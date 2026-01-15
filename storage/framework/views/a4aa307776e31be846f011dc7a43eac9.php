

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
                        <?php if(request()->anyFilled(['category', 'state', 'price_range', 'availability_day', 'rating_range', 'user_lat', 'user_lng', 'radius'])): ?>
                            <a href="<?php echo e(route('trainers')); ?>" class="trainers-clear-filters">
                                <i class="bi bi-x-circle me-1"></i>Clear All
                            </a>
                        <?php endif; ?>
                    </div>
                    <form method="GET" action="<?php echo e(route('trainers')); ?>" class="trainers-filters-form">
                        <!-- Preserve location and radius when applying filters -->
                        <?php if(isset($userLat) && isset($userLng)): ?>
                            <input type="hidden" name="user_lat" value="<?php echo e($userLat); ?>">
                            <input type="hidden" name="user_lng" value="<?php echo e($userLng); ?>">
                        <?php endif; ?>
                        <?php if(isset($radius) && $radius): ?>
                            <input type="hidden" name="radius" value="<?php echo e($radius); ?>">
                        <?php endif; ?>
                        
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
                            <label class="filter-label">Price Range</label>
                            <select name="price_range" class="filter-select">
                                <option value="">All Prices</option>
                                <option value="below_100" <?php echo e(request('price_range') == 'below_100' ? 'selected' : ''); ?>>Below RM100</option>
                                <option value="100_300" <?php echo e(request('price_range') == '100_300' ? 'selected' : ''); ?>>RM100 - RM300</option>
                                <option value="300_500" <?php echo e(request('price_range') == '300_500' ? 'selected' : ''); ?>>RM300 - RM500</option>
                                <option value="above_500" <?php echo e(request('price_range') == 'above_500' ? 'selected' : ''); ?>>Above RM500</option>
                            </select>
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
                            <label class="filter-label">Rating</label>
                            <select name="rating_range" class="filter-select">
                                <option value="">All Ratings</option>
                                <option value="above_4.5" <?php echo e(request('rating_range') == 'above_4.5' ? 'selected' : ''); ?>>Above 4.5</option>
                                <option value="4.0_4.5" <?php echo e(request('rating_range') == '4.0_4.5' ? 'selected' : ''); ?>>4.0 - 4.5</option>
                                <option value="3.5_4.0" <?php echo e(request('rating_range') == '3.5_4.0' ? 'selected' : ''); ?>>3.5 - 4.0</option>
                                <option value="3.0_3.5" <?php echo e(request('rating_range') == '3.0_3.5' ? 'selected' : ''); ?>>3.0 - 3.5</option>
                                <option value="below_3.0" <?php echo e(request('rating_range') == 'below_3.0' ? 'selected' : ''); ?>>Below 3.0</option>
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
                <!-- Location & Radius Controls -->
                <div class="trainers-controls-bar">
                    <div class="trainers-location-control">
                        <button type="button" id="get-user-location" class="btn-location-detect">
                            <i class="bi bi-geo-alt-fill me-2"></i>Find Nearest Trainer
                        </button>
                        <span id="location-status" class="location-status-text"></span>
                        <input type="hidden" id="user-latitude" name="user_lat" value="<?php echo e($userLat ?? ''); ?>">
                        <input type="hidden" id="user-longitude" name="user_lng" value="<?php echo e($userLng ?? ''); ?>">
                    </div>
                    <div class="trainers-radius-control">
                        <label for="radius-filter" class="radius-label">Search Radius:</label>
                        <select id="radius-filter" name="radius" class="radius-select" onchange="updateRadius()">
                            <option value="">All Distance</option>
                            <option value="5" <?php echo e(($radius ?? '') == '5' ? 'selected' : ''); ?>>5 km</option>
                            <option value="10" <?php echo e(($radius ?? '') == '10' ? 'selected' : ''); ?>>10 km</option>
                            <option value="30" <?php echo e(($radius ?? '') == '30' ? 'selected' : ''); ?>>30 km</option>
                            <option value="50" <?php echo e(($radius ?? '') == '50' ? 'selected' : ''); ?>>50 km</option>
                        </select>
                    </div>
                </div>

                <!-- Results Count -->
                <div class="trainers-results-count">
                    <span>Found <strong><?php echo e($trainers->total()); ?></strong> trainer<?php echo e($trainers->total() !== 1 ? 's' : ''); ?></span>
                    <?php if(isset($userLat) && isset($userLng)): ?>
                        <span class="location-active-badge">
                            <i class="bi bi-check-circle me-1"></i>Location detected
                        </span>
                    <?php endif; ?>
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
                                            <div class="trainer-card-price-left">
                                                <span class="trainer-card-price-label">Monthly Rate</span>
                                                <span class="trainer-card-price-amount">RM <?php echo e(number_format($trainer->salary, 0)); ?></span>
                                            </div>
                                            <?php if(isset($trainer->distance) && $trainer->distance !== null): ?>
                                                <div class="trainer-card-distance">
                                                    <i class="bi bi-signpost-2 me-1"></i><?php echo e($trainer->distance); ?> km away
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php elseif(isset($trainer->distance) && $trainer->distance !== null): ?>
                                        <div class="trainer-card-price">
                                            <div class="trainer-card-price-left"></div>
                                            <div class="trainer-card-distance">
                                                <i class="bi bi-signpost-2 me-1"></i><?php echo e($trainer->distance); ?> km away
                                            </div>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const getLocationBtn = document.getElementById('get-user-location');
    const locationStatus = document.getElementById('location-status');
    const userLatInput = document.getElementById('user-latitude');
    const userLngInput = document.getElementById('user-longitude');

    // Get user's current location
    getLocationBtn.addEventListener('click', function() {
        if (!navigator.geolocation) {
            locationStatus.textContent = 'Geolocation is not supported by your browser';
            locationStatus.style.color = '#dc3545';
            return;
        }

        locationStatus.textContent = 'Detecting location...';
        locationStatus.style.color = '#17a2b8';
        getLocationBtn.disabled = true;

        navigator.geolocation.getCurrentPosition(
            function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                
                userLatInput.value = lat;
                userLngInput.value = lng;
                
                locationStatus.textContent = 'Location detected!';
                locationStatus.style.color = '#28a745';
                
                // Reload page with location parameters, preserving all existing filters
                const url = new URL(window.location.href);
                url.searchParams.set('user_lat', lat);
                url.searchParams.set('user_lng', lng);
                // Preserve all existing filter parameters (category, state, price_range, availability_day, rating_range, radius)
                window.location.href = url.toString();
            },
            function(error) {
                let errorMsg = 'Unable to detect location. ';
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                        errorMsg += 'Please allow location access.';
                        break;
                    case error.POSITION_UNAVAILABLE:
                        errorMsg += 'Location information unavailable.';
                        break;
                    case error.TIMEOUT:
                        errorMsg += 'Location request timed out.';
                        break;
                    default:
                        errorMsg += 'An unknown error occurred.';
                        break;
                }
                locationStatus.textContent = errorMsg;
                locationStatus.style.color = '#dc3545';
                getLocationBtn.disabled = false;
            }
        );
    });

    // Update radius function - preserves all existing filters
    window.updateRadius = function() {
        const radiusSelect = document.getElementById('radius-filter');
        const radiusValue = radiusSelect.value;
        const userLat = document.getElementById('user-latitude').value;
        const userLng = document.getElementById('user-longitude').value;
        
        // If radius is selected but no location, prompt user to find location first
        if (radiusValue && (!userLat || !userLng)) {
            alert('Please click "Find Nearest Trainer" first to detect your location before setting a search radius.');
            radiusSelect.value = '';
            return;
        }
        
        const url = new URL(window.location.href);
        if (radiusValue) {
            url.searchParams.set('radius', radiusValue);
        } else {
            url.searchParams.delete('radius');
        }
        // Preserve all existing filter parameters (category, state, price_range, availability_day, rating_range, user_lat, user_lng)
        window.location.href = url.toString();
    };
});
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\hnsupplement\resources\views/trainers/index.blade.php ENDPATH**/ ?>