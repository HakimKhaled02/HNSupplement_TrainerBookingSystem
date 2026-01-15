<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Trainer;
use App\User;
use App\Staff;
use App\Booking;
use App\Mail\TrainerApprovedMail;
use App\Mail\TrainerRejectedMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    /**
     * Show admin login page.
     */
    public function showLogin()
    {
        return view('admin.login');
    }

    /**
     * Handle admin login.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Fixed admin credentials
        $adminEmail = 'admin@hnsupplement.com';
        $adminPassword = 'Admin@123'; // Fixed password

        // Check if email matches
        if ($request->email !== $adminEmail) {
            return back()->withErrors([
                'email' => 'Invalid admin credentials.',
            ])->withInput($request->only('email'));
        }

        // Find or create staff user
        $user = User::where('email', $adminEmail)->where('role', 'staff')->first();

        if (!$user) {
            // Create staff user if doesn't exist with hashed password
            $user = User::create([
                'name' => 'Admin Staff',
                'email' => $adminEmail,
                'password' => Hash::make($adminPassword),
                'role' => 'staff',
            ]);

            // Create staff profile
            Staff::create([
                'user_id' => $user->id,
                'department' => 'Administration',
                'position' => 'Administrator',
            ]);
        }

        // Check password
        if (Hash::check($request->password, $user->password)) {
            // Login the user
            Auth::login($user);
            $request->session()->regenerate();

            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors([
            'email' => 'Invalid admin credentials.',
        ])->withInput($request->only('email'));
    }

    /**
     * Show admin dashboard.
     */
    public function dashboard()
    {
        // Check if user is staff
        if (!Auth::check() || Auth::user()->role !== 'staff') {
            return redirect()->route('admin.login')->with('error', 'Please login as admin to access the dashboard.');
        }

        return view('admin.dashboard');
    }

    /**
     * Show trainer approvals page.
     */
    public function approvals()
    {
        // Check if user is staff
        if (!Auth::check() || Auth::user()->role !== 'staff') {
            return redirect()->route('admin.login')->with('error', 'Please login as admin to access the approvals page.');
        }

        $pendingTrainers = Trainer::where('status', 'pending')
            ->with('user')
            ->get();

        $activeTrainers = Trainer::where('status', 'active')
            ->with('user')
            ->get();

        return view('admin.approvals', compact('pendingTrainers', 'activeTrainers'));
    }

    /**
     * Approve a trainer.
     */
    public function approveTrainer($id)
    {
        // Check if user is staff
        if (Auth::user()->role !== 'staff') {
            abort(403, 'Unauthorized access');
        }

        $trainer = Trainer::with('user')->findOrFail($id);

        if ($trainer->status !== 'pending') {
            return back()->with('error', 'Trainer is not pending approval.');
        }

        // Generate temporary password
        $temporaryPassword = Str::random(12);

        // Update trainer status and set temporary password
        $trainer->status = 'active';
        $trainer->save();

        // Update user password with temporary password
        $trainer->user->password = Hash::make($temporaryPassword);
        $trainer->user->save();

        // Send email with login credentials
        $mailMailer = env('MAIL_MAILER');
        $emailSent = false;
        $emailError = null;

        if ($mailMailer) {
            try {
                Mail::to($trainer->user->email)->send(new TrainerApprovedMail($trainer->user, $temporaryPassword));
                $emailSent = true;
            } catch (\TypeError $e) {
                Log::warning('Mail configuration error when sending trainer approval email: ' . $e->getMessage());
                $emailError = 'Mail configuration error. Trainer approved but email could not be sent.';
            } catch (\Exception $e) {
                Log::error('Failed to send trainer approval email: ' . $e->getMessage());
                $emailError = 'Failed to send email. Trainer approved but email could not be sent.';
            }
        } else {
            Log::warning('Mail not configured. Trainer approved but email was not sent.');
            $emailError = 'Mail not configured. Trainer approved but email was not sent.';
        }

        if ($emailSent) {
            return redirect()->route('admin.approvals')->with('success', 'Trainer approved and email sent successfully!');
        } else {
            return redirect()->route('admin.approvals')
                ->with('success', 'Trainer approved successfully!')
                ->with('warning', $emailError . ' Temporary password: ' . $temporaryPassword);
        }
    }

    /**
     * Reject a trainer.
     */
    public function rejectTrainer($id)
    {
        // Check if user is staff
        if (Auth::user()->role !== 'staff') {
            abort(403, 'Unauthorized access');
        }

        $trainer = Trainer::findOrFail($id);

        if ($trainer->status !== 'pending') {
            return back()->with('error', 'Trainer is not pending approval.');
        }

        // Update trainer status
        $trainer->status = 'inactive';
        $trainer->save();

        // Send rejection email
        $mailMailer = env('MAIL_MAILER');
        $emailSent = false;
        $emailError = null;

        if ($mailMailer) {
            try {
                Mail::to($trainer->user->email)->send(new TrainerRejectedMail($trainer->user));
                $emailSent = true;
            } catch (\TypeError $e) {
                Log::warning('Mail configuration error when sending trainer rejection email: ' . $e->getMessage());
                $emailError = 'Mail configuration error. Trainer rejected but email could not be sent.';
            } catch (\Exception $e) {
                Log::error('Failed to send trainer rejection email: ' . $e->getMessage());
                $emailError = 'Failed to send email. Trainer rejected but email could not be sent.';
            }
        } else {
            Log::warning('Mail not configured. Trainer rejected but email was not sent.');
            $emailError = 'Mail not configured. Trainer rejected but email was not sent.';
        }

        if ($emailSent) {
            return redirect()->route('admin.approvals')->with('success', 'Trainer rejected and email sent successfully.');
        } else {
            return redirect()->route('admin.approvals')
                ->with('success', 'Trainer rejected successfully.')
                ->with('warning', $emailError);
        }
    }

    /**
     * Show all approved trainers.
     */
    public function trainers()
    {
        // Check if user is staff
        if (!Auth::check() || Auth::user()->role !== 'staff') {
            return redirect()->route('admin.login')->with('error', 'Please login as admin to access the trainers page.');
        }

        $trainers = Trainer::where('status', 'active')
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.trainers', compact('trainers'));
    }

    /**
     * Update trainer salary.
     */
    public function updateSalary(Request $request, $id)
    {
        // Check if user is staff
        if (Auth::user()->role !== 'staff') {
            abort(403, 'Unauthorized access');
        }

        $request->validate([
            'salary' => 'required|numeric|min:0|max:999999.99',
        ]);

        $trainer = Trainer::findOrFail($id);
        $trainer->salary = $request->salary;
        $trainer->save();

        return redirect()->route('admin.trainers')->with('success', 'Trainer salary updated successfully.');
    }

    /**
     * Show admin profile.
     */
    public function profile()
    {
        // Check if user is staff
        if (!Auth::check() || Auth::user()->role !== 'staff') {
            return redirect()->route('admin.login')->with('error', 'Please login as admin to access the profile page.');
        }

        $user = Auth::user();
        $staff = $user->staff;

        return view('admin.profile', compact('user', 'staff'));
    }

    /**
     * Show edit profile form.
     */
    public function editProfile()
    {
        // Check if user is staff
        if (!Auth::check() || Auth::user()->role !== 'staff') {
            return redirect()->route('admin.login')->with('error', 'Please login as admin to access the profile page.');
        }

        $user = Auth::user();
        $staff = $user->staff;

        return view('admin.edit-profile', compact('user', 'staff'));
    }

    /**
     * Update admin profile.
     */
    public function updateProfile(Request $request)
    {
        // Check if user is staff
        if (!Auth::check() || Auth::user()->role !== 'staff') {
            abort(403, 'Unauthorized access');
        }

        $user = Auth::user();
        $staff = $user->staff;

        if (!$staff) {
            return redirect()->route('admin.dashboard')->with('error', 'Staff profile not found.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Update user information
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        // Update staff profile
        $staff->phone = $request->phone;
        $staff->department = $request->department;
        $staff->position = $request->position;

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if exists
            if ($staff->profile_picture) {
                Storage::disk('public')->delete($staff->profile_picture);
            }
            $staff->profile_picture = $request->file('profile_picture')->store('staff/profile_pictures', 'public');
        }

        $staff->save();

        // Refresh the relationship to ensure updated data is available
        $user->refresh();
        $staff->refresh();

        return redirect()->route('admin.profile')->with('success', 'Profile updated successfully!');
    }

    /**
     * Monitor all bookings.
     */
    public function monitorBookings()
    {
        // Check if user is staff
        if (!Auth::check() || Auth::user()->role !== 'staff') {
            return redirect()->route('admin.login')->with('error', 'Please login as admin to access the bookings page.');
        }

        // Get all bookings with relationships
        $bookings = Booking::with(['trainer.user', 'user.customer'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Update progress for all bookings
        foreach ($bookings as $booking) {
            $booking->updateProgress();
        }

        // Get statistics
        $totalBookings = Booking::count();
        $paidBookings = Booking::where('payment_status', 'paid')->count();
        $completedBookings = Booking::where('progress', 'completed')->count();
        $ongoingBookings = Booking::where('progress', 'ongoing')->count();
        $upcomingBookings = Booking::where('progress', 'upcoming')->count();

        return view('admin.monitor-bookings', compact(
            'bookings',
            'totalBookings',
            'paidBookings',
            'completedBookings',
            'ongoingBookings',
            'upcomingBookings'
        ));
    }
}
