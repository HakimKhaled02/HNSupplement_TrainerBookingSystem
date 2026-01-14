<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Booking;
use App\Review;

class TrainerController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show trainer dashboard.
     */
    public function dashboard()
    {
        // Check if user is trainer
        if (Auth::user()->role !== 'trainer') {
            abort(403, 'Unauthorized access');
        }

        $trainer = Auth::user()->trainer;

        // Check if trainer is approved
        if ($trainer && $trainer->status === 'pending') {
            return view('trainer.pending');
        }

        // Get recent paid bookings (last 5)
        $recentBookings = Booking::where('trainer_id', $trainer->id)
            ->where('payment_status', 'paid')
            ->with(['user', 'user.customer'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Update progress for recent bookings
        foreach ($recentBookings as $booking) {
            $booking->updateProgress();
        }

        // Get total bookings count
        $totalBookings = Booking::where('trainer_id', $trainer->id)
            ->where('payment_status', 'paid')
            ->count();

        // Calculate total earnings from completed bookings only
        $totalEarnings = Booking::where('trainer_id', $trainer->id)
            ->where('payment_status', 'paid')
            ->where('progress', 'completed')
            ->sum('total_amount');

        return view('trainer.dashboard', compact('trainer', 'recentBookings', 'totalBookings', 'totalEarnings'));
    }

    /**
     * Show trainer profile.
     */
    public function profile()
    {
        // Check if user is trainer
        if (Auth::user()->role !== 'trainer') {
            abort(403, 'Unauthorized access');
        }

        $trainer = Auth::user()->trainer;

        // Check if trainer is approved
        if ($trainer && $trainer->status === 'pending') {
            return view('trainer.pending');
        }

        return view('trainer.profile', compact('trainer'));
    }

    /**
     * Show trainer reviews.
     */
    public function reviews()
    {
        // Check if user is trainer
        if (Auth::user()->role !== 'trainer') {
            abort(403, 'Unauthorized access');
        }

        $trainer = Auth::user()->trainer;

        // Check if trainer is approved
        if ($trainer && $trainer->status === 'pending') {
            return view('trainer.pending');
        }

        // Load reviews with user information
        $reviews = [];
        if ($trainer) {
            $reviews = Review::where('trainer_id', $trainer->id)
                ->with(['user', 'booking'])
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('trainer.reviews', compact('trainer', 'reviews'));
    }

    /**
     * Show edit profile form.
     */
    public function editProfile()
    {
        // Check if user is trainer
        if (Auth::user()->role !== 'trainer') {
            abort(403, 'Unauthorized access');
        }

        $trainer = Auth::user()->trainer;

        // Check if trainer is approved
        if ($trainer && $trainer->status === 'pending') {
            return view('trainer.pending');
        }

        return view('trainer.edit-profile', compact('trainer'));
    }

    /**
     * Update trainer profile.
     */
    public function updateProfile(Request $request)
    {
        // Check if user is trainer
        if (Auth::user()->role !== 'trainer') {
            abort(403, 'Unauthorized access');
        }

        $trainer = Auth::user()->trainer;

        if (!$trainer) {
            return redirect()->route('trainer.dashboard')->with('error', 'Trainer profile not found.');
        }

        $validationRules = [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'state' => 'nullable|string|max:255',
            'area' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        // Add password validation only if password is being changed
        if ($request->filled('password')) {
            $validationRules['current_password'] = 'required';
            $validationRules['password'] = 'required|min:8|confirmed';
        }

        $request->validate($validationRules);

        // Update user name
        $user = Auth::user();
        $user->name = $request->name;

        // Update password if provided
        if ($request->filled('password')) {
            // Verify current password
            if (!$request->filled('current_password') || !Hash::check($request->current_password, $user->password)) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['current_password' => 'Current password is incorrect.']);
            }

            // Update password
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // Update trainer profile (excluding salary, qualification_file, and status)
        $trainer->phone = $request->phone;
        $trainer->state = $request->state;
        $trainer->area = $request->area;
        $trainer->category = $request->category;
        $trainer->latitude = $request->latitude;
        $trainer->longitude = $request->longitude;

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if exists
            if ($trainer->profile_picture) {
                Storage::disk('public')->delete($trainer->profile_picture);
            }
            $trainer->profile_picture = $request->file('profile_picture')->store('trainers/profile_pictures', 'public');
        }

        $trainer->save();

        return redirect()->route('trainer.profile')->with('success', 'Profile updated successfully!');
    }

    /**
     * Show availability management page.
     */
    public function availability()
    {
        // Check if user is trainer
        if (Auth::user()->role !== 'trainer') {
            abort(403, 'Unauthorized access');
        }

        $trainer = Auth::user()->trainer;

        // Check if trainer is approved
        if ($trainer && $trainer->status === 'pending') {
            return view('trainer.pending');
        }

        // Check if trainer has completed bookings
        // TODO: Replace with actual bookings check when bookings table is created
        $hasCompletedBookings = $this->hasCompletedBookings($trainer);

        // Get current availability
        $currentAvailability = $trainer->availability ?? [];

        return view('trainer.availability', compact('trainer', 'hasCompletedBookings', 'currentAvailability'));
    }

    /**
     * Update trainer availability.
     */
    public function updateAvailability(Request $request)
    {
        // Check if user is trainer
        if (Auth::user()->role !== 'trainer') {
            abort(403, 'Unauthorized access');
        }

        $trainer = Auth::user()->trainer;

        if (!$trainer) {
            return redirect()->route('trainer.dashboard')->with('error', 'Trainer profile not found.');
        }

        // Check if trainer has any incomplete bookings
        $hasCompletedBookings = $this->hasCompletedBookings($trainer);
        
        if (!$hasCompletedBookings) {
            return redirect()->route('trainer.dashboard')
                ->with('error', 'You can only set your availability when all your bookings are fully completed. Please wait until all active bookings have ended.');
        }

        // Fixed 2-hour time slots
        $timeSlots = [
            '08:00' => '10:00',
            '10:00' => '12:00',
            '12:00' => '14:00',
            '14:00' => '16:00',
            '16:00' => '18:00',
            '18:00' => '20:00',
            '20:00' => '22:00',
        ];

        // Process availability data from checkboxes
        $availability = [];
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        $daysWithSlots = [];
        
        foreach ($days as $day) {
            if (isset($request->availability[$day]) && is_array($request->availability[$day])) {
                foreach ($request->availability[$day] as $startTime => $value) {
                    if ($value == '1' && isset($timeSlots[$startTime])) {
                        $availability[] = [
                            'day' => $day,
                            'start_time' => $startTime,
                            'end_time' => $timeSlots[$startTime],
                        ];
                        if (!in_array($day, $daysWithSlots)) {
                            $daysWithSlots[] = $day;
                        }
                    }
                }
            }
        }

        // Ensure at least 3 days
        if (count($daysWithSlots) < 3) {
            return redirect()->route('trainer.availability')
                ->with('error', 'You must select at least 3 days per week.')
                ->withInput();
        }

        // Update trainer availability
        $trainer->availability = $availability;
        $trainer->save();

        return redirect()->route('trainer.availability')
            ->with('success', 'Availability updated successfully!');
    }

    /**
     * Check if trainer has any incomplete bookings.
     * Trainer can only set availability when all bookings are fully completed.
     */
    private function hasCompletedBookings($trainer)
    {
        if (!$trainer) {
            return false;
        }

        // Check if trainer has any incomplete bookings
        // A booking is incomplete if it's paid and progress is not 'completed'
        // Only paid bookings that are not completed block availability updates
        $incompleteBookings = \App\Booking::where('trainer_id', $trainer->id)
            ->where('payment_status', 'paid')
            ->where(function($query) {
                $query->where('progress', '!=', 'completed')
                      ->orWhereNull('progress');
            })
            ->exists();

        // Trainer can set availability only if there are NO incomplete bookings
        return !$incompleteBookings;
    }

    /**
     * Show trainer bookings.
     */
    public function bookings()
    {
        // Check if user is trainer
        if (Auth::user()->role !== 'trainer') {
            abort(403, 'Unauthorized access');
        }

        $trainer = Auth::user()->trainer;

        if (!$trainer) {
            return redirect()->route('trainer.dashboard')->with('error', 'Trainer profile not found.');
        }

        // Get only paid bookings for this trainer
        $bookings = Booking::where('trainer_id', $trainer->id)
            ->where('payment_status', 'paid')
            ->with(['user', 'user.customer'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        // Update progress for all bookings
        foreach ($bookings as $booking) {
            $booking->updateProgress();
        }
        
        return view('trainer.bookings', compact('bookings', 'trainer'));
    }

    /**
     * Show attendance management page for a booking.
     */
    public function manageAttendance($id)
    {
        // Check if user is trainer
        if (Auth::user()->role !== 'trainer') {
            abort(403, 'Unauthorized access');
        }

        $trainer = Auth::user()->trainer;
        $booking = Booking::where('id', $id)
            ->where('trainer_id', $trainer->id)
            ->where('payment_status', 'paid')
            ->with(['user', 'user.customer'])
            ->firstOrFail();

        // Redirect to bookings page - attendance is now managed via modal
        return redirect()->route('trainer.bookings')->with('open_attendance_modal', $booking->id);
    }

    /**
     * Update attendance for a booking.
     */
    public function updateAttendance(Request $request, $id)
    {
        // Check if user is trainer
        if (Auth::user()->role !== 'trainer') {
            abort(403, 'Unauthorized access');
        }

        $trainer = Auth::user()->trainer;
        $booking = Booking::where('id', $id)
            ->where('trainer_id', $trainer->id)
            ->where('payment_status', 'paid')
            ->firstOrFail();

        $request->validate([
            'attendance' => 'required|array',
        ]);

        // Process attendance data
        $attendance = [];
        foreach ($request->attendance as $key => $status) {
            // Key format: "day_start_time_date" e.g., "monday_08:00_2026-01-15"
            // Date has dashes, so when we split by '_', the date remains as one part
            $parts = explode('_', $key);
            if (count($parts) >= 3) {
                $day = $parts[0];
                $startTime = $parts[1];
                // The date is in parts[2] (format: Y-m-d) since it has dashes, not underscores
                $date = $parts[2];
                
                // If date was somehow split (shouldn't happen), join remaining parts
                if (count($parts) > 3) {
                    $date = implode('-', array_slice($parts, 2));
                }
                
                $attendance[] = [
                    'day' => $day,
                    'start_time' => $startTime,
                    'date' => $date,
                    'status' => $status, // 'present' or 'absent'
                    'marked_at' => now()->toDateTimeString(),
                ];
            }
        }

        // Update attendance and save
        $booking->attendance = $attendance;
        $booking->save();
        
        // Calculate and update progress
        $booking->updateProgress();
        
        // Refresh the booking to ensure data is up to date
        $booking->refresh();

        return redirect()->route('trainer.bookings')
            ->with('success', 'Attendance updated successfully!');
    }
}
