

<?php $__env->startSection('title', 'Find Trainers - HN Supplement'); ?>

<?php $__env->startSection('content'); ?>
<div class="trainers-page-container">
    <div class="container py-5">
        <div class="trainers-layout-container">
            <!-- Filters Sidebar -->
            <div class="trainers-filters-sidebar">
                <div class="trainers-filters-card">
                    <div class="trainers-filters-header">
                        <h3 class="trainers-filters-title">
                            <i class="bi bi-funnel me-2"></i>Filters
                        </h3>
                        <?php if(request()->anyFilled(['category', 'state', 'area', 'availability_day', 'min_rating'])): ?>
                            <a href="<?php echo e(route('trainers')); ?>" class="trainers-clear-filters">
                                <i class="bi bi-x-circle me-1"></i>Clear
                            </a>
                        <?php endif; ?>
                    </div>
                    <form method="GET" action="<?php echo e(route('trainers')); ?>" class="trainers-filters-form">
                        <div class="filter-group">
                            <label class="filter-label">Category</label>
                            <select name="category" class="filter-select">
                                <option value="">All Categories</option>
                                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($category); ?>" <?php echo e(request('category') == $category ? 'selected' : ''); ?>>
                                        <?php echo e(ucfirst(str_replace('_', ' ', $category))); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label class="filter-label">State</label>
                            <select name="state" class="filter-select">
                                <option value="">All States</option>
                                <?php $__currentLoopData = $states; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $state): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($state); ?>" <?php echo e(request('state') == $state ? 'selected' : ''); ?>>
                                        <?php echo e(ucfirst(str_replace('_', ' ', $state))); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label class="filter-label">Area</label>
                            <input type="text" 
                                   name="area" 
                                   class="filter-input" 
                                   placeholder="Enter area"
                                   value="<?php echo e(request('area')); ?>">
                        </div>
                        <div class="filter-group">
                            <label class="filter-label">Availability Day</label>
                            <select name="availability_day" class="filter-select">
                                <option value="">Any Day</option>
                                <option value="monday" <?php echo e(request('availability_day') == 'monday' ? 'selected' : ''); ?>>Monday</option>
                                <option value="tuesday" <?php echo e(request('availability_day') == 'tuesday' ? 'selected' : ''); ?>>Tuesday</option>
                                <option value="wednesday" <?php echo e(request('availability_day') == 'wednesday' ? 'selected' : ''); ?>>Wednesday</option>
                                <option value="thursday" <?php echo e(request('availability_day') == 'thursday' ? 'selected' : ''); ?>>Thursday</option>
                                <option value="friday" <?php echo e(request('availability_day') == 'friday' ? 'selected' : ''); ?>>Friday</option>
                                <option value="saturday" <?php echo e(request('availability_day') == 'saturday' ? 'selected' : ''); ?>>Saturday</option>
                                <option value="sunday" <?php echo e(request('availability_day') == 'sunday' ? 'selected' : ''); ?>>Sunday</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label class="filter-label">Min Rating</label>
                            <select name="min_rating" class="filter-select">
                                <option value="">Any Rating</option>
                                <option value="4.5" <?php echo e(request('min_rating') == '4.5' ? 'selected' : ''); ?>>4.5+ Stars</option>
                                <option value="4.0" <?php echo e(request('min_rating') == '4.0' ? 'selected' : ''); ?>>4.0+ Stars</option>
                                <option value="3.5" <?php echo e(request('min_rating') == '3.5' ? 'selected' : ''); ?>>3.5+ Stars</option>
                                <option value="3.0" <?php echo e(request('min_rating') == '3.0' ? 'selected' : ''); ?>>3.0+ Stars</option>
                            </select>
                        </div>
                        <button type="submit" class="filter-submit-btn">
                            <i class="bi bi-search me-2"></i>Apply Filters
                        </button>
                    </form>
                </div>
            </div>

            <!-- Trainers Content -->
            <div class="trainers-content-area">
                <!-- Results Count -->
                <div class="trainers-results-count">
                    <span>Found <strong><?php echo e($trainers->total()); ?></strong> trainer<?php echo e($trainers->total() !== 1 ? 's' : ''); ?></span>
                </div>

                <!-- Trainers Grid -->
                <?php if($trainers->count() > 0): ?>
                    <div class="trainers-grid">
                        <?php $__currentLoopData = $trainers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trainer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="<?php echo e(route('trainer.book', $trainer->id)); ?>" class="trainer-card-link">
                            <div class="trainer-card">
                                <div class="trainer-card-image">
                                    <?php if($trainer->profile_picture): ?>
                                        <img src="<?php echo e(asset('storage/' . $trainer->profile_picture)); ?>" 
                                             alt="<?php echo e($trainer->user->name); ?>">
                                    <?php else: ?>
                                        <div class="trainer-card-image-placeholder">
                                            <?php echo e(strtoupper(substr($trainer->user->name, 0, 1))); ?>

                                        </div>
                                    <?php endif; ?>
                                    <div class="trainer-card-badge">CERTIFIED</div>
                                    <div class="trainer-card-rating-badge">
                                        <i class="bi bi-star-fill"></i>
                                        <span><?php echo e(number_format($trainer->rating ?? 0, 1)); ?></span>
                                    </div>
                                </div>
                                <div class="trainer-card-content">
                                    <h3 class="trainer-card-name"><?php echo e($trainer->user->name); ?></h3>
                                    <div class="trainer-card-info">
                                        <div class="trainer-card-info-item">
                                            <i class="bi bi-tag"></i>
                                            <span><?php echo e(ucfirst(str_replace('_', ' ', $trainer->category ?? 'N/A'))); ?></span>
                                        </div>
                                        <div class="trainer-card-info-item">
                                            <i class="bi bi-geo-alt"></i>
                                            <span><?php echo e($trainer->area ?? 'N/A'); ?>, <?php echo e(ucfirst(str_replace('_', ' ', $trainer->state ?? 'N/A'))); ?></span>
                                        </div>
                                        <?php if($trainer->availability && count($trainer->availability) > 0): ?>
                                            <?php
                                                $uniqueDays = [];
                                                $dayNames = [
                                                    'monday' => 'Monday',
                                                    'tuesday' => 'Tuesday',
                                                    'wednesday' => 'Wednesday',
                                                    'thursday' => 'Thursday',
                                                    'friday' => 'Friday',
                                                    'saturday' => 'Saturday',
                                                    'sunday' => 'Sunday'
                                                ];
                                                foreach ($trainer->availability as $avail) {
                                                    if (isset($avail['day']) && isset($dayNames[$avail['day']])) {
                                                        $dayName = $dayNames[$avail['day']];
                                                        if (!in_array($dayName, $uniqueDays)) {
                                                            $uniqueDays[] = $dayName;
                                                        }
                                                    }
                                                }
                                                $daysDisplay = !empty($uniqueDays) ? implode(', ', $uniqueDays) : 'Not set';
                                            ?>
                                            <div class="trainer-card-info-item">
                                                <i class="bi bi-calendar-event"></i>
                                                <span><?php echo e($daysDisplay); ?></span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <?php if($trainer->salary): ?>
                                        <div class="trainer-card-price">
                                            <span class="trainer-card-price-label">Monthly Rate</span>
                                            <span class="trainer-card-price-amount">RM <?php echo e(number_format($trainer->salary, 0)); ?></span>
                                        </div>
                                    <?php endif; ?>
                                    <div class="trainer-card-button">
                                        Book Now
                                    </div>
                                </div>
                            </div>
                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <!-- Pagination -->
                    <div class="trainers-pagination">
                        <?php echo e($trainers->links()); ?>

                    </div>
                <?php else: ?>
                    <div class="trainers-empty">
                        <i class="bi bi-person-x"></i>
                        <h3>No trainers found</h3>
                        <p>Try adjusting your filters to see more results.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\hnsupplement\resources\views/trainers/index.blade.php ENDPATH**/ ?>