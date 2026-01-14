<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\User;
use App\Customer;
use App\Trainer;
use App\Staff;

class AuthController extends Controller
{
    /**
     * Display the login page.
     *
     * @return \Illuminate\View\View
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Handle login form submission.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'role' => 'required|in:customer,trainer',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $user = Auth::user();
            
            // Check if user role matches selected role
            if ($user->role !== $request->role) {
                Auth::logout();
                return back()->withErrors([
                    'role' => 'The selected role does not match your account type.',
                ])->withInput($request->only('email'));
            }

            // Check if trainer is approved
            if ($user->role === 'trainer') {
                $trainer = $user->trainer;
                if (!$trainer || $trainer->status !== 'active') {
                    Auth::logout();
                    return back()->withErrors([
                        'email' => 'Your trainer account is pending approval. You will receive an email once approved.',
                    ])->withInput($request->only('email', 'role'));
                }
            }

            $request->session()->regenerate();
            
            // Redirect based on role
            if ($user->role === 'trainer') {
                return redirect()->route('trainer.dashboard');
            } elseif ($user->role === 'staff') {
                return redirect()->route('admin.dashboard');
            }
            
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email', 'role'));
    }

    /**
     * Display the sign up page.
     *
     * @return \Illuminate\View\View
     */
    public function showSignup()
    {
        return view('auth.signup');
    }

    /**
     * Handle sign up form submission.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function signup(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer', // Default role for signup
        ]);

        // Create customer profile
        Customer::create([
            'user_id' => $user->id,
        ]);

        // Redirect to login page instead of auto-login
        return redirect()->route('login')->with('success', 'Registration successful! Please login to continue.');
    }

    /**
     * Display the trainer registration page.
     *
     * @return \Illuminate\View\View
     */
    public function showTrainerSignup()
    {
        return view('auth.trainer-signup');
    }

    /**
     * Handle trainer registration form submission.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function trainerSignup(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'state' => 'required|string',
            'area' => 'required|string|max:255',
            'category' => 'required|string',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'qualification_file' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:5120',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        // Generate a temporary password (will be changed when approved)
        $temporaryPassword = Str::random(12);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($temporaryPassword),
            'role' => 'trainer',
        ]);

        // Handle file uploads
        $profilePicturePath = null;
        $qualificationFilePath = null;

        if ($request->hasFile('profile_picture')) {
            $profilePicturePath = $request->file('profile_picture')->store('trainers/profile_pictures', 'public');
        }

        if ($request->hasFile('qualification_file')) {
            $qualificationFilePath = $request->file('qualification_file')->store('trainers/qualifications', 'public');
        }

        // Create trainer profile
        Trainer::create([
            'user_id' => $user->id,
            'phone' => $request->phone,
            'state' => $request->state,
            'area' => $request->area,
            'category' => $request->category,
            'profile_picture' => $profilePicturePath,
            'qualification_file' => $qualificationFilePath,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'status' => 'pending', // New trainers need approval
        ]);

        // Don't auto-login, wait for staff approval
        return redirect()->route('login')->with('info', 'Your trainer registration is pending approval. You will receive an email with login credentials once approved.');
    }

    /**
     * Handle logout.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('home')->with('success', 'You have been logged out successfully.');
    }

    /**
     * Show forgot password form.
     *
     * @return \Illuminate\View\View
     */
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send password reset link.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendPasswordReset(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'We could not find a user with that email address.'])->withInput();
        }

        // Generate token
        $token = Str::random(64);

        // Delete any existing tokens for this email
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // Insert new token
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => Hash::make($token),
            'created_at' => Carbon::now(),
        ]);

        // Send email with reset link
        $resetUrl = route('password.reset', ['token' => $token]);

        // Check if mail is configured before attempting to send
        $mailMailer = env('MAIL_MAILER');
        $emailSent = false;

        if ($mailMailer) {
            try {
                Mail::raw("Click the following link to reset your password: {$resetUrl}", function ($message) use ($user) {
                    $message->to($user->email)
                            ->subject('Password Reset Request');
                });
                $emailSent = true;
            } catch (\TypeError $e) {
                Log::warning('Mail configuration error when sending password reset email: ' . $e->getMessage());
            } catch (\Exception $e) {
                Log::error('Failed to send password reset email: ' . $e->getMessage());
            }
        }

        if ($emailSent) {
            return redirect()->route('login')->with('success', 'Password reset link has been sent to your email address.');
        } else {
            // For development: log the reset link if mail is not configured
            Log::info('Password reset link for ' . $user->email . ': ' . $resetUrl);
            
            // Store the reset link in session for display on a separate page if needed
            if (config('app.debug')) {
                return redirect()->route('password.reset', ['token' => $token])
                    ->with('info', 'Mail is not configured. Use this page to reset your password.');
            } else {
                return redirect()->route('login')
                    ->with('info', 'Password reset link has been generated. Please check your email. If mail is not configured, check the application logs for the reset link.');
            }
        }
    }

    /**
     * Show reset password form.
     *
     * @param  string  $token
     * @return \Illuminate\View\View
     */
    public function showResetPassword($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    /**
     * Reset password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Find token in database
        $passwordReset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$passwordReset) {
            return back()->withErrors(['email' => 'Invalid or expired reset token.'])->withInput();
        }

        // Check if token matches
        if (!Hash::check($request->token, $passwordReset->token)) {
            return back()->withErrors(['token' => 'Invalid or expired reset token.'])->withInput();
        }

        // Check if token is expired (60 minutes)
        if (Carbon::parse($passwordReset->created_at)->addMinutes(60)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return back()->withErrors(['token' => 'This password reset token has expired.'])->withInput();
        }

        // Update user password
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // Delete token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('success', 'Your password has been reset successfully. Please login with your new password.');
    }
}

