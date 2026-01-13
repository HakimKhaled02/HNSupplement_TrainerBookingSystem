@extends('layouts.dashboard')

@section('title', 'Manage Attendance - Trainer Dashboard')

@section('page-title', 'Manage Attendance')

@section('sidebar-menu')
    @include('components.trainer-sidebar')
@endsection

@section('content')
<div class="attendance-container">
    <div class="container" style="padding-top: 2rem; padding-bottom: 3rem;">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="card dashboard-card">
                    <div class="card-body dashboard-card-body">
                        <div class="mb-4">
                            <h2 class="dashboard-card-title mb-2">Booking #{{ $booking->id }}</h2>
                            <p class="dashboard-text mb-0">Customer: <strong>{{ $booking->user->name }}</strong></p>
                            <p class="dashboard-text mb-0">Period: {{ \Carbon\Carbon::parse($booking->start_date)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($booking->end_date)->format('M d, Y') }}</p>
                        </div>

                        <form action="{{ route('trainer.attendance.update', $booking->id) }}" method="POST">
                            @csrf
                            
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
                                
                                // Get existing attendance
                                $attendance = $booking->attendance ?? [];
                                $attendanceMap = [];
                                foreach ($attendance as $att) {
                                    $key = $att['day'] . '_' . $att['start_time'] . '_' . $att['date'];
                                    $attendanceMap[$key] = $att['status'];
                                }
                            @endphp

                            <div class="attendance-schedule">
                                @while($currentDate <= $endDate)
                                    @php
                                        $dateStr = $currentDate->format('Y-m-d');
                                        $dayOfWeek = strtolower($currentDate->format('l'));
                                        
                                        // Check if this day has time slots
                                        if (isset($slotsByDay[$dayOfWeek])) {
                                            $daySlots = $slotsByDay[$dayOfWeek];
                                    @endphp
                                    
                                    <div class="attendance-day-section">
                                        <h4 class="attendance-day-title">
                                            {{ $dayNames[$dayOfWeek] }}, {{ $currentDate->format('M d, Y') }}
                                        </h4>
                                        
                                        <div class="attendance-slots">
                                            @foreach($daySlots as $slot)
                                                @php
                                                    $slotKey = $dayOfWeek . '_' . $slot['start_time'] . '_' . $dateStr;
                                                    $currentStatus = $attendanceMap[$slotKey] ?? null;
                                                    $startTime = \Carbon\Carbon::createFromFormat('H:i', $slot['start_time'])->format('g:i A');
                                                    $endTime = \Carbon\Carbon::createFromFormat('H:i', $slot['end_time'])->format('g:i A');
                                                @endphp
                                                
                                                <div class="attendance-slot-item">
                                                    <div class="attendance-slot-time">
                                                        <strong>{{ $startTime }} - {{ $endTime }}</strong>
                                                    </div>
                                                    <div class="attendance-slot-actions">
                                                        <label class="attendance-radio-label">
                                                            <input type="radio" 
                                                                   name="attendance[{{ $slotKey }}]" 
                                                                   value="present"
                                                                   {{ $currentStatus === 'present' ? 'checked' : '' }}
                                                                   required>
                                                            <span class="attendance-status-badge present">Present</span>
                                                        </label>
                                                        <label class="attendance-radio-label">
                                                            <input type="radio" 
                                                                   name="attendance[{{ $slotKey }}]" 
                                                                   value="absent"
                                                                   {{ $currentStatus === 'absent' ? 'checked' : '' }}
                                                                   required>
                                                            <span class="attendance-status-badge absent">Absent</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    
                                    @php
                                        }
                                        $currentDate->addDay();
                                    @endphp
                                @endwhile
                            </div>

                            <div class="attendance-actions mt-4">
                                <a href="{{ route('trainer.bookings') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left me-2"></i>Back to Bookings
                                </a>
                                <button type="submit" class="btn dashboard-btn-primary">
                                    <i class="bi bi-check-circle me-2"></i>Save Attendance
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

