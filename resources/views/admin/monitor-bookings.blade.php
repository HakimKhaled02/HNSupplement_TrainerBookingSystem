@extends('layouts.dashboard')

@section('title', 'Monitor Bookings - Admin Dashboard')

@section('page-title', 'Monitor Bookings')

@section('sidebar-menu')
    @include('components.admin-sidebar')
@endsection

@section('content')
<div class="admin-bookings-container">
    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-sm-6">
            <div class="card dashboard-card stats-card">
                <div class="card-body">
                    <div class="stats-icon" style="background: rgba(40, 167, 69, 0.1); color: #28a745;">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <div class="stats-content">
                        <div class="stats-value">{{ $totalBookings }}</div>
                        <div class="stats-label">Total Bookings</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card dashboard-card stats-card">
                <div class="card-body">
                    <div class="stats-icon" style="background: rgba(0, 123, 255, 0.1); color: #007bff;">
                        <i class="bi bi-credit-card"></i>
                    </div>
                    <div class="stats-content">
                        <div class="stats-value">{{ $paidBookings }}</div>
                        <div class="stats-label">Paid</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card dashboard-card stats-card">
                <div class="card-body">
                    <div class="stats-icon" style="background: rgba(40, 167, 69, 0.1); color: #28a745;">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div class="stats-content">
                        <div class="stats-value">{{ $completedBookings }}</div>
                        <div class="stats-label">Completed</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bookings Table -->
    <div class="card dashboard-card">
        <div class="card-header dashboard-card-header">
            <h2 class="dashboard-card-title">
                <i class="bi bi-list-ul me-2"></i>All Bookings
            </h2>
        </div>
        <div class="card-body dashboard-card-body p-0">
            @if($bookings->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0 admin-bookings-table">
                        <thead>
                            <tr>
                                <th style="width: 80px;">ID</th>
                                <th style="width: 200px;">Trainer</th>
                                <th style="width: 200px;">Customer</th>
                                <th style="width: 150px;">Period</th>
                                <th style="width: 120px;">Amount</th>
                                <th style="width: 120px;">Progress</th>
                                <th style="width: 150px;">Attendance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bookings as $booking)
                            <tr>
                                <td>
                                    <span class="booking-id-badge">#{{ $booking->id }}</span>
                                </td>
                                <td>
                                    <div class="booking-user-info">
                                        @if($booking->trainer && $booking->trainer->user)
                                            @if($booking->trainer->profile_picture)
                                                <img src="{{ asset('storage/' . $booking->trainer->profile_picture) }}" 
                                                     alt="{{ $booking->trainer->user->name }}" 
                                                     class="booking-avatar-small">
                                            @else
                                                <div class="booking-avatar-small-placeholder">
                                                    {{ strtoupper(substr($booking->trainer->user->name, 0, 1)) }}
                                                </div>
                                            @endif
                                            <div class="booking-user-details">
                                                <strong>{{ $booking->trainer->user->name }}</strong>
                                                <small class="d-block" style="color: #666666;">{{ $booking->trainer->user->email }}</small>
                                            </div>
                                        @else
                                            <span style="color: #999999;">N/A</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="booking-user-info">
                                        @if($booking->user)
                                            @if($booking->user->customer && $booking->user->customer->profile_picture)
                                                <img src="{{ asset('storage/' . $booking->user->customer->profile_picture) }}" 
                                                     alt="{{ $booking->user->name }}" 
                                                     class="booking-avatar-small">
                                            @else
                                                <div class="booking-avatar-small-placeholder">
                                                    {{ strtoupper(substr($booking->user->name, 0, 1)) }}
                                                </div>
                                            @endif
                                            <div class="booking-user-details">
                                                <strong>{{ $booking->user->name }}</strong>
                                                <small class="d-block" style="color: #666666;">{{ $booking->user->email }}</small>
                                            </div>
                                        @else
                                            <span style="color: #999999;">N/A</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="booking-period">
                                        <div>{{ \Carbon\Carbon::parse($booking->start_date)->format('M d, Y') }}</div>
                                        <small style="color: #666666;">{{ \Carbon\Carbon::parse($booking->end_date)->format('M d, Y') }}</small>
                                    </div>
                                </td>
                                <td>
                                    <strong style="color: #28a745; font-size: 0.875rem;">RM {{ number_format($booking->total_amount, 2) }}</strong>
                                </td>
                                <td>
                                    @if($booking->progress === 'completed')
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle me-1"></i>Completed
                                        </span>
                                    @elseif($booking->progress === 'ongoing')
                                        <span class="badge bg-primary">
                                            <i class="bi bi-arrow-repeat me-1"></i>Ongoing
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            <i class="bi bi-clock me-1"></i>Upcoming
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($booking->payment_status === 'paid')
                                        <button type="button" 
                                                class="btn btn-sm btn-primary" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#attendanceModal{{ $booking->id }}"
                                                title="View Attendance">
                                            <i class="bi bi-check-circle me-1"></i>View
                                        </button>
                                    @else
                                        <span class="text-muted" style="font-size: 0.875rem;">N/A</span>
                                    @endif
                                </td>
                            </tr>

                            <!-- Attendance Modal -->
                            @if($booking->payment_status === 'paid')
                            <div class="modal fade" id="attendanceModal{{ $booking->id }}" tabindex="-1" aria-labelledby="attendanceModalLabel{{ $booking->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header" style="padding: 0.75rem 1rem;">
                                            <h5 class="modal-title" id="attendanceModalLabel{{ $booking->id }}" style="font-size: 1rem; margin: 0;">
                                                Attendance - Booking #{{ $booking->id }}
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body" style="padding: 1rem;">
                                            <div class="mb-2" style="font-size: 0.875rem;">
                                                <p class="mb-1"><strong>Trainer:</strong> {{ $booking->trainer && $booking->trainer->user ? $booking->trainer->user->name : 'N/A' }}</p>
                                                <p class="mb-1"><strong>Customer:</strong> {{ $booking->user ? $booking->user->name : 'N/A' }}</p>
                                                <p class="mb-0"><strong>Period:</strong> {{ \Carbon\Carbon::parse($booking->start_date)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($booking->end_date)->format('M d, Y') }}</p>
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
                                                
                                                $startDate = \Carbon\Carbon::parse($booking->start_date);
                                                $endDate = \Carbon\Carbon::parse($booking->end_date);
                                                $currentDate = $startDate->copy();
                                                
                                                $slotsByDay = [];
                                                if ($booking->time_slots) {
                                                    foreach ($booking->time_slots as $slot) {
                                                        $slotsByDay[$slot['day']][] = $slot;
                                                    }
                                                }
                                            @endphp

                                            @if(count($attendance) > 0)
                                                <div class="attendance-record-list-modal" style="max-height: 400px; overflow-y: auto; margin-top: 1rem;">
                                                    @while($currentDate <= $endDate)
                                                        @php
                                                            $dateStr = $currentDate->format('Y-m-d');
                                                            $dayOfWeek = strtolower($currentDate->format('l'));
                                                            
                                                            if (isset($slotsByDay[$dayOfWeek]) && isset($attendanceByDate[$dateStr][$dayOfWeek])) {
                                                                $daySlots = $slotsByDay[$dayOfWeek];
                                                                $dayAttendance = $attendanceByDate[$dateStr][$dayOfWeek];
                                                        @endphp
                                                        
                                                        <div class="attendance-record-item-modal" style="background: #f8f9fa; padding: 0.75rem; border-radius: 6px; margin-bottom: 0.75rem;">
                                                            <strong style="color: #333; font-size: 0.9rem;">{{ $dayNames[$dayOfWeek] }}, {{ $currentDate->format('M d, Y') }}</strong>
                                                            <div class="attendance-record-slots-modal" style="margin-top: 0.5rem;">
                                                                @foreach($daySlots as $slot)
                                                                    @php
                                                                        $startTime = \Carbon\Carbon::createFromFormat('H:i', $slot['start_time'])->format('g:i A');
                                                                        $endTime = \Carbon\Carbon::createFromFormat('H:i', $slot['end_time'])->format('g:i A');
                                                                        $slotAttendance = $dayAttendance[$slot['start_time']] ?? null;
                                                                    @endphp
                                                                    
                                                                    @if($slotAttendance)
                                                                        <div class="attendance-record-slot-modal" style="display: flex; justify-content: space-between; align-items: center; padding: 0.5rem; background: #fff; border-radius: 4px; margin-bottom: 0.5rem;">
                                                                            <span style="color: #333; font-size: 0.85rem;">{{ $startTime }} - {{ $endTime }}</span>
                                                                            <span class="attendance-status-badge-view-modal {{ $slotAttendance['status'] }}" style="padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.75rem; font-weight: 600; 
                                                                                @if($slotAttendance['status'] === 'present') background: #28a745; color: #fff;
                                                                                @elseif($slotAttendance['status'] === 'absent') background: #dc3545; color: #fff;
                                                                                @elseif($slotAttendance['status'] === 'late') background: #ffc107; color: #000;
                                                                                @else background: #6c757d; color: #fff; @endif">
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
                                            @else
                                                <p class="text-center text-muted mb-0" style="padding: 2rem 0;">No attendance records yet.</p>
                                            @endif
                                        </div>
                                        <div class="modal-footer" style="padding: 0.75rem 1rem;">
                                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="p-3 border-top">
                    {{ $bookings->links() }}
                </div>
            @else
                <div class="text-center p-5">
                    <i class="bi bi-calendar-x" style="font-size: 3rem; color: #6c757d; margin-bottom: 1rem;"></i>
                    <p class="text-muted">No bookings found.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.admin-bookings-container {
    padding: 0;
}

.stats-card {
    border: none;
    transition: transform 0.2s;
}

.stats-card:hover {
    transform: translateY(-2px);
}

.stats-card .card-body {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.25rem;
}

.stats-icon {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.stats-content {
    flex: 1;
}

.stats-value {
    font-size: 1.75rem;
    font-weight: bold;
    color: var(--text-primary, #fff);
    line-height: 1.2;
}

.stats-label {
    font-size: 0.875rem;
    color: var(--text-secondary, #adb5bd);
    margin-top: 0.25rem;
}

.admin-bookings-table {
    margin: 0;
}

.admin-bookings-table thead th {
    background: var(--bg-secondary, #1a1a1a);
    color: var(--text-primary, #fff);
    font-weight: 600;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 1rem 0.75rem;
    border-bottom: 2px solid var(--accent-green, #28a745);
}

.admin-bookings-table tbody td {
    padding: 0.75rem;
    vertical-align: middle;
    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    font-size: 0.875rem;
    color: #333333;
    background: #ffffff;
}

.admin-bookings-table tbody tr {
    background: #ffffff;
}

.admin-bookings-table tbody tr:hover {
    background: rgba(40, 167, 69, 0.08);
}

.booking-id-badge {
    display: inline-block;
    padding: 0.25rem 0.5rem;
    background: rgba(40, 167, 69, 0.1);
    color: var(--accent-green, #28a745);
    border-radius: 4px;
    font-weight: 600;
    font-size: 0.75rem;
}

.booking-user-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.booking-avatar-small {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid rgba(40, 167, 69, 0.3);
}

.booking-avatar-small-placeholder {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: var(--accent-green, #28a745);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 0.875rem;
    border: 2px solid rgba(40, 167, 69, 0.3);
}

.booking-user-details {
    flex: 1;
    min-width: 0;
}

.booking-user-details strong {
    display: block;
    color: #333333;
    font-size: 0.875rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    font-weight: 600;
}

.booking-user-details small {
    font-size: 0.75rem;
    color: #666666;
}

.booking-period {
    font-size: 0.875rem;
    color: #333333;
}

.booking-period small {
    color: #666666;
}

.badge {
    padding: 0.35rem 0.65rem;
    font-size: 0.75rem;
    font-weight: 600;
}

.pagination {
    margin: 0;
}

.pagination .page-link {
    background: var(--bg-secondary, #1a1a1a);
    border-color: rgba(255, 255, 255, 0.1);
    color: var(--text-primary, #fff);
}

.pagination .page-link:hover {
    background: var(--accent-green, #28a745);
    border-color: var(--accent-green, #28a745);
    color: #fff;
}

.pagination .page-item.active .page-link {
    background: var(--accent-green, #28a745);
    border-color: var(--accent-green, #28a745);
}
</style>
@endsection

