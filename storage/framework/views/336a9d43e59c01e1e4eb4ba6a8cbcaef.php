<?php $__env->startSection('title', 'Trainer Dashboard'); ?>

<?php $__env->startSection('page-title', 'Welcome, ' . auth()->user()->name . '!'); ?>

<?php $__env->startSection('sidebar-menu'); ?>
    <?php echo $__env->make('components.trainer-sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card dashboard-stat-card">
            <div class="dashboard-stat-icon">
                <i class="bi bi-calendar-check"></i>
            </div>
            <h3 class="dashboard-stat-title">Bookings</h3>
            <p class="dashboard-stat-value">0</p>
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
            <p class="dashboard-stat-value">$0</p>
        </div>
    </div>
</div>

<div class="card dashboard-card">
    <div class="card-body dashboard-card-body">
        <h2 class="dashboard-card-title mb-3">Your Profile</h2>
        <p class="dashboard-text mb-3">Complete your trainer profile to start receiving bookings.</p>
        <a href="#" class="btn dashboard-btn-primary">
            Edit Profile
        </a>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\hnsupplement\resources\views/trainer/dashboard.blade.php ENDPATH**/ ?>