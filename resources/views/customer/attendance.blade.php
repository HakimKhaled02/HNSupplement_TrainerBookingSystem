@extends('layouts.app')

@section('title', 'Attendance - My Bookings')

@section('content')
<div class="customer-attendance-container">
    <div class="container" style="padding-top: 6rem; padding-bottom: 3rem;">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="customer-bookings-header mb-4">
                    <h2 class="customer-bookings-page-title">
                        <i class="bi bi-check-circle me-2"></i>Attendance
                    </h2>
                </div>

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
                    </div>

                    <div class="booking-card-body-new">
                        <div class="booking-details-section-new">
                            <h4 class="booking-section-title-new">Booking Period</h4>
                            <p class="mb-0">
                                {{ \Carbon\Carbon::parse($booking->start_date)->format('M d, Y') }} - 
                                {{ \Carbon\Carbon::parse($booking->end_date)->format('M d, Y') }}
                            </p>
                        </div>

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
                            
                            $attendance = $booking->attendance ?? [];
                            $attendanceByDate = [];
                            foreach ($attendance as $att) {
                                $attendanceByDate[$att['date']][$att['day']][$att['start_time']] = $att;
                            }
                            
                            // Get all dates in the booking period
                            $startDate = \Carbon\Carbon::parse($booking->start_date);
                            $endDate = \Carbon\Carbon::parse($booking->end_date);
                            $currentDate = $startDate->copy();
                            
                            // Group time slots by day
                            $slotsByDay = [];
                            if ($booking->time_slots) {
                                foreach ($booking->time_slots as $slot) {
                                    $slotsByDay[$slot['day']][] = $slot;
                                }
                            }
                        @endphp

                        @if(count($attendance) > 0)
                            <div class="booking-schedule-section-new">
                                <h4 class="booking-section-title-new">Attendance Record</h4>
                                <div class="attendance-record-list">
                                    @while($currentDate <= $endDate)
                                        @php
                                            $dateStr = $currentDate->format('Y-m-d');
                                            $dayOfWeek = strtolower($currentDate->format('l'));
                                            
                                            if (isset($slotsByDay[$dayOfWeek]) && isset($attendanceByDate[$dateStr][$dayOfWeek])) {
                                                $daySlots = $slotsByDay[$dayOfWeek];
                                                $dayAttendance = $attendanceByDate[$dateStr][$dayOfWeek];
                                        @endphp
                                        
                                        <div class="attendance-record-item">
                                            <div class="attendance-record-header">
                                                <strong>{{ $dayNames[$dayOfWeek] }}, {{ $currentDate->format('M d, Y') }}</strong>
                                            </div>
                                            <div class="attendance-record-slots">
                                                @foreach($daySlots as $slot)
                                                    @php
                                                        $startTime = \Carbon\Carbon::createFromFormat('H:i', $slot['start_time'])->format('g:i A');
                                                        $endTime = \Carbon\Carbon::createFromFormat('H:i', $slot['end_time'])->format('g:i A');
                                                        $slotAttendance = $dayAttendance[$slot['start_time']] ?? null;
                                                    @endphp
                                                    
                                                    @if($slotAttendance)
                                                        <div class="attendance-record-slot">
                                                            <span class="attendance-slot-time">{{ $startTime }} - {{ $endTime }}</span>
                                                            <span class="attendance-status-badge-view {{ $slotAttendance['status'] }}">
                                                                {{ ucfirst($slotAttendance['status']) }}
                                                            </span>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                        
                                        @php
                                            }
                                            $currentDate->addDay();
                                        @endphp
                                    @endwhile
                                </div>
                            </div>
                        @else
                            <div class="booking-schedule-section-new">
                                <p class="text-center text-muted mb-0">No attendance records yet.</p>
                            </div>
                        @endif
                    </div>

                    <div class="booking-card-footer-new">
                        <div class="booking-footer-info">
                            <a href="{{ route('customer.bookings') }}" class="btn booking-action-btn booking-attendance-btn">
                                <i class="bi bi-arrow-left me-1"></i>Back to Bookings
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

