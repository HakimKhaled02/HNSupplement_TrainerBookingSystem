

<?php $__env->startSection('title', 'Trainer Approvals - Admin Dashboard'); ?>

<?php $__env->startSection('page-title', 'Trainer Approvals'); ?>

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
<!-- Summary Cards -->
<div class="row g-2 mb-3">
    <div class="col-md-6">
        <div class="approval-summary-card pending">
            <div class="approval-summary-icon">
                <i class="bi bi-clock-history"></i>
            </div>
            <div class="approval-summary-content">
                <h3 class="approval-summary-title">Pending Approvals</h3>
                <p class="approval-summary-count"><?php echo e($pendingTrainers->count()); ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="approval-summary-card active">
            <div class="approval-summary-icon">
                <i class="bi bi-check-circle"></i>
            </div>
            <div class="approval-summary-content">
                <h3 class="approval-summary-title">Active Trainers</h3>
                <p class="approval-summary-count"><?php echo e($activeTrainers->count()); ?></p>
            </div>
        </div>
    </div>
</div>

<!-- Pending Trainers -->
<div class="card mb-3 dashboard-card">
    <div class="card-header dashboard-card-header">
        <h2 class="dashboard-card-title">
            <i class="bi bi-person-check me-2"></i>
            Pending Trainer Approvals
            <span class="approval-count-badge"><?php echo e($pendingTrainers->count()); ?></span>
        </h2>
    </div>
    <div class="card-body dashboard-card-body">
        <?php if($pendingTrainers->count() > 0): ?>
            <div class="trainer-list">
                <?php $__currentLoopData = $pendingTrainers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trainer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="trainer-card pending-card">
                        <div class="trainer-card-header">
                            <div class="trainer-avatar">
                                <?php if($trainer->profile_picture): ?>
                                    <img src="<?php echo e(asset('storage/' . $trainer->profile_picture)); ?>" 
                                         alt="<?php echo e($trainer->user->name); ?>">
                                <?php else: ?>
                                    <?php echo e(strtoupper(substr($trainer->user->name, 0, 1))); ?>

                                <?php endif; ?>
                            </div>
                            <div class="trainer-info">
                                <h3 class="trainer-name"><?php echo e($trainer->user->name); ?></h3>
                                <p class="trainer-email">
                                    <i class="bi bi-envelope me-1"></i><?php echo e($trainer->user->email); ?>

                                </p>
                            </div>
                            <div class="trainer-date">
                                <i class="bi bi-calendar3 me-1"></i>
                                <span><?php echo e($trainer->created_at->format('M d, Y')); ?></span>
                            </div>
                        </div>
                        <div class="trainer-card-footer">
                            <form action="<?php echo e(route('admin.approve.trainer', $trainer->id)); ?>" method="POST" class="d-inline me-2">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn trainer-action-btn approve-btn">
                                    <i class="bi bi-check-lg me-1"></i>Approve
                                </button>
                            </form>
                            <form action="<?php echo e(route('admin.reject.trainer', $trainer->id)); ?>" method="POST" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn trainer-action-btn reject-btn">
                                    <i class="bi bi-x-lg me-1"></i>Reject
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="bi bi-inbox empty-state-icon"></i>
                <p class="empty-state-text">No pending trainer approvals.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Active Trainers -->
<div class="card dashboard-card">
    <div class="card-header dashboard-card-header">
        <h2 class="dashboard-card-title">
            <i class="bi bi-people me-2"></i>
            Active Trainers
            <span class="approval-count-badge"><?php echo e($activeTrainers->count()); ?></span>
        </h2>
    </div>
    <div class="card-body dashboard-card-body">
        <?php if($activeTrainers->count() > 0): ?>
            <div class="trainer-list">
                <?php $__currentLoopData = $activeTrainers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trainer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="trainer-card active-card">
                        <div class="trainer-card-header">
                            <div class="trainer-avatar active">
                                <?php if($trainer->profile_picture): ?>
                                    <img src="<?php echo e(asset('storage/' . $trainer->profile_picture)); ?>" 
                                         alt="<?php echo e($trainer->user->name); ?>">
                                <?php else: ?>
                                    <?php echo e(strtoupper(substr($trainer->user->name, 0, 1))); ?>

                                <?php endif; ?>
                            </div>
                            <div class="trainer-info">
                                <h3 class="trainer-name"><?php echo e($trainer->user->name); ?></h3>
                                <p class="trainer-email">
                                    <i class="bi bi-envelope me-1"></i><?php echo e($trainer->user->email); ?>

                                </p>
                            </div>
                            <div class="trainer-status-info">
                                <span class="badge badge-success-large">
                                    <i class="bi bi-check-circle me-1"></i>Active
                                </span>
                                <div class="trainer-date">
                                    <i class="bi bi-calendar-check me-1"></i>
                                    <span>Approved: <?php echo e($trainer->updated_at->format('M d, Y')); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="bi bi-inbox empty-state-icon"></i>
                <p class="empty-state-text">No active trainers.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\hnsupplement\resources\views/admin/approvals.blade.php ENDPATH**/ ?>