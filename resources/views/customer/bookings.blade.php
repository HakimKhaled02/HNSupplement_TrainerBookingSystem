@extends('layouts.app')

@section('title', 'My Bookings')

@section('content')
<div class="customer-bookings-page-container">
    <div class="container" style="padding-top: 2rem; padding-bottom: 3rem;">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="customer-bookings-header mb-4">
                    <h2 class="customer-bookings-page-title">
                        <i class="bi bi-calendar-check me-2"></i>My Bookings
                    </h2>
                </div>

                @if($bookings->count() > 0)
                    <div class="customer-bookings-list">
                        @foreach($bookings as $booking)
                        <div class="customer-booking-card-new">
                            <div class="booking-card-header-new">
                                <div class="booking-trainer-section">
                                    <div class="booking-trainer-image-wrapper">
                                        @if($booking->trainer->profile_picture)
                                            <img src="{{ asset('storage/' . $booking->trainer->profile_picture) }}" 
                                                 alt="{{ $booking->trainer->user->name }}" 
                                                 class="booking-trainer-image-new">
                                        @else
                                            <div class="booking-trainer-placeholder-new">
                                                {{ strtoupper(substr($booking->trainer->user->name, 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="booking-trainer-info-new">
                                        <h3 class="booking-trainer-name-new">{{ $booking->trainer->user->name }}</h3>
                                        <p class="booking-id-new">Booking #{{ $booking->id }}</p>
                                    </div>
                                </div>
                                <span class="booking-status-badge-new booking-status-{{ $booking->payment_status }}-new">
                                    {{ ucfirst($booking->payment_status) }}
                                </span>
                            </div>

                            <div class="booking-card-body-new">
                                <div class="booking-details-section-new">
                                    <h4 class="booking-section-title-new">Booking Details</h4>
                                    <div class="booking-info-grid-new">
                                        <div class="booking-info-item-new">
                                            <span class="booking-info-label-new">Period:</span>
                                            <span class="booking-info-value-new">
                                                {{ \Carbon\Carbon::parse($booking->start_date)->format('M d, Y') }} - 
                                                {{ \Carbon\Carbon::parse($booking->end_date)->format('M d, Y') }}
                                            </span>
                                        </div>
                                        <div class="booking-info-item-new">
                                            <span class="booking-info-label-new">Category:</span>
                                            <span class="booking-info-value-new">
                                                {{ ucfirst(str_replace('_', ' ', $booking->trainer->category ?? 'N/A')) }}
                                            </span>
                                        </div>
                                        <div class="booking-info-item-new">
                                            <span class="booking-info-label-new">Location:</span>
                                            <span class="booking-info-value-new">
                                                {{ $booking->trainer->area ?? 'N/A' }}, {{ ucfirst(str_replace('_', ' ', $booking->trainer->state ?? 'N/A')) }}
                                            </span>
                                        </div>
                                        <div class="booking-info-item-new">
                                            <span class="booking-info-label-new">Total Amount:</span>
                                            <span class="booking-info-value-new booking-amount-new">
                                                RM {{ number_format($booking->total_amount, 2) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                @if($booking->selected_days && count($booking->selected_days) > 0)
                                <div class="booking-schedule-section-new">
                                    <h4 class="booking-section-title-new">Schedule</h4>
                                    <div class="booking-schedule-content-new">
                                        <div class="booking-days-display-new">
                                            <span class="booking-days-label-new">Days:</span>
                                            <div class="booking-days-badges-new">
                                                @php
                                                    $dayNames = [
                                                        'monday' => 'Monday',
                                                        'tuesday' => 'Tuesday',
                                                        'wednesday' => 'Wednesday',
                                                        'thursday' => 'Thursday',
                                                        'friday' => 'Friday',
                                                        'saturday' => 'Saturday',
                                                        'sunday' => 'Sunday'
                                                    ];
                                                @endphp
                                                @foreach($booking->selected_days as $day)
                                                    <span class="booking-day-badge-new">{{ $dayNames[$day] ?? ucfirst($day) }}</span>
                                                @endforeach
                                            </div>
                                        </div>
                                        @if($booking->time_slots && count($booking->time_slots) > 0)
                                        <div class="booking-times-display-new">
                                            <span class="booking-times-label-new">Time Slots:</span>
                                            <div class="booking-times-list-new">
                                                @foreach($booking->time_slots as $slot)
                                                    @php
                                                        $dayName = $dayNames[$slot['day']] ?? ucfirst($slot['day']);
                                                        $startTime = \Carbon\Carbon::createFromFormat('H:i', $slot['start_time'])->format('g:i A');
                                                        $endTime = \Carbon\Carbon::createFromFormat('H:i', $slot['end_time'])->format('g:i A');
                                                    @endphp
                                                    <div class="booking-time-item-new">
                                                        <strong>{{ $dayName }}:</strong> {{ $startTime }} - {{ $endTime }}
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @endif
                            </div>

                            <div class="booking-card-footer-new">
                                <div class="booking-footer-info">
                                    <small class="booking-date-new">
                                        <i class="bi bi-clock me-1"></i>Booked on {{ $booking->created_at->format('M d, Y h:i A') }}
                                    </small>
                                    @if($booking->payment_status === 'pending' && $booking->payment_expires_at)
                                        <small class="booking-expiry-new">
                                            <i class="bi bi-exclamation-triangle me-1"></i>
                                            Payment expires: {{ $booking->payment_expires_at->format('M d, Y h:i A') }}
                                        </small>
                                    @endif
                                </div>
                                @if($booking->payment_status === 'paid')
                                <div class="booking-action-buttons">
                                    <button type="button" class="btn booking-action-btn booking-refund-btn" data-booking-id="{{ $booking->id }}">
                                        <i class="bi bi-arrow-counterclockwise me-1"></i>Refund
                                    </button>
                                    <button type="button" class="btn booking-action-btn booking-reminder-btn" data-booking-id="{{ $booking->id }}">
                                        <i class="bi bi-bell me-1"></i>Set Reminder
                                    </button>
                                    <button type="button" class="btn booking-action-btn booking-attendance-btn" data-booking-id="{{ $booking->id }}">
                                        <i class="bi bi-check-circle me-1"></i>Attendance
                                    </button>
                                    <button type="button" class="btn booking-action-btn booking-feedback-rating-btn" data-booking-id="{{ $booking->id }}">
                                        <i class="bi bi-star me-1"></i>Leave Feedback & Rating
                                    </button>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="mt-4">
                        {{ $bookings->links() }}
                    </div>
                @else
                    <div class="customer-no-bookings-new">
                        <div class="text-center py-5">
                            <i class="bi bi-calendar-x" style="font-size: 4rem; color: #999999;"></i>
                            <h3 class="mt-3" style="color: #333333;">No Bookings Yet</h3>
                            <p style="color: #666666;">You haven't made any bookings yet. Start by browsing our trainers!</p>
                            <a href="{{ route('trainers') }}" class="btn btn-primary mt-3" style="background: #4a9eff; border: none;">
                                <i class="bi bi-search me-2"></i>Browse Trainers
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
