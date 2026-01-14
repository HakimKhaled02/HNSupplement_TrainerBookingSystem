<?php $__env->startSection('title', 'Trainer Dashboard'); ?>

<?php $__env->startSection('page-title', 'Welcome to Trainer Dashboard'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .dashboard-title {
        background: linear-gradient(135deg, #ffffff 0%, var(--accent-green) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('sidebar-menu'); ?>
    <?php echo $__env->make('components.trainer-sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row g-2 mb-3">
    <div class="col-md-4">
        <div class="card dashboard-stat-card">
            <div class="dashboard-stat-icon">
                <i class="bi bi-calendar-check"></i>
            </div>
            <h3 class="dashboard-stat-title">Bookings</h3>
            <p class="dashboard-stat-value"><?php echo e($totalBookings ?? 0); ?></p>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card dashboard-stat-card">
            <div class="dashboard-stat-icon">
                <i class="bi bi-star-fill"></i>
            </div>
            <h3 class="dashboard-stat-title">Rating</h3>
            <p class="dashboard-stat-value"><?php echo e($trainer->rating ?? '0.00'); ?></p>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card dashboard-stat-card">
            <div class="dashboard-stat-icon">
                <i class="bi bi-currency-dollar"></i>
            </div>
            <h3 class="dashboard-stat-title">Earnings</h3>
            <p class="dashboard-stat-value">RM <?php echo e(number_format($totalEarnings ?? 0, 2)); ?></p>
        </div>
    </div>
</div>

<div class="card dashboard-card">
    <div class="card-body dashboard-card-body">
        <h2 class="dashboard-card-title mb-2">Your Profile</h2>
        <p class="dashboard-text mb-2">Complete your trainer profile to start receiving bookings.</p>
        <a href="<?php echo e(route('trainer.profile.edit')); ?>" class="btn dashboard-btn-primary">
            Edit Profile
        </a>
    </div>
</div>

<?php if(isset($recentBookings) && $recentBookings->count() > 0): ?>
<div class="card dashboard-card">
    <div class="card-body dashboard-card-body">
        <h2 class="dashboard-card-title mb-2">Recent Bookings</h2>
        <div class="recent-bookings-list">
            <?php $__currentLoopData = $recentBookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $progress = $booking->progress ?? $booking->calculateProgress();
                $progressLabels = [
                    'upcoming' => 'Upcoming',
                    'ongoing' => 'Ongoing',
                    'completed' => 'Completed'
                ];
                $progressClass = 'progress-' . $progress;
            ?>
            <div class="recent-booking-item">
                <div class="recent-booking-info">
                    <div class="recent-booking-customer">
                        <strong><?php echo e($booking->user->name); ?></strong>
                    </div>
                    <div class="recent-booking-details">
                        <span class="recent-booking-date">
                            <?php echo e(\Carbon\Carbon::parse($booking->start_date)->format('M d')); ?> - 
                            <?php echo e(\Carbon\Carbon::parse($booking->end_date)->format('M d, Y')); ?>

                        </span>
                        <span class="recent-booking-amount">RM <?php echo e(number_format($booking->total_amount, 2)); ?></span>
                    </div>
                </div>
                <div class="recent-booking-status-group">
                    <span class="recent-booking-status paid">Paid</span>
                    <span class="recent-booking-progress <?php echo e($progressClass); ?>"><?php echo e($progressLabels[$progress] ?? $progress); ?></span>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <div class="mt-2">
            <a href="<?php echo e(route('trainer.bookings')); ?>" class="btn dashboard-btn-primary">
                View All Bookings
            </a>
        </div>
    </div>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\hnsupplement\resources\views/trainer/dashboard.blade.php ENDPATH**/ ?>