<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\User;
use App\Booking;
use App\Customer;

class CustomerController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show customer profile page.
     */
    public function profile()
    {
        $user = Auth::user()->load('customer');
        
        return view('customer.profile', compact('user'));
    }

    /**
     * Show edit profile form.
     */
    public function editProfile()
    {
        $user = Auth::user();
        return view('customer.edit-profile', compact('user'));
    }

    /**
     * Update customer profile.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // Update or create customer record
        if ($user->customer) {
            $customer = $user->customer;
            $customer->phone = $request->phone ?? $customer->phone;
        } else {
            $customer = $user->customer()->create([
                'phone' => $request->phone,
            ]);
        }

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if exists
            if ($customer->profile_picture) {
                Storage::disk('public')->delete($customer->profile_picture);
            }
            $customer->profile_picture = $request->file('profile_picture')->store('customers/profile_pictures', 'public');
        }

        $customer->save();

        return redirect()->route('customer.profile')
            ->with('success', 'Profile updated successfully.');
    }

    /**
     * Show customer bookings.
     */
    public function bookings()
    {
        $user = Auth::user();
        $bookings = Booking::where('user_id', $user->id)
            ->with(['trainer.user'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        // Refresh bookings and update progress
        foreach ($bookings as $booking) {
            $booking->refresh();
            $booking->updateProgress();
        }
        
        return view('customer.bookings', compact('bookings'));
    }

    /**
     * Show attendance for a booking.
     */
    public function viewAttendance($id)
    {
        $user = Auth::user();
        $booking = Booking::where('id', $id)
            ->where('user_id', $user->id)
            ->where('payment_status', 'paid')
            ->with(['trainer', 'trainer.user'])
            ->firstOrFail();

        return view('customer.attendance', compact('booking'));
    }

    /**
     * Set reminder for a booking.
     */
    public function setReminder(Request $request, $id)
    {
        $user = Auth::user();
        $booking = Booking::where('id', $id)
            ->where('user_id', $user->id)
            ->where('payment_status', 'paid')
            ->firstOrFail();

        $request->validate([
            'reminder_date' => 'required|date|after:now',
            'reminder_note' => 'nullable|string|max:500',
        ]);

        // TODO: Implement reminder functionality (store in database, send notifications, etc.)
        // For now, just return success message
        
        return redirect()->route('customer.bookings')
            ->with('success', 'Reminder set successfully!');
    }

    /**
     * Submit feedback and rating for a booking.
     */
    public function submitFeedback(Request $request, $id)
    {
        $user = Auth::user();
        $booking = Booking::where('id', $id)
            ->where('user_id', $user->id)
            ->where('payment_status', 'paid')
            ->firstOrFail();

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'feedback' => 'required|string|min:10|max:1000',
        ]);

        // TODO: Implement feedback storage (create reviews table, store rating and feedback)
        // For now, just return success message
        
        return redirect()->route('customer.bookings')
            ->with('success', 'Thank you for your feedback!');
    }

    /**
     * Request refund for a booking.
     */
    public function requestRefund(Request $request, $id)
    {
        $user = Auth::user();
        $booking = Booking::where('id', $id)
            ->where('user_id', $user->id)
            ->where('payment_status', 'paid')
            ->firstOrFail();

        $request->validate([
            'refund_reason' => 'required|string|in:trainer_unavailable,service_not_as_described,cancelled_by_customer,technical_issues,other',
            'refund_details' => 'required|string|min:10|max:1000',
        ]);

        // TODO: Implement refund request functionality (store request, notify admin, process refund)
        // For now, just return success message
        
        return redirect()->route('customer.bookings')
            ->with('success', 'Refund request submitted successfully! We will review your request and get back to you soon.');
    }
}

