<?php $__env->startSection('title', 'Payment Successful - Booking #' . $booking->id); ?>

<?php $__env->startSection('content'); ?>
<div class="payment-success-container">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="payment-success-card">
                    <div class="payment-success-icon">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <h2 class="payment-success-title">Payment Successful!</h2>
                    <p class="payment-success-message">Your booking has been confirmed.</p>

                    <div class="payment-success-details">
                        <div class="payment-success-detail-item">
                            <span class="payment-success-detail-label">Booking ID:</span>
                            <span class="payment-success-detail-value">#<?php echo e($booking->id); ?></span>
                        </div>
                        <div class="payment-success-detail-item">
                            <span class="payment-success-detail-label">Trainer:</span>
                            <span class="payment-success-detail-value"><?php echo e($booking->trainer->user->name); ?></span>
                        </div>
                        <div class="payment-success-detail-item">
                            <span class="payment-success-detail-label">Amount Paid:</span>
                            <span class="payment-success-detail-value">RM <?php echo e(number_format($booking->total_amount, 2)); ?></span>
                        </div>
                        <div class="payment-success-detail-item">
                            <span class="payment-success-detail-label">Period:</span>
                            <span class="payment-success-detail-value">
                                <?php echo e(\Carbon\Carbon::parse($booking->start_date)->format('M d, Y')); ?> - 
                                <?php echo e(\Carbon\Carbon::parse($booking->end_date)->format('M d, Y')); ?>

                            </span>
                        </div>
                    </div>

                    <div class="payment-success-actions">
                        <a href="<?php echo e(route('home')); ?>" class="payment-success-btn payment-success-btn-primary">
                            <i class="bi bi-house me-2"></i>Go to Home
                        </a>
                        <a href="<?php echo e(route('trainers')); ?>" class="payment-success-btn payment-success-btn-secondary">
                            <i class="bi bi-people me-2"></i>Browse More Trainers
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\hnsupplement\resources\views/bookings/success.blade.php ENDPATH**/ ?>