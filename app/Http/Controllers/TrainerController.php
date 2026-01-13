<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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

        return view('trainer.dashboard', compact('trainer'));
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

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'state' => 'nullable|string|max:255',
            'area' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Update user name
        $user = Auth::user();
        $user->name = $request->name;
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

        // Check if trainer has any active/incomplete bookings
        // A booking is incomplete if it's paid and the end_date hasn't passed yet
        $incompleteBookings = \App\Booking::where('trainer_id', $trainer->id)
            ->where('payment_status', 'paid')
            ->where('end_date', '>=', now()->toDateString())
            ->exists();

        // Trainer can set availability only if there are NO incomplete bookings
        return !$incompleteBookings;
    }
}
