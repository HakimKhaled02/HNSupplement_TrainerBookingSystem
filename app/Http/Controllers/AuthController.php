<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
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
            
            // Customer redirects to homepage
            return redirect()->route('home');
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
     * Handle user logout.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}

