<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}
