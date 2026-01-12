<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Trainer;
use App\User;
use App\Staff;
use App\Mail\TrainerApprovedMail;
use App\Mail\TrainerRejectedMail;
use Illuminate\Support\Facades\Mail;

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

        $pendingTrainers = Trainer::where('status', 'pending')
            ->with('user')
            ->get();

        $activeTrainers = Trainer::where('status', 'active')
            ->with('user')
            ->get();

        return view('admin.dashboard', compact('pendingTrainers', 'activeTrainers'));
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
        Mail::to($trainer->user->email)->send(new TrainerApprovedMail($trainer->user, $temporaryPassword));

        return back()->with('success', 'Trainer approved and email sent successfully!');
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
        Mail::to($trainer->user->email)->send(new TrainerRejectedMail($trainer->user));

        return back()->with('success', 'Trainer rejected and email sent successfully.');
    }
}
