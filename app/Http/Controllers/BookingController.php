<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Booking;
use App\Trainer;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BookingController extends Controller
{
    /**
     * Store a new booking.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'trainer_id' => 'required|exists:trainers,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'selected_days' => 'required|array|min:3',
            'selected_days.*' => 'required|string',
            'time_slots' => 'required|array',
        ]);

        $trainer = Trainer::findOrFail($request->trainer_id);

        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to make a booking');
        }

        // Validate date range (max 1 month)
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        if ($startDate->diffInDays($endDate) > 31) {
            return back()->withErrors(['end_date' => 'Booking period cannot exceed one month (31 days)']);
        }

        // Validate at least 3 days
        if (count($request->selected_days) < 3) {
            return back()->withErrors(['selected_days' => 'Please select at least 3 days per week']);
        }

        // Build time slots array
        $timeSlots = [];
        foreach ($request->time_slots as $day => $slots) {
            if (is_array($slots) && count($slots) === 1) {
                $slot = $slots[0];
                $parts = explode('-', $slot);
                if (count($parts) === 2) {
                    $timeSlots[] = [
                        'day' => $day,
                        'start_time' => $parts[0],
                        'end_time' => $parts[1]
                    ];
                }
            } else {
                return back()->withErrors(['time_slots' => 'Please select exactly 1 time slot for each selected day']);
            }
        }

        // Check availability (exclude cancelled, refunded, and expired pending bookings)
        $bookings = Booking::where('trainer_id', $trainer->id)
            ->where(function($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate->toDateString(), $endDate->toDateString()])
                      ->orWhereBetween('end_date', [$startDate->toDateString(), $endDate->toDateString()])
                      ->orWhere(function($q) use ($startDate, $endDate) {
                          $q->where('start_date', '<=', $startDate->toDateString())
                            ->where('end_date', '>=', $endDate->toDateString());
                      });
            })
            ->where(function($query) {
                // Only include bookings that are:
                // 1. Paid (regardless of expiry)
                // 2. Pending but NOT expired (payment_expires_at is null OR in the future)
                $query->where('payment_status', 'paid')
                      ->orWhere(function($q) {
                          $q->where('payment_status', 'pending')
                            ->where(function($subQ) {
                                $subQ->whereNull('payment_expires_at')
                                     ->orWhere('payment_expires_at', '>', Carbon::now());
                            });
                      });
            })
            ->where('payment_status', '!=', 'cancelled')
            ->where('payment_status', '!=', 'refunded')
            ->get();

        // Check if any time slots are already booked
        foreach ($timeSlots as $slot) {
            foreach ($bookings as $booking) {
                if ($booking->time_slots && is_array($booking->time_slots)) {
                    foreach ($booking->time_slots as $bookedSlot) {
                        if (($bookedSlot['day'] ?? null) === $slot['day'] &&
                            ($bookedSlot['start_time'] ?? null) === $slot['start_time'] &&
                            ($bookedSlot['end_time'] ?? null) === $slot['end_time']) {
                            return back()->withErrors(['time_slots' => 'One or more selected time slots are already booked']);
                        }
                    }
                }
            }
        }

        // Calculate total amount (monthly rate)
        $totalAmount = $trainer->salary ?? 0;

        // Create booking with 1 minute payment expiry
        $booking = Booking::create([
            'trainer_id' => $trainer->id,
            'user_id' => Auth::id(),
            'start_date' => $startDate->toDateString(),
            'end_date' => $endDate->toDateString(),
            'selected_days' => $request->selected_days,
            'time_slots' => $timeSlots,
            'total_amount' => $totalAmount,
            'payment_status' => 'pending',
            'payment_expires_at' => Carbon::now()->addMinute(1),
            'status' => 'active',
        ]);

        // Redirect to payment page
        return redirect()->route('booking.payment', $booking->id)
            ->with('success', 'Booking created successfully. Please complete payment.');
    }

    /**
     * Show payment page for a booking.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function payment($id)
    {
        $booking = Booking::with(['trainer.user', 'user'])
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Check if already paid
        if ($booking->payment_status === 'paid') {
            return redirect()->route('booking.success', $booking->id)
                ->with('info', 'This booking has already been paid.');
        }

        // Check if booking has expired and cancel it
        if ($booking->payment_status === 'pending' && 
            $booking->payment_expires_at && 
            $booking->payment_expires_at <= Carbon::now()) {
            $booking->update([
                'payment_status' => 'cancelled',
                'status' => 'cancelled'
            ]);
            return redirect()->route('trainers')
                ->with('error', 'Your booking has expired. Please create a new booking.');
        }

        return view('bookings.payment', compact('booking'));
    }

    /**
     * Process payment for a booking.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function processPayment(Request $request, $id)
    {
        $booking = Booking::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($booking->payment_status === 'paid') {
            return redirect()->route('booking.success', $booking->id)
                ->with('info', 'This booking has already been paid.');
        }

        // Check if payment has expired
        if ($booking->payment_expires_at && Carbon::now()->greaterThan($booking->payment_expires_at)) {
            // Cancel the booking if payment expired
            $booking->update([
                'payment_status' => 'cancelled',
                'status' => 'cancelled'
            ]);
            
            return redirect()->route('trainers')
                ->with('error', 'Payment time has expired. Your booking has been cancelled. Please try again.');
        }

        // Here you would integrate with payment gateway (Stripe, PayPal, etc.)
        // For now, we'll just mark it as paid
        $booking->update([
            'payment_status' => 'paid',
            'status' => 'active'
        ]);

        return redirect()->route('booking.success', $booking->id)
            ->with('success', 'Payment completed successfully!');
    }

    /**
     * Show success page after payment.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function success($id)
    {
        $booking = Booking::with(['trainer.user', 'user'])
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('bookings.success', compact('booking'));
    }
}
