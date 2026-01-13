

<?php $__env->startSection('title', 'Manage Attendance - Trainer Dashboard'); ?>

<?php $__env->startSection('page-title', 'Manage Attendance'); ?>

<?php $__env->startSection('sidebar-menu'); ?>
    <?php echo $__env->make('components.trainer-sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="attendance-container">
    <div class="container" style="padding-top: 2rem; padding-bottom: 3rem;">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <?php if(session('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo e(session('success')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="card dashboard-card">
                    <div class="card-body dashboard-card-body">
                        <div class="mb-4">
                            <h2 class="dashboard-card-title mb-2">Booking #<?php echo e($booking->id); ?></h2>
                            <p class="dashboard-text mb-0">Customer: <strong><?php echo e($booking->user->name); ?></strong></p>
                            <p class="dashboard-text mb-0">Period: <?php echo e(\Carbon\Carbon::parse($booking->start_date)->format('M d, Y')); ?> - <?php echo e(\Carbon\Carbon::parse($booking->end_date)->format('M d, Y')); ?></p>
                        </div>

                        <form action="<?php echo e(route('trainer.attendance.update', $booking->id)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            
                            <?php
                                $dayNames = [
                                    'monday' => 'Monday',
                                    'tuesday' => 'Tuesday',
                                    'wednesday' => 'Wednesday',
                                    'thursday' => 'Thursday',
                                    'friday' => 'Friday',
                                    'saturday' => 'Saturday',
                                    'sunday' => 'Sunday'
                                ];
                                
                                // Get all dates in the booking period
                                $startDate = \Carbon\Carbon::parse($booking->start_date);
                                $endDate = \Carbon\Carbon::parse($booking->end_date);
                                $currentDate = $startDate->copy();
                                
                                // Group time slots by day
                                $slotsByDay = [];
                                if ($booking->time_slots) {
                                    foreach ($booking->time_slots as $slot) {
                                        $slotsByDay[$slot['day']][] = $slot;
                                    }
                                }
                                
                                // Get existing attendance
                                $attendance = $booking->attendance ?? [];
                                $attendanceMap = [];
                                foreach ($attendance as $att) {
                                    $key = $att['day'] . '_' . $att['start_time'] . '_' . $att['date'];
                                    $attendanceMap[$key] = $att['status'];
                                }
                            ?>

                            <div class="attendance-schedule">
                                <?php while($currentDate <= $endDate): ?>
                                    <?php
                                        $dateStr = $currentDate->format('Y-m-d');
                                        $dayOfWeek = strtolower($currentDate->format('l'));
                                        
                                        // Check if this day has time slots
                                        if (isset($slotsByDay[$dayOfWeek])) {
                                            $daySlots = $slotsByDay[$dayOfWeek];
                                    ?>
                                    
                                    <div class="attendance-day-section">
                                        <h4 class="attendance-day-title">
                                            <?php echo e($dayNames[$dayOfWeek]); ?>, <?php echo e($currentDate->format('M d, Y')); ?>

                                        </h4>
                                        
                                        <div class="attendance-slots">
                                            <?php $__currentLoopData = $daySlots; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slot): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php
                                                    $slotKey = $dayOfWeek . '_' . $slot['start_time'] . '_' . $dateStr;
                                                    $currentStatus = $attendanceMap[$slotKey] ?? null;
                                                    $startTime = \Carbon\Carbon::createFromFormat('H:i', $slot['start_time'])->format('g:i A');
                                                    $endTime = \Carbon\Carbon::createFromFormat('H:i', $slot['end_time'])->format('g:i A');
                                                ?>
                                                
                                                <div class="attendance-slot-item">
                                                    <div class="attendance-slot-time">
                                                        <strong><?php echo e($startTime); ?> - <?php echo e($endTime); ?></strong>
                                                    </div>
                                                    <div class="attendance-slot-actions">
                                                        <label class="attendance-radio-label">
                                                            <input type="radio" 
                                                                   name="attendance[<?php echo e($slotKey); ?>]" 
                                                                   value="present"
                                                                   <?php echo e($currentStatus === 'present' ? 'checked' : ''); ?>

                                                                   required>
                                                            <span class="attendance-status-badge present">Present</span>
                                                        </label>
                                                        <label class="attendance-radio-label">
                                                            <input type="radio" 
                                                                   name="attendance[<?php echo e($slotKey); ?>]" 
                                                                   value="absent"
                                                                   <?php echo e($currentStatus === 'absent' ? 'checked' : ''); ?>

                                                                   required>
                                                            <span class="attendance-status-badge absent">Absent</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                    
                                    <?php
                                        }
                                        $currentDate->addDay();
                                    ?>
                                <?php endwhile; ?>
                            </div>

                            <div class="attendance-actions mt-4">
                                <a href="<?php echo e(route('trainer.bookings')); ?>" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left me-2"></i>Back to Bookings
                                </a>
                                <button type="submit" class="btn dashboard-btn-primary">
                                    <i class="bi bi-check-circle me-2"></i>Save Attendance
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\hnsupplement\resources\views/trainer/attendance.blade.php ENDPATH**/ ?>