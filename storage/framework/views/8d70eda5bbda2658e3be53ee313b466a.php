

<?php $__env->startSection('title', 'My Bookings'); ?>

<?php $__env->startSection('content'); ?>
<div class="customer-bookings-page-container">
    <div class="container" style="padding-top: 2rem; padding-bottom: 3rem;">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="customer-bookings-header mb-4">
                    <h2 class="customer-bookings-page-title">
                        <i class="bi bi-calendar-check me-2"></i>My Bookings
                    </h2>
                </div>

                <?php if($bookings->count() > 0): ?>
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
                                        <?php $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>#<?php echo e($booking->id); ?></td>
                                            <td>
                                                <div class="booking-customer-info">
                                                    <?php if($booking->trainer->profile_picture): ?>
                                                        <img src="<?php echo e(asset('storage/' . $booking->trainer->profile_picture)); ?>" 
                                                             alt="<?php echo e($booking->trainer->user->name); ?>" 
                                                             class="booking-customer-avatar">
                                                    <?php else: ?>
                                                        <div class="booking-customer-avatar-placeholder">
                                                            <?php echo e(strtoupper(substr($booking->trainer->user->name, 0, 1))); ?>

                                                        </div>
                                                    <?php endif; ?>
                                                    <div>
                                                        <strong><?php echo e($booking->trainer->user->name); ?></strong><br>
                                                        <small class="text-muted"><?php echo e(ucfirst(str_replace('_', ' ', $booking->trainer->category ?? 'N/A'))); ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <?php echo e(\Carbon\Carbon::parse($booking->start_date)->format('M d, Y')); ?><br>
                                                <small class="text-muted">to <?php echo e(\Carbon\Carbon::parse($booking->end_date)->format('M d, Y')); ?></small>
                                            </td>
                                            <td>
                                                <?php
                                                    $dayNames = [
                                                        'monday' => 'Mon',
                                                        'tuesday' => 'Tue',
                                                        'wednesday' => 'Wed',
                                                        'thursday' => 'Thu',
                                                        'friday' => 'Fri',
                                                        'saturday' => 'Sat',
                                                        'sunday' => 'Sun'
                                                    ];
                                                ?>
                                                <?php if($booking->selected_days): ?>
                                                    <?php $__currentLoopData = array_slice($booking->selected_days, 0, 3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <span class="day-badge-small"><?php echo e($dayNames[$day] ?? ucfirst($day)); ?></span>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if(count($booking->selected_days) > 3): ?>
                                                        <span class="text-muted">+<?php echo e(count($booking->selected_days) - 3); ?> more</span>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </td>
                                            <td><strong>RM <?php echo e(number_format($booking->total_amount, 2)); ?></strong></td>
                                            <td>
                                                <?php
                                                    $progress = $booking->progress ?? $booking->calculateProgress();
                                                ?>
                                                <span class="progress-badge progress-<?php echo e($progress); ?>">
                                                    <?php echo e(ucfirst($progress)); ?>

                                                </span>
                                            </td>
                                            <td>
                                                <small><?php echo e($booking->created_at->format('M d, Y')); ?><br><?php echo e($booking->created_at->format('h:i A')); ?></small>
                                            </td>
                                            <td style="width: 140px;">
                                                <div class="booking-actions-group">
                                                    <?php if($booking->payment_status === 'paid'): ?>
                                                        <?php
                                                            $progress = $booking->progress ?? $booking->calculateProgress();
                                                            $isCompleted = $progress === 'completed';
                                                        ?>
                                                        <button type="button" 
                                                                class="btn btn-sm btn-primary" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#attendanceModal<?php echo e($booking->id); ?>"
                                                                title="View Attendance">
                                                            <i class="bi bi-check-circle"></i> Attendance
                                                        </button>
                                                        <button type="button" 
                                                                class="btn btn-sm btn-info" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#reminderModal<?php echo e($booking->id); ?>"
                                                                title="Set Reminder">
                                                            <i class="bi bi-bell"></i> Reminder
                                                        </button>
                                                        <button type="button" 
                                                                class="btn btn-sm btn-warning <?php echo e(!$isCompleted ? 'disabled' : ''); ?>" 
                                                                <?php if(!$isCompleted): ?> disabled <?php else: ?> data-bs-toggle="modal" data-bs-target="#feedbackModal<?php echo e($booking->id); ?>" <?php endif; ?>
                                                                title="<?php echo e($isCompleted ? 'Leave Feedback & Rating' : 'Complete all sessions to leave feedback'); ?>">
                                                            <i class="bi bi-star"></i> Feedback
                                                        </button>
                                                        <button type="button" 
                                                                class="btn btn-sm btn-danger" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#refundModal<?php echo e($booking->id); ?>"
                                                                title="Request Refund">
                                                            <i class="bi bi-arrow-counterclockwise"></i> Refund
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Attendance Modal -->
                                        <?php if($booking->payment_status === 'paid'): ?>
                                        <div class="modal fade" id="attendanceModal<?php echo e($booking->id); ?>" tabindex="-1" aria-labelledby="attendanceModalLabel<?php echo e($booking->id); ?>" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header" style="padding: 0.75rem 1rem;">
                                                        <h5 class="modal-title" id="attendanceModalLabel<?php echo e($booking->id); ?>" style="font-size: 1rem; margin: 0;">
                                                            Attendance - Booking #<?php echo e($booking->id); ?>

                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body" style="padding: 1rem;">
                                                        <div class="mb-2" style="font-size: 0.875rem;">
                                                            <p class="mb-1"><strong>Trainer:</strong> <?php echo e($booking->trainer->user->name); ?></p>
                                                            <p class="mb-0"><strong>Period:</strong> <?php echo e(\Carbon\Carbon::parse($booking->start_date)->format('M d, Y')); ?> - <?php echo e(\Carbon\Carbon::parse($booking->end_date)->format('M d, Y')); ?></p>
                                                        </div>

                                                        <?php
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
                                                        ?>

                                                        <?php if(count($attendance) > 0): ?>
                                                            <div class="attendance-record-list-modal">
                                                                <?php while($currentDate <= $endDate): ?>
                                                                    <?php
                                                                        $dateStr = $currentDate->format('Y-m-d');
                                                                        $dayOfWeek = strtolower($currentDate->format('l'));
                                                                        
                                                                        if (isset($slotsByDay[$dayOfWeek]) && isset($attendanceByDate[$dateStr][$dayOfWeek])) {
                                                                            $daySlots = $slotsByDay[$dayOfWeek];
                                                                            $dayAttendance = $attendanceByDate[$dateStr][$dayOfWeek];
                                                                    ?>
                                                                    
                                                                    <div class="attendance-record-item-modal">
                                                                        <strong><?php echo e($dayNames[$dayOfWeek]); ?>, <?php echo e($currentDate->format('M d, Y')); ?></strong>
                                                                        <div class="attendance-record-slots-modal">
                                                                            <?php $__currentLoopData = $daySlots; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slot): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                <?php
                                                                                    $startTime = \Carbon\Carbon::createFromFormat('H:i', $slot['start_time'])->format('g:i A');
                                                                                    $endTime = \Carbon\Carbon::createFromFormat('H:i', $slot['end_time'])->format('g:i A');
                                                                                    $slotAttendance = $dayAttendance[$slot['start_time']] ?? null;
                                                                                ?>
                                                                                
                                                                                <?php if($slotAttendance): ?>
                                                                                    <div class="attendance-record-slot-modal">
                                                                                        <span><?php echo e($startTime); ?> - <?php echo e($endTime); ?></span>
                                                                                        <span class="attendance-status-badge-view-modal <?php echo e($slotAttendance['status']); ?>">
                                                                                            <?php echo e(ucfirst($slotAttendance['status'])); ?>

                                                                                        </span>
                                                                                    </div>
                                                                                <?php endif; ?>
                                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <?php
                                                                        }
                                                                        $currentDate->addDay();
                                                                    ?>
                                                                <?php endwhile; ?>
                                                            </div>
                                                        <?php else: ?>
                                                            <p class="text-center text-muted mb-0">No attendance records yet.</p>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="modal-footer" style="padding: 0.75rem 1rem;">
                                                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endif; ?>

                                        <!-- Reminder Modal -->
                                        <div class="modal fade" id="reminderModal<?php echo e($booking->id); ?>" tabindex="-1" aria-labelledby="reminderModalLabel<?php echo e($booking->id); ?>" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header" style="padding: 0.75rem 1rem;">
                                                        <h5 class="modal-title" id="reminderModalLabel<?php echo e($booking->id); ?>" style="font-size: 1rem; margin: 0;">
                                                            Set Reminder - Booking #<?php echo e($booking->id); ?>

                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="<?php echo e(route('customer.booking.reminder', $booking->id)); ?>" method="POST">
                                                        <?php echo csrf_field(); ?>
                                                        <div class="modal-body" style="padding: 1rem;">
                                                            <div class="mb-2" style="font-size: 0.875rem;">
                                                                <p class="mb-1"><strong>Trainer:</strong> <?php echo e($booking->trainer->user->name); ?></p>
                                                                <p class="mb-2"><strong>Period:</strong> <?php echo e(\Carbon\Carbon::parse($booking->start_date)->format('M d, Y')); ?> - <?php echo e(\Carbon\Carbon::parse($booking->end_date)->format('M d, Y')); ?></p>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="reminder_date<?php echo e($booking->id); ?>" class="form-label">Reminder Date & Time</label>
                                                                <input type="datetime-local" 
                                                                       class="form-control" 
                                                                       id="reminder_date<?php echo e($booking->id); ?>" 
                                                                       name="reminder_date" 
                                                                       required
                                                                       min="<?php echo e(now()->format('Y-m-d\TH:i')); ?>">
                                                                <small class="text-muted">Select when you want to be reminded about this booking.</small>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="reminder_note<?php echo e($booking->id); ?>" class="form-label">Note (Optional)</label>
                                                                <textarea class="form-control" 
                                                                          id="reminder_note<?php echo e($booking->id); ?>" 
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
                                        <div class="modal fade" id="feedbackModal<?php echo e($booking->id); ?>" tabindex="-1" aria-labelledby="feedbackModalLabel<?php echo e($booking->id); ?>" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header" style="padding: 0.75rem 1rem;">
                                                        <h5 class="modal-title" id="feedbackModalLabel<?php echo e($booking->id); ?>" style="font-size: 1rem; margin: 0;">
                                                            Leave Feedback & Rating - Booking #<?php echo e($booking->id); ?>

                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="<?php echo e(route('customer.booking.feedback', $booking->id)); ?>" method="POST">
                                                        <?php echo csrf_field(); ?>
                                                        <div class="modal-body" style="padding: 1rem;">
                                                            <div class="mb-2" style="font-size: 0.875rem;">
                                                                <p class="mb-1"><strong>Trainer:</strong> <?php echo e($booking->trainer->user->name); ?></p>
                                                                <p class="mb-2"><strong>Period:</strong> <?php echo e(\Carbon\Carbon::parse($booking->start_date)->format('M d, Y')); ?> - <?php echo e(\Carbon\Carbon::parse($booking->end_date)->format('M d, Y')); ?></p>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Rating</label>
                                                                <div class="rating-input">
                                                                    <input type="radio" name="rating" value="5" id="rating5_<?php echo e($booking->id); ?>" required>
                                                                    <label for="rating5_<?php echo e($booking->id); ?>"><i class="bi bi-star-fill"></i></label>
                                                                    <input type="radio" name="rating" value="4" id="rating4_<?php echo e($booking->id); ?>" required>
                                                                    <label for="rating4_<?php echo e($booking->id); ?>"><i class="bi bi-star-fill"></i></label>
                                                                    <input type="radio" name="rating" value="3" id="rating3_<?php echo e($booking->id); ?>" required>
                                                                    <label for="rating3_<?php echo e($booking->id); ?>"><i class="bi bi-star-fill"></i></label>
                                                                    <input type="radio" name="rating" value="2" id="rating2_<?php echo e($booking->id); ?>" required>
                                                                    <label for="rating2_<?php echo e($booking->id); ?>"><i class="bi bi-star-fill"></i></label>
                                                                    <input type="radio" name="rating" value="1" id="rating1_<?php echo e($booking->id); ?>" required>
                                                                    <label for="rating1_<?php echo e($booking->id); ?>"><i class="bi bi-star-fill"></i></label>
                                                                </div>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="feedback<?php echo e($booking->id); ?>" class="form-label">Feedback</label>
                                                                <textarea class="form-control" 
                                                                          id="feedback<?php echo e($booking->id); ?>" 
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
                                        <div class="modal fade" id="refundModal<?php echo e($booking->id); ?>" tabindex="-1" aria-labelledby="refundModalLabel<?php echo e($booking->id); ?>" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header" style="padding: 0.75rem 1rem;">
                                                        <h5 class="modal-title" id="refundModalLabel<?php echo e($booking->id); ?>" style="font-size: 1rem; margin: 0;">
                                                            Request Refund - Booking #<?php echo e($booking->id); ?>

                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="<?php echo e(route('customer.booking.refund', $booking->id)); ?>" method="POST">
                                                        <?php echo csrf_field(); ?>
                                                        <div class="modal-body" style="padding: 1rem;">
                                                            <div class="mb-2" style="font-size: 0.875rem;">
                                                                <p class="mb-1"><strong>Trainer:</strong> <?php echo e($booking->trainer->user->name); ?></p>
                                                                <p class="mb-1"><strong>Amount:</strong> RM <?php echo e(number_format($booking->total_amount, 2)); ?></p>
                                                                <p class="mb-2"><strong>Period:</strong> <?php echo e(\Carbon\Carbon::parse($booking->start_date)->format('M d, Y')); ?> - <?php echo e(\Carbon\Carbon::parse($booking->end_date)->format('M d, Y')); ?></p>
                                                            </div>
                                                            <div class="alert alert-warning" style="padding: 0.5rem; font-size: 0.85rem; margin-bottom: 1rem;">
                                                                <i class="bi bi-exclamation-triangle me-1"></i>Refund requests are subject to review and approval.
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="refund_reason<?php echo e($booking->id); ?>" class="form-label">Reason for Refund <span class="text-danger">*</span></label>
                                                                <select class="form-select" id="refund_reason<?php echo e($booking->id); ?>" name="refund_reason" required>
                                                                    <option value="">Select a reason...</option>
                                                                    <option value="trainer_unavailable">Trainer Unavailable</option>
                                                                    <option value="service_not_as_described">Service Not as Described</option>
                                                                    <option value="cancelled_by_customer">Cancelled by Customer</option>
                                                                    <option value="technical_issues">Technical Issues</option>
                                                                    <option value="other">Other</option>
                                                                </select>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="refund_details<?php echo e($booking->id); ?>" class="form-label">Additional Details</label>
                                                                <textarea class="form-control" 
                                                                          id="refund_details<?php echo e($booking->id); ?>" 
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
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <?php echo e($bookings->links()); ?>

                    </div>
                <?php else: ?>
                    <div class="customer-no-bookings-new">
                        <div class="text-center py-5">
                            <i class="bi bi-calendar-x" style="font-size: 4rem; color: #999999;"></i>
                            <h3 class="mt-3" style="color: #333333;">No Bookings Yet</h3>
                            <p style="color: #666666;">You haven't made any bookings yet. Start by browsing our trainers!</p>
                            <a href="<?php echo e(route('trainers')); ?>" class="btn btn-primary mt-3" style="background: #4a9eff; border: none;">
                                <i class="bi bi-search me-2"></i>Browse Trainers
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\hnsupplement\resources\views/customer/bookings.blade.php ENDPATH**/ ?>