@extends('layouts.app')

@section('title', 'My Bookings')

@section('content')
<div class="customer-bookings-page-container">
    <div class="container" style="padding-top: 2rem; padding-bottom: 3rem;">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="customer-bookings-header mb-4">
                    <h2 class="customer-bookings-page-title">
                        <i class="bi bi-calendar-check me-2"></i>My Bookings
                    </h2>
                </div>

                @if($bookings->count() > 0)
                    <div class="card" style="background: #ffffff; border-radius: 12px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table bookings-table">
                                    <thead>
                                        <tr>
                                            <th>Booking ID</th>
                                            <th>Trainer</th>
                                            <th>Period</th>
                                            <th>Days</th>
                                            <th>Amount</th>
                                            <th>Progress</th>
                                            <th>Booked On</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($bookings as $booking)
                                        <tr>
                                            <td>#{{ $booking->id }}</td>
                                            <td>
                                                <div class="booking-customer-info">
                                                    @if($booking->trainer->profile_picture)
                                                        <img src="{{ asset('storage/' . $booking->trainer->profile_picture) }}" 
                                                             alt="{{ $booking->trainer->user->name }}" 
                                                             class="booking-customer-avatar">
                                                    @else
                                                        <div class="booking-customer-avatar-placeholder">
                                                            {{ strtoupper(substr($booking->trainer->user->name, 0, 1)) }}
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <strong>{{ $booking->trainer->user->name }}</strong><br>
                                                        <small class="text-muted">{{ ucfirst(str_replace('_', ' ', $booking->trainer->category ?? 'N/A')) }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($booking->start_date)->format('M d, Y') }}<br>
                                                <small class="text-muted">to {{ \Carbon\Carbon::parse($booking->end_date)->format('M d, Y') }}</small>
                                            </td>
                                            <td>
                                                @php
                                                    $dayNames = [
                                                        'monday' => 'Mon',
                                                        'tuesday' => 'Tue',
                                                        'wednesday' => 'Wed',
                                                        'thursday' => 'Thu',
                                                        'friday' => 'Fri',
                                                        'saturday' => 'Sat',
                                                        'sunday' => 'Sun'
                                                    ];
                                                @endphp
                                                @if($booking->selected_days)
                                                    @foreach(array_slice($booking->selected_days, 0, 3) as $day)
                                                        <span class="day-badge-small">{{ $dayNames[$day] ?? ucfirst($day) }}</span>
                                                    @endforeach
                                                    @if(count($booking->selected_days) > 3)
                                                        <span class="text-muted">+{{ count($booking->selected_days) - 3 }} more</span>
                                                    @endif
                                                @endif
                                            </td>
                                            <td><strong>RM {{ number_format($booking->total_amount, 2) }}</strong></td>
                                            <td>
                                                @php
                                                    $progress = $booking->progress ?? $booking->calculateProgress();
                                                @endphp
                                                <span class="progress-badge progress-{{ $progress }}">
                                                    {{ ucfirst($progress) }}
                                                </span>
                                            </td>
                                            <td>
                                                <small>{{ $booking->created_at->format('M d, Y') }}<br>{{ $booking->created_at->format('h:i A') }}</small>
                                            </td>
                                            <td style="width: 140px;">
                                                <div class="booking-actions-group">
                                                    @if($booking->payment_status === 'paid')
                                                        @php
                                                            $progress = $booking->progress ?? $booking->calculateProgress();
                                                            $isCompleted = $progress === 'completed';
                                                            $isUpcomingOrOngoing = in_array($progress, ['upcoming', 'ongoing']);
                                                            $hasReview = $booking->review ?? false;
                                                        @endphp
                                                        <button type="button" 
                                                                class="btn btn-sm btn-primary" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#attendanceModal{{ $booking->id }}"
                                                                title="View Attendance">
                                                            <i class="bi bi-check-circle"></i> Attendance
                                                        </button>
                                                        <button type="button" 
                                                                class="btn btn-sm btn-info {{ !$isUpcomingOrOngoing ? 'disabled' : '' }}" 
                                                                @if(!$isUpcomingOrOngoing) disabled @else data-bs-toggle="modal" data-bs-target="#reminderModal{{ $booking->id }}" @endif
                                                                title="{{ $isUpcomingOrOngoing ? 'Set Reminder' : 'Reminder only available for upcoming or ongoing bookings' }}">
                                                            <i class="bi bi-bell"></i> Reminder
                                                        </button>
                                                        <button type="button" 
                                                                class="btn btn-sm btn-warning {{ !$isCompleted || $hasReview ? 'disabled' : '' }}" 
                                                                @if(!$isCompleted || $hasReview) disabled @else data-bs-toggle="modal" data-bs-target="#feedbackModal{{ $booking->id }}" @endif
                                                                title="{{ $hasReview ? 'Feedback already submitted' : ($isCompleted ? 'Leave Feedback & Rating' : 'Complete all sessions to leave feedback') }}">
                                                            <i class="bi bi-star"></i> Feedback
                                                            @if($hasReview)
                                                                <i class="bi bi-check-circle ms-1"></i>
                                                            @endif
                                                        </button>
                                                        <button type="button" 
                                                                class="btn btn-sm btn-danger {{ !$isUpcomingOrOngoing ? 'disabled' : '' }}" 
                                                                @if(!$isUpcomingOrOngoing) disabled @else data-bs-toggle="modal" data-bs-target="#refundModal{{ $booking->id }}" @endif
                                                                title="{{ $isUpcomingOrOngoing ? 'Request Refund' : 'Refund only available for upcoming or ongoing bookings' }}">
                                                            <i class="bi bi-arrow-counterclockwise"></i> Refund
                                                        </button>
                                                    @endif
                                                </div>
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
                                                            <p class="mb-1"><strong>Trainer:</strong> {{ $booking->trainer->user->name }}</p>
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
                                                            <div class="attendance-record-list-modal">
                                                                @while($currentDate <= $endDate)
                                                                    @php
                                                                        $dateStr = $currentDate->format('Y-m-d');
                                                                        $dayOfWeek = strtolower($currentDate->format('l'));
                                                                        
                                                                        if (isset($slotsByDay[$dayOfWeek]) && isset($attendanceByDate[$dateStr][$dayOfWeek])) {
                                                                            $daySlots = $slotsByDay[$dayOfWeek];
                                                                            $dayAttendance = $attendanceByDate[$dateStr][$dayOfWeek];
                                                                    @endphp
                                                                    
                                                                    <div class="attendance-record-item-modal">
                                                                        <strong>{{ $dayNames[$dayOfWeek] }}, {{ $currentDate->format('M d, Y') }}</strong>
                                                                        <div class="attendance-record-slots-modal">
                                                                            @foreach($daySlots as $slot)
                                                                                @php
                                                                                    $startTime = \Carbon\Carbon::createFromFormat('H:i', $slot['start_time'])->format('g:i A');
                                                                                    $endTime = \Carbon\Carbon::createFromFormat('H:i', $slot['end_time'])->format('g:i A');
                                                                                    $slotAttendance = $dayAttendance[$slot['start_time']] ?? null;
                                                                                @endphp
                                                                                
                                                                                @if($slotAttendance)
                                                                                    <div class="attendance-record-slot-modal">
                                                                                        <span>{{ $startTime }} - {{ $endTime }}</span>
                                                                                        <span class="attendance-status-badge-view-modal {{ $slotAttendance['status'] }}">
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
                                                            <p class="text-center text-muted mb-0">No attendance records yet.</p>
                                                        @endif
                                                    </div>
                                                    <div class="modal-footer" style="padding: 0.75rem 1rem;">
                                                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif

                                        <!-- Reminder Modal -->
                                        <div class="modal fade" id="reminderModal{{ $booking->id }}" tabindex="-1" aria-labelledby="reminderModalLabel{{ $booking->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header" style="padding: 0.75rem 1rem;">
                                                        <h5 class="modal-title" id="reminderModalLabel{{ $booking->id }}" style="font-size: 1rem; margin: 0;">
                                                            Set Reminder - Booking #{{ $booking->id }}
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{ route('customer.booking.reminder', $booking->id) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-body" style="padding: 1rem;">
                                                            <div class="mb-2" style="font-size: 0.875rem;">
                                                                <p class="mb-1"><strong>Trainer:</strong> {{ $booking->trainer->user->name }}</p>
                                                                <p class="mb-2"><strong>Period:</strong> {{ \Carbon\Carbon::parse($booking->start_date)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($booking->end_date)->format('M d, Y') }}</p>
                                                            </div>
                                                            
                                                            @if($booking->reminders && $booking->reminders->count() > 0)
                                                            <div class="mb-3" style="background: #f0f8ff; padding: 0.75rem; border-radius: 6px; border-left: 3px solid #17a2b8;">
                                                                <strong style="font-size: 0.85rem; color: #17a2b8;">Existing Reminders:</strong>
                                                                <ul style="margin: 0.5rem 0 0 0; padding-left: 1.5rem; font-size: 0.8rem;">
                                                                    @foreach($booking->reminders->sortBy('reminder_date') as $reminder)
                                                                    <li style="margin-bottom: 0.5rem;">
                                                                        <strong>{{ \Carbon\Carbon::parse($reminder->reminder_date)->format('M d, Y \a\t g:i A') }}</strong>
                                                                        @if($reminder->sent)
                                                                            <span class="badge bg-success" style="font-size: 0.7rem; margin-left: 0.5rem;">Sent</span>
                                                                        @else
                                                                            <span class="badge bg-warning text-dark" style="font-size: 0.7rem; margin-left: 0.5rem;">Pending</span>
                                                                        @endif
                                                                        @if($reminder->note)
                                                                            <br><small style="color: #666;">{{ $reminder->note }}</small>
                                                                        @endif
                                                                    </li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                            @endif
                                                            
                                                            <div class="mb-3">
                                                                <label for="reminder_date{{ $booking->id }}" class="form-label">Reminder Date & Time</label>
                                                                <input type="datetime-local" 
                                                                       class="form-control" 
                                                                       id="reminder_date{{ $booking->id }}" 
                                                                       name="reminder_date" 
                                                                       required
                                                                       min="{{ now()->format('Y-m-d\TH:i') }}">
                                                                <small class="text-muted">Select when you want to be reminded about this booking.</small>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="reminder_note{{ $booking->id }}" class="form-label">Note (Optional)</label>
                                                                <textarea class="form-control" 
                                                                          id="reminder_note{{ $booking->id }}" 
                                                                          name="reminder_note" 
                                                                          rows="3" 
                                                                          placeholder="Add a note for this reminder..."></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer" style="padding: 0.75rem 1rem;">
                                                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-info btn-sm">Set Reminder</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Feedback & Rating Modal -->
                                        <div class="modal fade" id="feedbackModal{{ $booking->id }}" tabindex="-1" aria-labelledby="feedbackModalLabel{{ $booking->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header" style="padding: 0.75rem 1rem;">
                                                        <h5 class="modal-title" id="feedbackModalLabel{{ $booking->id }}" style="font-size: 1rem; margin: 0;">
                                                            Leave Feedback & Rating - Booking #{{ $booking->id }}
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{ route('customer.booking.feedback', $booking->id) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-body" style="padding: 1rem;">
                                                            <div class="mb-2" style="font-size: 0.875rem;">
                                                                <p class="mb-1"><strong>Trainer:</strong> {{ $booking->trainer->user->name }}</p>
                                                                <p class="mb-2"><strong>Period:</strong> {{ \Carbon\Carbon::parse($booking->start_date)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($booking->end_date)->format('M d, Y') }}</p>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Rating</label>
                                                                <div class="rating-input">
                                                                    <input type="radio" name="rating" value="5" id="rating5_{{ $booking->id }}" required>
                                                                    <label for="rating5_{{ $booking->id }}"><i class="bi bi-star-fill"></i></label>
                                                                    <input type="radio" name="rating" value="4" id="rating4_{{ $booking->id }}" required>
                                                                    <label for="rating4_{{ $booking->id }}"><i class="bi bi-star-fill"></i></label>
                                                                    <input type="radio" name="rating" value="3" id="rating3_{{ $booking->id }}" required>
                                                                    <label for="rating3_{{ $booking->id }}"><i class="bi bi-star-fill"></i></label>
                                                                    <input type="radio" name="rating" value="2" id="rating2_{{ $booking->id }}" required>
                                                                    <label for="rating2_{{ $booking->id }}"><i class="bi bi-star-fill"></i></label>
                                                                    <input type="radio" name="rating" value="1" id="rating1_{{ $booking->id }}" required>
                                                                    <label for="rating1_{{ $booking->id }}"><i class="bi bi-star-fill"></i></label>
                                                                </div>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="feedback{{ $booking->id }}" class="form-label">Feedback</label>
                                                                <textarea class="form-control" 
                                                                          id="feedback{{ $booking->id }}" 
                                                                          name="feedback" 
                                                                          rows="4" 
                                                                          placeholder="Share your experience with this trainer..."
                                                                          required></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer" style="padding: 0.75rem 1rem;">
                                                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-warning btn-sm">Submit Feedback</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Refund Request Modal -->
                                        <div class="modal fade" id="refundModal{{ $booking->id }}" tabindex="-1" aria-labelledby="refundModalLabel{{ $booking->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header" style="padding: 0.75rem 1rem;">
                                                        <h5 class="modal-title" id="refundModalLabel{{ $booking->id }}" style="font-size: 1rem; margin: 0;">
                                                            Request Refund - Booking #{{ $booking->id }}
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{ route('customer.booking.refund', $booking->id) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-body" style="padding: 1rem;">
                                                            <div class="mb-2" style="font-size: 0.875rem;">
                                                                <p class="mb-1"><strong>Trainer:</strong> {{ $booking->trainer->user->name }}</p>
                                                                <p class="mb-1"><strong>Amount:</strong> RM {{ number_format($booking->total_amount, 2) }}</p>
                                                                <p class="mb-2"><strong>Period:</strong> {{ \Carbon\Carbon::parse($booking->start_date)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($booking->end_date)->format('M d, Y') }}</p>
                                                            </div>
                                                            <div class="alert alert-warning" style="padding: 0.5rem; font-size: 0.85rem; margin-bottom: 1rem;">
                                                                <i class="bi bi-exclamation-triangle me-1"></i>Refund requests are subject to review and approval.
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="refund_reason{{ $booking->id }}" class="form-label">Reason for Refund <span class="text-danger">*</span></label>
                                                                <select class="form-select" id="refund_reason{{ $booking->id }}" name="refund_reason" required>
                                                                    <option value="">Select a reason...</option>
                                                                    <option value="trainer_unavailable">Trainer Unavailable</option>
                                                                    <option value="service_not_as_described">Service Not as Described</option>
                                                                    <option value="cancelled_by_customer">Cancelled by Customer</option>
                                                                    <option value="technical_issues">Technical Issues</option>
                                                                    <option value="other">Other</option>
                                                                </select>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="refund_details{{ $booking->id }}" class="form-label">Additional Details</label>
                                                                <textarea class="form-control" 
                                                                          id="refund_details{{ $booking->id }}" 
                                                                          name="refund_details" 
                                                                          rows="3" 
                                                                          placeholder="Please provide more details about your refund request..."
                                                                          required></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer" style="padding: 0.75rem 1rem;">
                                                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-danger btn-sm">Request Refund</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
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
