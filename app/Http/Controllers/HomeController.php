<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Trainer;
use App\User;
use App\Booking;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Display the homepage.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('home');
    }

    /**
     * Display all trainers with filters.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function trainers(Request $request)
    {
        $query = Trainer::with('user')
            ->where('status', 'active');

        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->where('category', $request->category);
        }

        // Filter by state
        if ($request->has('state') && $request->state) {
            $query->where('state', $request->state);
        }

        // Filter by area
        if ($request->has('area') && $request->area) {
            $query->where('area', 'like', '%' . $request->area . '%');
        }

        // Filter by availability day
        if ($request->has('availability_day') && $request->availability_day) {
            $query->where('availability', 'like', '%"day":"' . $request->availability_day . '"%');
        }

        // Filter by minimum rating
        if ($request->has('min_rating') && $request->min_rating) {
            $query->where('rating', '>=', $request->min_rating);
        }

        $trainers = $query->orderBy('rating', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        // Filter trainers that have enough availability (3 days/week, 1 slot/day minimum)
        $trainers = $trainers->filter(function($trainer) {
            return $this->hasEnoughAvailability($trainer);
        });

        // Paginate manually
        $perPage = 12;
        $currentPage = $request->get('page', 1);
        $items = $trainers->slice(($currentPage - 1) * $perPage, $perPage)->values();
        $trainers = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $trainers->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // Get unique values for filters
        $categories = Trainer::where('status', 'active')
            ->distinct()
            ->pluck('category')
            ->filter()
            ->sort()
            ->values();

        $states = Trainer::where('status', 'active')
            ->distinct()
            ->pluck('state')
            ->filter()
            ->sort()
            ->values();

        return view('trainers.index', compact('trainers', 'categories', 'states'));
    }

    /**
     * Show booking page for a specific trainer.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function bookTrainer($id)
    {
        $trainer = Trainer::with('user')->where('id', $id)->where('status', 'active')->firstOrFail();
        
        // Get unique days from availability
        $availableDays = [];
        if ($trainer->availability && is_array($trainer->availability)) {
            $dayNames = [
                'monday' => 'Monday',
                'tuesday' => 'Tuesday',
                'wednesday' => 'Wednesday',
                'thursday' => 'Thursday',
                'friday' => 'Friday',
                'saturday' => 'Saturday',
                'sunday' => 'Sunday'
            ];
            
            foreach ($trainer->availability as $avail) {
                if (isset($avail['day']) && isset($dayNames[$avail['day']])) {
                    $dayKey = $avail['day'];
                    if (!isset($availableDays[$dayKey])) {
                        $availableDays[$dayKey] = [
                            'name' => $dayNames[$dayKey],
                            'timeSlots' => []
                        ];
                    }
                    if (isset($avail['start_time']) && isset($avail['end_time'])) {
                        $availableDays[$dayKey]['timeSlots'][] = [
                            'start' => $avail['start_time'],
                            'end' => $avail['end_time']
                        ];
                    }
                }
            }
        }

        return view('trainers.book', compact('trainer', 'availableDays'));
    }

    /**
     * Check availability for a trainer.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkAvailability(Request $request, $id)
    {
        $trainer = Trainer::where('id', $id)->where('status', 'active')->firstOrFail();
        
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        
        if (!$startDate || !$endDate) {
            return response()->json([
                'available' => false,
                'message' => 'Start date and end date are required'
            ]);
        }
        
        // Cancel any expired pending bookings for this trainer (cleanup)
        $this->cancelExpiredBookings($trainer->id);
        
        // Get all bookings for this trainer in the date range (excluding cancelled/refunded/expired)
        $bookings = Booking::where('trainer_id', $trainer->id)
            ->where(function($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                      ->orWhereBetween('end_date', [$startDate, $endDate])
                      ->orWhere(function($q) use ($startDate, $endDate) {
                          $q->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
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
        
        // Build availability map: day => [time slots that are booked]
        $bookedSlots = [];
        foreach ($bookings as $booking) {
            if ($booking->time_slots && is_array($booking->time_slots)) {
                foreach ($booking->time_slots as $slot) {
                    $day = $slot['day'] ?? null;
                    $startTime = $slot['start_time'] ?? null;
                    $endTime = $slot['end_time'] ?? null;
                    
                    if ($day && $startTime && $endTime) {
                        if (!isset($bookedSlots[$day])) {
                            $bookedSlots[$day] = [];
                        }
                        $bookedSlots[$day][] = [
                            'start_time' => $startTime,
                            'end_time' => $endTime,
                            'payment_status' => $booking->payment_status
                        ];
                    }
                }
            }
        }
        
        return response()->json([
            'available' => true,
            'booked_slots' => $bookedSlots
        ]);
    }

    /**
     * Cancel expired pending bookings for a trainer.
     *
     * @param int $trainerId
     * @return void
     */
    private function cancelExpiredBookings($trainerId)
    {
        Booking::where('trainer_id', $trainerId)
            ->where('payment_status', 'pending')
            ->whereNotNull('payment_expires_at')
            ->where('payment_expires_at', '<=', Carbon::now())
            ->update([
                'payment_status' => 'cancelled',
                'status' => 'cancelled'
            ]);
    }

    /**
     * Check if trainer has enough availability for monthly booking.
     *
     * @param Trainer $trainer
     * @return bool
     */
    private function hasEnoughAvailability($trainer)
    {
        if (!$trainer->availability || !is_array($trainer->availability)) {
            return false;
        }
        
        // Count unique days with at least one time slot
        $daysWithSlots = [];
        foreach ($trainer->availability as $avail) {
            if (isset($avail['day']) && isset($avail['start_time']) && isset($avail['end_time'])) {
                $day = $avail['day'];
                if (!in_array($day, $daysWithSlots)) {
                    $daysWithSlots[] = $day;
                }
            }
        }
        
        // Need at least 3 days per week
        return count($daysWithSlots) >= 3;
    }
}

