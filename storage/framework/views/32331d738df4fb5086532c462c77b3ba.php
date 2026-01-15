<?php $__env->startSection('title', 'Admin Dashboard'); ?>

<?php $__env->startSection('page-title', 'Welcome to Admin Dashboard'); ?>

<?php $__env->startSection('sidebar-menu'); ?>
    <?php echo $__env->make('components.admin-sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

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

<?php $__env->startSection('content'); ?>
<div class="row g-2 mb-3">
    <div class="col-md-6">
        <div class="card dashboard-stat-card">
            <div class="dashboard-stat-icon">
                <i class="bi bi-person-check"></i>
            </div>
            <h3 class="dashboard-stat-title">Pending Approvals</h3>
            <p class="dashboard-stat-value">
                <?php echo e(\App\Trainer::where('status', 'pending')->count()); ?>

            </p>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card dashboard-stat-card">
            <div class="dashboard-stat-icon">
                <i class="bi bi-people"></i>
            </div>
            <h3 class="dashboard-stat-title">Active Trainers</h3>
            <p class="dashboard-stat-value">
                <?php echo e(\App\Trainer::where('status', 'active')->count()); ?>

            </p>
        </div>
    </div>
</div>

<div class="card dashboard-card">
    <div class="card-body dashboard-card-body">
        <h2 class="dashboard-card-title mb-2">Welcome to Admin Dashboard</h2>
        <p class="dashboard-text mb-2">Manage trainer approvals, view statistics, and oversee the platform from here.</p>
        <a href="<?php echo e(route('admin.approvals')); ?>" class="btn dashboard-btn-primary">
            <i class="bi bi-person-check me-2"></i>Go to Approvals
        </a>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\hnsupplement\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>