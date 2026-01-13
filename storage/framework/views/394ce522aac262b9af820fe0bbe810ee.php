

<?php $__env->startSection('title', 'My Profile'); ?>

<?php $__env->startSection('content'); ?>
<div class="customer-profile-page-container-new">
    <div class="container" style="padding-top: 2rem; padding-bottom: 3rem;">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <?php if(session('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo e(session('success')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="customer-profile-card-new">
                    <div class="customer-profile-header-new">
                        <div class="customer-profile-header-content">
                            <div class="customer-profile-avatar-wrapper-new">
                                <?php if($user->customer && $user->customer->profile_picture): ?>
                                    <img src="<?php echo e(asset('storage/' . $user->customer->profile_picture)); ?>" 
                                         alt="<?php echo e($user->name); ?>" 
                                         class="customer-profile-avatar-new">
                                <?php else: ?>
                                    <div class="customer-profile-avatar-placeholder-new">
                                        <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="customer-profile-header-info-new">
                                <h2 class="customer-profile-title-new"><?php echo e($user->name); ?></h2>
                                <p class="customer-profile-subtitle-new"><?php echo e($user->email); ?></p>
                            </div>
                        </div>
                        <a href="<?php echo e(route('customer.profile.edit')); ?>" class="btn customer-profile-edit-btn-new">
                            <i class="bi bi-pencil-square me-2"></i>Edit Profile
                        </a>
                    </div>

                    <div class="customer-profile-details-section-new">
                        <h3 class="customer-profile-section-title-new">Profile Information</h3>
                        <div class="customer-profile-info-new">
                            <div class="customer-profile-info-row-new">
                                <span class="customer-profile-info-label-new">Phone Number:</span>
                                <span class="customer-profile-info-value-new">
                                    <?php echo e($user->customer->phone ?? 'Not provided'); ?>

                                </span>
                            </div>
                            <div class="customer-profile-info-row-new">
                                <span class="customer-profile-info-label-new">My Bookings:</span>
                                <span class="customer-profile-info-value-new">
                                    <a href="<?php echo e(route('customer.bookings')); ?>" class="customer-profile-link-new">
                                        View All Bookings
                                        <i class="bi bi-arrow-right ms-2"></i>
                                    </a>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\hnsupplement\resources\views/customer/profile.blade.php ENDPATH**/ ?>