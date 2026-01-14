

<?php $__env->startSection('title', 'My Profile - Trainer Dashboard'); ?>

<?php $__env->startSection('page-title', 'My Profile'); ?>

<?php $__env->startSection('sidebar-menu'); ?>
    <?php echo $__env->make('components.trainer-sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header-actions'); ?>
    <a href="<?php echo e(route('trainer.profile.edit')); ?>" class="btn dashboard-btn-primary">
        <i class="bi bi-pencil-square me-2"></i>Edit Profile
    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="profile-single-container">
    <div class="card dashboard-card profile-unified-card">
        <div class="card-body dashboard-card-body">
            <!-- Profile Header -->
            <div class="profile-header-section">
                <div class="profile-avatar-section">
                    <?php if($trainer && $trainer->profile_picture): ?>
                        <img src="<?php echo e(asset('storage/' . $trainer->profile_picture)); ?>" 
                             alt="<?php echo e(auth()->user()->name); ?>" 
                             class="profile-avatar-img">
                    <?php else: ?>
                        <div class="profile-avatar-placeholder">
                            <?php echo e(strtoupper(substr(auth()->user()->name, 0, 1))); ?>

                        </div>
                    <?php endif; ?>
                </div>
                <div class="profile-header-info">
                    <h2 class="profile-header-name"><?php echo e(auth()->user()->name); ?></h2>
                    <p class="profile-header-email"><?php echo e(auth()->user()->email); ?></p>
                    <?php if($trainer && $trainer->status): ?>
                        <span class="profile-status-badge-simple">
                            <?php echo e(ucfirst($trainer->status)); ?>

                        </span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Profile Details -->
            <div class="profile-details-section">
                <div class="profile-details-list">
                    <div class="profile-detail-row">
                        <div class="profile-detail-label-simple">
                            <i class="bi bi-telephone"></i>
                            <span>Phone Number</span>
                        </div>
                        <div class="profile-detail-value-simple">
                            <?php echo e($trainer->phone ?? 'Not provided'); ?>

                        </div>
                    </div>

                    <div class="profile-detail-row">
                        <div class="profile-detail-label-simple">
                            <i class="bi bi-geo-alt"></i>
                            <span>Location</span>
                        </div>
                        <div class="profile-detail-value-simple">
                            <?php echo e($trainer->area ?? 'N/A'); ?>, <?php echo e(ucfirst(str_replace('_', ' ', $trainer->state ?? 'N/A'))); ?>

                        </div>
                    </div>

                    <div class="profile-detail-row">
                        <div class="profile-detail-label-simple">
                            <i class="bi bi-tag"></i>
                            <span>Category</span>
                        </div>
                        <div class="profile-detail-value-simple">
                            <span class="profile-category-badge-simple">
                                <?php echo e(ucfirst(str_replace('_', ' ', $trainer->category ?? 'Not specified'))); ?>

                            </span>
                        </div>
                    </div>

                    <div class="profile-detail-row">
                        <div class="profile-detail-label-simple">
                            <i class="bi bi-star"></i>
                            <span>Rating</span>
                        </div>
                        <div class="profile-detail-value-simple">
                            <span class="profile-rating-simple">
                                <i class="bi bi-star-fill"></i> <?php echo e(number_format($trainer->rating ?? 0, 2)); ?>

                            </span>
                        </div>
                    </div>

                    <div class="profile-detail-row profile-salary-row">
                        <div class="profile-detail-label-simple">
                            <i class="bi bi-wallet2"></i>
                            <span>Salary</span>
                        </div>
                        <div class="profile-detail-value-simple">
                            <?php if($trainer->salary): ?>
                                <div class="profile-salary-amount-simple">RM <?php echo e(number_format($trainer->salary, 2)); ?></div>
                                <div class="profile-salary-note-simple">Set by Admin</div>
                            <?php else: ?>
                                <span class="profile-salary-not-set-simple">Not set yet</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if($trainer->qualification_file): ?>
                    <div class="profile-detail-row">
                        <div class="profile-detail-label-simple">
                            <i class="bi bi-file-earmark-pdf"></i>
                            <span>Qualification File</span>
                        </div>
                        <div class="profile-detail-value-simple">
                            <a href="<?php echo e(asset('storage/' . $trainer->qualification_file)); ?>" 
                               target="_blank" 
                               class="profile-qualification-link-simple">
                                <i class="bi bi-download"></i> Download
                            </a>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if($trainer->latitude && $trainer->longitude): ?>
                    <div class="profile-detail-row">
                        <div class="profile-detail-label-simple">
                            <i class="bi bi-geo"></i>
                            <span>Location Coordinates</span>
                        </div>
                        <div class="profile-detail-value-simple">
                            <div class="profile-coordinates-simple">
                                <span>Lat: <?php echo e($trainer->latitude); ?>, Long: <?php echo e($trainer->longitude); ?></span>
                                <a href="https://www.google.com/maps?q=<?php echo e($trainer->latitude); ?>,<?php echo e($trainer->longitude); ?>" 
                                   target="_blank" 
                                   class="profile-direction-link-simple">
                                    <i class="bi bi-compass"></i> Get Direction
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\hnsupplement\resources\views/trainer/profile.blade.php ENDPATH**/ ?>