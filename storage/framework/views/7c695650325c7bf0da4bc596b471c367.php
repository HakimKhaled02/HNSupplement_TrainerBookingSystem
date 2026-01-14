

<?php $__env->startSection('title', 'Reviews & Ratings - Trainer Dashboard'); ?>

<?php $__env->startSection('page-title', 'Reviews & Ratings'); ?>

<?php $__env->startSection('sidebar-menu'); ?>
    <?php echo $__env->make('components.trainer-sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card dashboard-card">
    <div class="card-body dashboard-card-body">
        <?php if(isset($reviews) && $reviews->count() > 0): ?>
        <div class="reviews-list">
            <?php $__currentLoopData = $reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="review-item">
                <div class="review-header">
                    <div class="review-user-info">
                        <div class="review-user-avatar">
                            <?php if($review->user->customer && $review->user->customer->profile_picture): ?>
                                <img src="<?php echo e(asset('storage/' . $review->user->customer->profile_picture)); ?>" 
                                     alt="<?php echo e($review->user->name); ?>" 
                                     class="review-avatar-img">
                            <?php else: ?>
                                <div class="review-avatar-placeholder">
                                    <?php echo e(strtoupper(substr($review->user->name, 0, 1))); ?>

                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="review-user-details">
                            <h5 class="review-user-name"><?php echo e($review->user->name); ?></h5>
                            <p class="review-date"><?php echo e($review->created_at->format('M d, Y')); ?></p>
                        </div>
                    </div>
                    <div class="review-rating">
                        <?php for($i = 1; $i <= 5; $i++): ?>
                            <i class="bi bi-star-fill <?php echo e($i <= $review->rating ? 'star-filled' : 'star-empty'); ?>"></i>
                        <?php endfor; ?>
                        <span class="review-rating-value"><?php echo e($review->rating); ?>.0</span>
                    </div>
                </div>
                <div class="review-feedback">
                    <p><?php echo e($review->feedback); ?></p>
                </div>
                <?php if($review->booking): ?>
                <div class="review-booking-info">
                    <small class="text-muted">Booking #<?php echo e($review->booking->id); ?></small>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php else: ?>
        <div class="text-center py-5">
            <p class="dashboard-text">No reviews yet. Complete bookings to receive feedback from customers.</p>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\hnsupplement\resources\views/trainer/reviews.blade.php ENDPATH**/ ?>