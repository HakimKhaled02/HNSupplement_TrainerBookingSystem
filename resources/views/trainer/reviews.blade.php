@extends('layouts.dashboard')

@section('title', 'Reviews & Ratings - Trainer Dashboard')

@section('page-title', 'Reviews & Ratings')

@section('sidebar-menu')
    @include('components.trainer-sidebar')
@endsection

@section('content')
<div class="card dashboard-card">
    <div class="card-body dashboard-card-body">
        @if(isset($reviews) && $reviews->count() > 0)
        <div class="reviews-list">
            @foreach($reviews as $review)
            <div class="review-item">
                <div class="review-header">
                    <div class="review-user-info">
                        <div class="review-user-avatar">
                            @if($review->user->customer && $review->user->customer->profile_picture)
                                <img src="{{ asset('storage/' . $review->user->customer->profile_picture) }}" 
                                     alt="{{ $review->user->name }}" 
                                     class="review-avatar-img">
                            @else
                                <div class="review-avatar-placeholder">
                                    {{ strtoupper(substr($review->user->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <div class="review-user-details">
                            <h5 class="review-user-name">{{ $review->user->name }}</h5>
                            <p class="review-date">{{ $review->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                    <div class="review-rating">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="bi bi-star-fill {{ $i <= $review->rating ? 'star-filled' : 'star-empty' }}"></i>
                        @endfor
                        <span class="review-rating-value">{{ $review->rating }}.0</span>
                    </div>
                </div>
                <div class="review-feedback">
                    <p>{{ $review->feedback }}</p>
                </div>
                @if($review->booking)
                <div class="review-booking-info">
                    <small class="text-muted">Booking #{{ $review->booking->id }}</small>
                </div>
                @endif
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-5">
            <p class="dashboard-text">No reviews yet. Complete bookings to receive feedback from customers.</p>
        </div>
        @endif
    </div>
</div>
@endsection

