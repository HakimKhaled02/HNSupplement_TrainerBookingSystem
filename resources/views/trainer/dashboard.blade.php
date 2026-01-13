@extends('layouts.dashboard')

@section('title', 'Trainer Dashboard')

@section('page-title', 'Welcome, ' . auth()->user()->name . '!')

@section('sidebar-menu')
    @include('components.trainer-sidebar')
@endsection

@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card dashboard-stat-card">
            <div class="dashboard-stat-icon">
                <i class="bi bi-calendar-check"></i>
            </div>
            <h3 class="dashboard-stat-title">Bookings</h3>
            <p class="dashboard-stat-value">{{ $totalBookings ?? 0 }}</p>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card dashboard-stat-card">
            <div class="dashboard-stat-icon">
                <i class="bi bi-star-fill"></i>
            </div>
            <h3 class="dashboard-stat-title">Rating</h3>
            <p class="dashboard-stat-value">{{ $trainer->rating ?? '0.00' }}</p>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card dashboard-stat-card">
            <div class="dashboard-stat-icon">
                <i class="bi bi-currency-dollar"></i>
            </div>
            <h3 class="dashboard-stat-title">Earnings</h3>
            <p class="dashboard-stat-value">$0</p>
        </div>
    </div>
</div>

<div class="card dashboard-card">
    <div class="card-body dashboard-card-body">
        <h2 class="dashboard-card-title mb-3">Your Profile</h2>
        <p class="dashboard-text mb-3">Complete your trainer profile to start receiving bookings.</p>
        <a href="{{ route('trainer.profile.edit') }}" class="btn dashboard-btn-primary">
            Edit Profile
        </a>
    </div>
</div>

@if(isset($recentBookings) && $recentBookings->count() > 0)
<div class="card dashboard-card">
    <div class="card-body dashboard-card-body">
        <h2 class="dashboard-card-title mb-3">Recent Bookings</h2>
        <div class="recent-bookings-list">
            @foreach($recentBookings as $booking)
            <div class="recent-booking-item">
                <div class="recent-booking-info">
                    <div class="recent-booking-customer">
                        <strong>{{ $booking->user->name }}</strong>
                    </div>
                    <div class="recent-booking-details">
                        <span class="recent-booking-date">
                            {{ \Carbon\Carbon::parse($booking->start_date)->format('M d') }} - 
                            {{ \Carbon\Carbon::parse($booking->end_date)->format('M d, Y') }}
                        </span>
                        <span class="recent-booking-amount">RM {{ number_format($booking->total_amount, 2) }}</span>
                    </div>
                </div>
                <span class="recent-booking-status paid">Paid</span>
            </div>
            @endforeach
        </div>
        <div class="mt-3">
            <a href="{{ route('trainer.bookings') }}" class="btn dashboard-btn-primary">
                View All Bookings
            </a>
        </div>
    </div>
</div>
@endif
@endsection

