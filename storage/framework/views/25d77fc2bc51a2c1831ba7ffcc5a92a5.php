

<?php $__env->startSection('title', 'Availability - Trainer Dashboard'); ?>

<?php $__env->startSection('page-title', 'Availability'); ?>

<?php $__env->startSection('sidebar-menu'); ?>
    <?php echo $__env->make('components.trainer-sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="availability-container">
    <?php if(!$hasCompletedBookings): ?>
        <div class="alert alert-warning availability-alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <strong>Notice:</strong> You can only set your availability after completing at least one booking appointment with a customer.
        </div>
    <?php endif; ?>

    <?php if(session('success')): ?>
        <div class="alert alert-success availability-alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger availability-alert">
            <i class="bi bi-x-circle-fill me-2"></i>
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <div class="alert alert-danger availability-alert">
            <i class="bi bi-x-circle-fill me-2"></i>
            <ul class="mb-0">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="card dashboard-card">
        <div class="card-header dashboard-card-header">
            <h2 class="dashboard-card-title">
                <i class="bi bi-calendar-event me-2"></i>Set Your Weekly Availability
            </h2>
            <p class="availability-subtitle">Select at least 3 days per week and choose your available time slots (2 hours each).</p>
        </div>
        <div class="card-body dashboard-card-body">
            <form action="<?php echo e(route('trainer.availability.update')); ?>" method="POST" id="availability-form">
                <?php echo csrf_field(); ?>
                
                <?php
                    $days = [
                        'monday' => 'Monday',
                        'tuesday' => 'Tuesday',
                        'wednesday' => 'Wednesday',
                        'thursday' => 'Thursday',
                        'friday' => 'Friday',
                        'saturday' => 'Saturday',
                        'sunday' => 'Sunday'
                    ];
                    
                    // Fixed 2-hour time slots
                    $timeSlots = [
                        ['start' => '08:00', 'end' => '10:00', 'label' => '8:00 AM - 10:00 AM'],
                        ['start' => '10:00', 'end' => '12:00', 'label' => '10:00 AM - 12:00 PM'],
                        ['start' => '12:00', 'end' => '14:00', 'label' => '12:00 PM - 2:00 PM'],
                        ['start' => '14:00', 'end' => '16:00', 'label' => '2:00 PM - 4:00 PM'],
                        ['start' => '16:00', 'end' => '18:00', 'label' => '4:00 PM - 6:00 PM'],
                        ['start' => '18:00', 'end' => '20:00', 'label' => '6:00 PM - 8:00 PM'],
                        ['start' => '20:00', 'end' => '22:00', 'label' => '8:00 PM - 10:00 PM'],
                    ];
                ?>

                <div class="availability-schedule">
                    <?php $__currentLoopData = $days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dayKey => $dayName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            // Check if all time slots for this day are selected
                            $daySlots = [];
                            $selectedCount = 0;
                            foreach ($timeSlots as $slot) {
                                $isSelected = false;
                                if ($currentAvailability) {
                                    foreach ($currentAvailability as $avail) {
                                        if ($avail['day'] === $dayKey && 
                                            $avail['start_time'] === $slot['start'] && 
                                            $avail['end_time'] === $slot['end']) {
                                            $isSelected = true;
                                            $selectedCount++;
                                            break;
                                        }
                                    }
                                }
                                $daySlots[] = ['slot' => $slot, 'selected' => $isSelected];
                            }
                            $allSelected = $selectedCount === count($timeSlots);
                        ?>
                        <div class="availability-day-row" data-day="<?php echo e($dayKey); ?>">
                            <div class="availability-day-header-simple">
                                <div class="availability-day-header-content">
                                    <label class="select-all-day-label" for="select-all-<?php echo e($dayKey); ?>">
                                        <input type="checkbox" 
                                               class="select-all-day-checkbox" 
                                               id="select-all-<?php echo e($dayKey); ?>"
                                               data-day="<?php echo e($dayKey); ?>"
                                               <?php echo e($allSelected ? 'checked' : ''); ?>

                                               <?php echo e(!$hasCompletedBookings ? 'disabled' : ''); ?>>
                                        <span class="select-all-day-text">Select All</span>
                                    </label>
                                    <h3 class="availability-day-title"><?php echo e($dayName); ?></h3>
                                </div>
                            </div>
                            <div class="availability-time-slots">
                                <?php $__currentLoopData = $daySlots; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $daySlot): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $slot = $daySlot['slot'];
                                        $isSelected = $daySlot['selected'];
                                        $slotId = $dayKey . '-' . $slot['start'];
                                    ?>
                                    <label class="time-slot-checkbox-label <?php echo e($isSelected ? 'selected' : ''); ?>" 
                                           for="slot-<?php echo e($slotId); ?>">
                                        <input type="checkbox" 
                                               class="time-slot-checkbox" 
                                               id="slot-<?php echo e($slotId); ?>"
                                               name="availability[<?php echo e($dayKey); ?>][<?php echo e($slot['start']); ?>]"
                                               value="1"
                                               data-day="<?php echo e($dayKey); ?>"
                                               data-start="<?php echo e($slot['start']); ?>"
                                               data-end="<?php echo e($slot['end']); ?>"
                                               <?php echo e($isSelected ? 'checked' : ''); ?>

                                               <?php echo e(!$hasCompletedBookings ? 'disabled' : ''); ?>>
                                        <span class="time-slot-text"><?php echo e($slot['label']); ?></span>
                                    </label>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <div class="availability-info-box">
                    <i class="bi bi-info-circle me-2"></i>
                    <span>You must select at least <strong>3 days</strong> per week.</span>
                    <span class="selected-days-count" id="selected-days-count">0 days selected</span>
                </div>

                <div class="form-actions-profile">
                    <a href="<?php echo e(route('trainer.dashboard')); ?>" class="btn btn-cancel-profile">
                        <i class="bi bi-x-circle me-2"></i>Cancel
                    </a>
                    <button type="submit" 
                            class="btn dashboard-btn-primary" 
                            id="save-availability-btn"
                            <?php echo e(!$hasCompletedBookings ? 'disabled' : ''); ?>>
                        <i class="bi bi-check-circle me-2"></i>Save Availability
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('availability-form');
    const selectedDaysCount = document.getElementById('selected-days-count');
    const checkboxes = document.querySelectorAll('.time-slot-checkbox');
    const selectAllCheckboxes = document.querySelectorAll('.select-all-day-checkbox');

    function updateSelectedDaysCount() {
        const selectedSlots = document.querySelectorAll('.time-slot-checkbox:checked');
        const daysWithSlots = new Set();
        
        selectedSlots.forEach(checkbox => {
            daysWithSlots.add(checkbox.dataset.day);
        });
        
        const daysCount = daysWithSlots.size;
        selectedDaysCount.textContent = `${daysCount} day${daysCount !== 1 ? 's' : ''} selected`;
        
        if (daysCount < 3) {
            selectedDaysCount.style.color = '#ffc107';
        } else {
            selectedDaysCount.style.color = '#007bff';
        }
    }

    function updateSelectAllCheckbox(dayKey) {
        const dayRow = document.querySelector(`.availability-day-row[data-day="${dayKey}"]`);
        const daySlots = dayRow.querySelectorAll('.time-slot-checkbox');
        const selectAllCheckbox = dayRow.querySelector('.select-all-day-checkbox');
        
        const allChecked = Array.from(daySlots).every(checkbox => checkbox.checked);
        selectAllCheckbox.checked = allChecked;
    }

    // Toggle selected state on checkbox change
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const label = this.closest('.time-slot-checkbox-label');
            if (this.checked) {
                label.classList.add('selected');
            } else {
                label.classList.remove('selected');
            }
            updateSelectedDaysCount();
            updateSelectAllCheckbox(this.dataset.day);
        });
        
        // Set initial state
        if (checkbox.checked) {
            checkbox.closest('.time-slot-checkbox-label').classList.add('selected');
        }
    });

    // Handle "Select All" checkbox
    selectAllCheckboxes.forEach(selectAllCheckbox => {
        selectAllCheckbox.addEventListener('change', function() {
            const dayKey = this.dataset.day;
            const dayRow = document.querySelector(`.availability-day-row[data-day="${dayKey}"]`);
            const daySlots = dayRow.querySelectorAll('.time-slot-checkbox');
            const isChecked = this.checked;
            
            daySlots.forEach(slotCheckbox => {
                if (!slotCheckbox.disabled) {
                    slotCheckbox.checked = isChecked;
                    const label = slotCheckbox.closest('.time-slot-checkbox-label');
                    if (isChecked) {
                        label.classList.add('selected');
                    } else {
                        label.classList.remove('selected');
                    }
                }
            });
            
            updateSelectedDaysCount();
        });
    });

    // Form validation
    form.addEventListener('submit', function(e) {
        const selectedSlots = document.querySelectorAll('.time-slot-checkbox:checked');
        const daysWithSlots = new Set();
        
        selectedSlots.forEach(checkbox => {
            daysWithSlots.add(checkbox.dataset.day);
        });
        
        if (daysWithSlots.size < 3) {
            e.preventDefault();
            alert('Please select at least 3 days per week.');
            return false;
        }
    });

    // Initial count and select all state
    updateSelectedDaysCount();
    selectAllCheckboxes.forEach(checkbox => {
        updateSelectAllCheckbox(checkbox.dataset.day);
    });
});
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\hnsupplement\resources\views/trainer/availability.blade.php ENDPATH**/ ?>