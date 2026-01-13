

<?php $__env->startSection('title', 'My Bookings'); ?>

<?php $__env->startSection('content'); ?>
<div class="customer-bookings-page-container">
    <div class="container" style="padding-top: 2rem; padding-bottom: 3rem;">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="customer-bookings-header mb-4">
                    <h2 class="customer-bookings-page-title">
                        <i class="bi bi-calendar-check me-2"></i>My Bookings
                    </h2>
                </div>

                <?php if($bookings->count() > 0): ?>
                    <div class="customer-bookings-list">
                        <?php $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="customer-booking-card-new">
                            <div class="booking-card-header-new">
                                <div class="booking-trainer-section">
                                    <div class="booking-trainer-image-wrapper">
                                        <?php if($booking->trainer->profile_picture): ?>
                                            <img src="<?php echo e(asset('storage/' . $booking->trainer->profile_picture)); ?>" 
                                                 alt="<?php echo e($booking->trainer->user->name); ?>" 
                                                 class="booking-trainer-image-new">
                                        <?php else: ?>
                                            <div class="booking-trainer-placeholder-new">
                                                <?php echo e(strtoupper(substr($booking->trainer->user->name, 0, 1))); ?>

                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="booking-trainer-info-new">
                                        <h3 class="booking-trainer-name-new"><?php echo e($booking->trainer->user->name); ?></h3>
                                        <p class="booking-id-new">Booking #<?php echo e($booking->id); ?></p>
                                    </div>
                                </div>
                                <span class="booking-status-badge-new booking-status-<?php echo e($booking->payment_status); ?>-new">
                                    <?php echo e(ucfirst($booking->payment_status)); ?>

                                </span>
                            </div>

                            <div class="booking-card-body-new">
                                <div class="booking-details-section-new">
                                    <h4 class="booking-section-title-new">Booking Details</h4>
                                    <div class="booking-info-grid-new">
                                        <div class="booking-info-item-new">
                                            <span class="booking-info-label-new">Period:</span>
                                            <span class="booking-info-value-new">
                                                <?php echo e(\Carbon\Carbon::parse($booking->start_date)->format('M d, Y')); ?> - 
                                                <?php echo e(\Carbon\Carbon::parse($booking->end_date)->format('M d, Y')); ?>

                                            </span>
                                        </div>
                                        <div class="booking-info-item-new">
                                            <span class="booking-info-label-new">Category:</span>
                                            <span class="booking-info-value-new">
                                                <?php echo e(ucfirst(str_replace('_', ' ', $booking->trainer->category ?? 'N/A'))); ?>

                                            </span>
                                        </div>
                                        <div class="booking-info-item-new">
                                            <span class="booking-info-label-new">Location:</span>
                                            <span class="booking-info-value-new">
                                                <?php echo e($booking->trainer->area ?? 'N/A'); ?>, <?php echo e(ucfirst(str_replace('_', ' ', $booking->trainer->state ?? 'N/A'))); ?>

                                            </span>
                                        </div>
                                        <div class="booking-info-item-new">
                                            <span class="booking-info-label-new">Total Amount:</span>
                                            <span class="booking-info-value-new booking-amount-new">
                                                RM <?php echo e(number_format($booking->total_amount, 2)); ?>

                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <?php if($booking->selected_days && count($booking->selected_days) > 0): ?>
                                <div class="booking-schedule-section-new">
                                    <h4 class="booking-section-title-new">Schedule</h4>
                                    <div class="booking-schedule-content-new">
                                        <div class="booking-days-display-new">
                                            <span class="booking-days-label-new">Days:</span>
                                            <div class="booking-days-badges-new">
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
                                                ?>
                                                <?php $__currentLoopData = $booking->selected_days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <span class="booking-day-badge-new"><?php echo e($dayNames[$day] ?? ucfirst($day)); ?></span>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        </div>
                                        <?php if($booking->time_slots && count($booking->time_slots) > 0): ?>
                                        <div class="booking-times-display-new">
                                            <span class="booking-times-label-new">Time Slots:</span>
                                            <div class="booking-times-list-new">
                                                <?php $__currentLoopData = $booking->time_slots; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slot): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php
                                                        $dayName = $dayNames[$slot['day']] ?? ucfirst($slot['day']);
                                                        $startTime = \Carbon\Carbon::createFromFormat('H:i', $slot['start_time'])->format('g:i A');
                                                        $endTime = \Carbon\Carbon::createFromFormat('H:i', $slot['end_time'])->format('g:i A');
                                                    ?>
                                                    <div class="booking-time-item-new">
                                                        <strong><?php echo e($dayName); ?>:</strong> <?php echo e($startTime); ?> - <?php echo e($endTime); ?>

                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>

                            <div class="booking-card-footer-new">
                                <div class="booking-footer-info">
                                    <small class="booking-date-new">
                                        <i class="bi bi-clock me-1"></i>Booked on <?php echo e($booking->created_at->format('M d, Y h:i A')); ?>

                                    </small>
                                    <?php if($booking->payment_status === 'pending' && $booking->payment_expires_at): ?>
                                        <small class="booking-expiry-new">
                                            <i class="bi bi-exclamation-triangle me-1"></i>
                                            Payment expires: <?php echo e($booking->payment_expires_at->format('M d, Y h:i A')); ?>

                                        </small>
                                    <?php endif; ?>
                                </div>
                                <?php if($booking->payment_status === 'paid'): ?>
                                <div class="booking-action-buttons">
                                    <button type="button" class="btn booking-action-btn booking-refund-btn" data-booking-id="<?php echo e($booking->id); ?>">
                                        <i class="bi bi-arrow-counterclockwise me-1"></i>Refund
                                    </button>
                                    <button type="button" class="btn booking-action-btn booking-reminder-btn" data-booking-id="<?php echo e($booking->id); ?>">
                                        <i class="bi bi-bell me-1"></i>Set Reminder
                                    </button>
                                    <button type="button" class="btn booking-action-btn booking-attendance-btn" data-booking-id="<?php echo e($booking->id); ?>">
                                        <i class="bi bi-check-circle me-1"></i>Attendance
                                    </button>
                                    <button type="button" class="btn booking-action-btn booking-feedback-rating-btn" data-booking-id="<?php echo e($booking->id); ?>">
                                        <i class="bi bi-star me-1"></i>Leave Feedback & Rating
                                    </button>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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