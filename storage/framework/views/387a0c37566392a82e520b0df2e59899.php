

<?php $__env->startSection('title', 'Trainers - Admin Dashboard'); ?>

<?php $__env->startSection('page-title', 'All Trainers'); ?>

<?php $__env->startSection('sidebar-menu'); ?>
    <?php echo $__env->make('components.admin-sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<!-- Trainers List -->
<div class="card dashboard-card">
    <div class="card-header dashboard-card-header">
        <h2 class="dashboard-card-title">
            <i class="bi bi-people me-2"></i>
            Approved Trainers
            <span class="approval-count-badge"><?php echo e($trainers->count()); ?></span>
        </h2>
    </div>
    <div class="card-body dashboard-card-body">
        <?php if($trainers->count() > 0): ?>
            <div class="trainers-list-simple">
                <?php $__currentLoopData = $trainers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trainer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="trainer-list-item">
                        <div class="trainer-list-avatar">
                            <?php if($trainer->profile_picture): ?>
                                <img src="<?php echo e(asset('storage/' . $trainer->profile_picture)); ?>" 
                                     alt="<?php echo e($trainer->user->name); ?>">
                            <?php else: ?>
                                <span><?php echo e(strtoupper(substr($trainer->user->name, 0, 1))); ?></span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="trainer-list-info">
                            <div class="trainer-list-name"><?php echo e($trainer->user->name); ?></div>
                            <div class="trainer-list-details">
                                <span><i class="bi bi-envelope-at"></i> <?php echo e($trainer->user->email); ?></span>
                                <span><i class="bi bi-telephone"></i> <?php echo e($trainer->phone ?? 'N/A'); ?></span>
                                <span><i class="bi bi-geo-alt"></i> <?php echo e($trainer->area ?? 'N/A'); ?>, <?php echo e($trainer->state ?? 'N/A'); ?></span>
                                <span><i class="bi bi-tag"></i> <?php echo e(ucfirst($trainer->category ?? 'N/A')); ?></span>
                                <span><i class="bi bi-star"></i> <?php echo e(number_format($trainer->rating ?? 0, 2)); ?></span>
                                <?php if($trainer->qualification_file): ?>
                                    <a href="<?php echo e(asset('storage/' . $trainer->qualification_file)); ?>" 
                                       target="_blank" 
                                       class="qualification-link-inline">
                                        <i class="bi bi-file-earmark-pdf"></i> Qualification
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="trainer-list-salary">
                            <form action="<?php echo e(route('admin.trainer.update-salary', $trainer->id)); ?>" 
                                  method="POST" 
                                  class="salary-form-inline">
                                <?php echo csrf_field(); ?>
                                <div class="salary-input-inline">
                                    <span class="salary-currency-inline">RM</span>
                                    <input type="number" 
                                           name="salary" 
                                           value="<?php echo e($trainer->salary ?? ''); ?>" 
                                           step="0.01" 
                                           min="0" 
                                           max="999999.99"
                                           class="salary-input-inline-field" 
                                           placeholder="0.00"
                                           required>
                                    <button type="submit" class="salary-save-btn-inline" title="Save">
                                        <i class="bi bi-check-lg"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                        <div class="trainer-list-actions">
                            <button type="button" 
                                    class="btn trainer-view-btn-inline" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#trainerModal<?php echo e($trainer->id); ?>">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Trainer Details Modal -->
                    <div class="modal fade" id="trainerModal<?php echo e($trainer->id); ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content trainer-modal-content">
                                <div class="modal-header trainer-modal-header">
                                    <h5 class="modal-title">Trainer Details</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body trainer-modal-body">
                                    <div class="row">
                                        <div class="col-md-4 text-center mb-3">
                                            <?php if($trainer->profile_picture): ?>
                                                <img src="<?php echo e(asset('storage/' . $trainer->profile_picture)); ?>" 
                                                     alt="<?php echo e($trainer->user->name); ?>" 
                                                     class="trainer-modal-img">
                                            <?php else: ?>
                                                <div class="trainer-avatar-large">
                                                    <?php echo e(strtoupper(substr($trainer->user->name, 0, 1))); ?>

                                                </div>
                                            <?php endif; ?>
                                            <h4 class="mt-3"><?php echo e($trainer->user->name); ?></h4>
                                            <p class="text-muted"><?php echo e($trainer->user->email); ?></p>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="trainer-details-list">
                                                <div class="detail-item">
                                                    <strong><i class="bi bi-telephone me-2"></i>Phone:</strong>
                                                    <span><?php echo e($trainer->phone ?? 'N/A'); ?></span>
                                                </div>
                                                <div class="detail-item">
                                                    <strong><i class="bi bi-geo-alt me-2"></i>Location:</strong>
                                                    <span><?php echo e($trainer->area ?? 'N/A'); ?>, <?php echo e($trainer->state ?? 'N/A'); ?></span>
                                                </div>
                                                <div class="detail-item">
                                                    <strong><i class="bi bi-tag me-2"></i>Category:</strong>
                                                    <span><?php echo e(ucfirst($trainer->category ?? 'N/A')); ?></span>
                                                </div>
                                                <div class="detail-item">
                                                    <strong><i class="bi bi-star me-2"></i>Rating:</strong>
                                                    <span><?php echo e(number_format($trainer->rating ?? 0, 2)); ?></span>
                                                </div>
                                                <div class="detail-item">
                                                    <strong><i class="bi bi-currency-dollar me-2"></i>Hourly Rate:</strong>
                                                    <span>RM <?php echo e(number_format($trainer->hourly_rate ?? 0, 2)); ?></span>
                                                </div>
                                                <div class="detail-item">
                                                    <strong><i class="bi bi-wallet2 me-2"></i>Salary:</strong>
                                                    <span>RM <?php echo e(number_format($trainer->salary ?? 0, 2)); ?></span>
                                                </div>
                                                <?php if($trainer->bio): ?>
                                                <div class="detail-item">
                                                    <strong><i class="bi bi-person-badge me-2"></i>Bio:</strong>
                                                    <p><?php echo e($trainer->bio); ?></p>
                                                </div>
                                                <?php endif; ?>
                                                <?php if($trainer->specialties): ?>
                                                <div class="detail-item">
                                                    <strong><i class="bi bi-award me-2"></i>Specialties:</strong>
                                                    <div class="specialties-list">
                                                        <?php $__currentLoopData = $trainer->specialties; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $specialty): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <span class="badge badge-specialty"><?php echo e($specialty); ?></span>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </div>
                                                </div>
                                                <?php endif; ?>
                                                <?php if($trainer->certifications): ?>
                                                <div class="detail-item">
                                                    <strong><i class="bi bi-patch-check me-2"></i>Certifications:</strong>
                                                    <div class="certifications-list">
                                                        <?php $__currentLoopData = $trainer->certifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cert): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <span class="badge badge-cert"><?php echo e($cert); ?></span>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </div>
                                                </div>
                                                <?php endif; ?>
                                                <?php if($trainer->qualification_file): ?>
                                                <div class="detail-item">
                                                    <strong><i class="bi bi-file-earmark-pdf me-2"></i>Qualification File:</strong>
                                                    <a href="<?php echo e(asset('storage/' . $trainer->qualification_file)); ?>" 
                                                       target="_blank" 
                                                       class="qualification-link">
                                                        <i class="bi bi-download me-1"></i>Download
                                                    </a>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="bi bi-inbox empty-state-icon"></i>
                <p class="empty-state-text">No approved trainers found.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\hnsupplement\resources\views/admin/trainers.blade.php ENDPATH**/ ?>