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
        
        return view('customer.bookings', compact('bookings'));
    }
}

