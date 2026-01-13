

<?php $__env->startSection('title', 'Manage Bookings - Trainer Dashboard'); ?>

<?php $__env->startSection('page-title', 'Manage Bookings'); ?>

<?php $__env->startSection('sidebar-menu'); ?>
    <?php echo $__env->make('components.trainer-sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="trainer-bookings-page-container">
    <div class="container" style="padding-top: 2rem; padding-bottom: 3rem;">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <?php if(session('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo e(session('success')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if(session('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo e(session('error')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if(session('open_attendance_modal')): ?>
                    <?php
                        $modalId = session('open_attendance_modal');
                    ?>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            var modalElement = document.getElementById('attendanceModal<?php echo e($modalId); ?>');
                            if (modalElement) {
                                var modal = new bootstrap.Modal(modalElement);
                                modal.show();
                            }
                        });
                    </script>
                <?php endif; ?>

                <?php if($bookings->count() > 0): ?>
                    <div class="card" style="background: #ffffff; border-radius: 12px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);">
                        <div class="card-body" style="padding: 1rem;">
                            <div class="table-responsive">
                            <div class="table-responsive">
                                <table class="table bookings-table">
                                    <thead>
                                        <tr>
                                            <th>Booking ID</th>
                                            <th>Customer</th>
                                            <th>Period</th>
                                            <th>Days</th>
                                            <th>Amount</th>
                                            <th>Progress</th>
                                            <th>Booked On</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>#<?php echo e($booking->id); ?></td>
                                            <td>
                                                <div class="booking-customer-info">
                                                    <?php if($booking->user->customer && $booking->user->customer->profile_picture): ?>
                                                        <img src="<?php echo e(asset('storage/' . $booking->user->customer->profile_picture)); ?>" 
                                                             alt="<?php echo e($booking->user->name); ?>" 
                                                             class="booking-customer-avatar">
                                                    <?php else: ?>
                                                        <div class="booking-customer-avatar-placeholder">
                                                            <?php echo e(strtoupper(substr($booking->user->name, 0, 1))); ?>

                                                        </div>
                                                    <?php endif; ?>
                                                    <div>
                                                        <strong><?php echo e($booking->user->name); ?></strong><br>
                                                        <small class="text-muted"><?php echo e($booking->user->email); ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <?php echo e(\Carbon\Carbon::parse($booking->start_date)->format('M d, Y')); ?><br>
                                                <small class="text-muted">to <?php echo e(\Carbon\Carbon::parse($booking->end_date)->format('M d, Y')); ?></small>
                                            </td>
                                            <td>
                                                <?php
                                                    $dayNames = [
                                                        'monday' => 'Mon',
                                                        'tuesday' => 'Tue',
                                                        'wednesday' => 'Wed',
                                                        'thursday' => 'Thu',
                                                        'friday' => 'Fri',
                                                        'saturday' => 'Sat',
                                                        'sunday' => 'Sun'
                                                    ];
                                                ?>
                                                <?php if($booking->selected_days): ?>
                                                    <?php $__currentLoopData = array_slice($booking->selected_days, 0, 3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <span class="day-badge-small"><?php echo e($dayNames[$day] ?? ucfirst($day)); ?></span>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if(count($booking->selected_days) > 3): ?>
                                                        <span class="text-muted">+<?php echo e(count($booking->selected_days) - 3); ?> more</span>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </td>
                                            <td><strong>RM <?php echo e(number_format($booking->total_amount, 2)); ?></strong></td>
                                            <td>
                                                <?php
                                                    $progress = $booking->progress ?? $booking->calculateProgress();
                                                ?>
                                                <span class="progress-badge progress-<?php echo e($progress); ?>">
                                                    <?php echo e(ucfirst($progress)); ?>

                                                </span>
                                            </td>
                                            <td>
                                                <small><?php echo e($booking->created_at->format('M d, Y')); ?><br><?php echo e($booking->created_at->format('h:i A')); ?></small>
                                            </td>
                                            <td>
                                                <button type="button" 
                                                        class="btn btn-sm trainer-attendance-btn" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#attendanceModal<?php echo e($booking->id); ?>">
                                                    <i class="bi bi-check-circle me-1"></i>Attendance
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Attendance Modal -->
                                        <div class="modal fade" id="attendanceModal<?php echo e($booking->id); ?>" tabindex="-1" aria-labelledby="attendanceModalLabel<?php echo e($booking->id); ?>" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header" style="padding: 0.75rem 1rem;">
                                                        <h5 class="modal-title" id="attendanceModalLabel<?php echo e($booking->id); ?>" style="font-size: 1rem; margin: 0;">
                                                            Manage Attendance - Booking #<?php echo e($booking->id); ?>

                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="<?php echo e(route('trainer.attendance.update', $booking->id)); ?>" method="POST">
                                                        <?php echo csrf_field(); ?>
                                                        <div class="modal-body" style="padding: 1rem;">
                                                            <div class="mb-2" style="font-size: 0.875rem;">
                                                                <p class="mb-1"><strong>Customer:</strong> <?php echo e($booking->user->name); ?></p>
                                                                <p class="mb-0"><strong>Period:</strong> <?php echo e(\Carbon\Carbon::parse($booking->start_date)->format('M d, Y')); ?> - <?php echo e(\Carbon\Carbon::parse($booking->end_date)->format('M d, Y')); ?></p>
                                                            </div>

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
                                                                
                                                                $startDate = \Carbon\Carbon::parse($booking->start_date);
                                                                $endDate = \Carbon\Carbon::parse($booking->end_date);
                                                                $currentDate = $startDate->copy();
                                                                
                                                                $slotsByDay = [];
                                                                if ($booking->time_slots) {
                                                                    foreach ($booking->time_slots as $slot) {
                                                                        $slotsByDay[$slot['day']][] = $slot;
                                                                    }
                                                                }
                                                                
                                                                $attendance = $booking->attendance ?? [];
                                                                $attendanceMap = [];
                                                                foreach ($attendance as $att) {
                                                                    $key = $att['day'] . '_' . $att['start_time'] . '_' . $att['date'];
                                                                    $attendanceMap[$key] = $att['status'];
                                                                }
                                                            ?>

                                                            <div class="attendance-schedule-modal">
                                                                <?php while($currentDate <= $endDate): ?>
                                                                    <?php
                                                                        $dateStr = $currentDate->format('Y-m-d');
                                                                        $dayOfWeek = strtolower($currentDate->format('l'));
                                                                        
                                                                        if (isset($slotsByDay[$dayOfWeek])) {
                                                                            $daySlots = $slotsByDay[$dayOfWeek];
                                                                    ?>
                                                                    
                                                                    <div class="attendance-day-section-modal">
                                                                        <h6 class="attendance-day-title-modal">
                                                                            <?php echo e($dayNames[$dayOfWeek]); ?>, <?php echo e($currentDate->format('M d, Y')); ?>

                                                                        </h6>
                                                                        
                                                                        <div class="attendance-slots-modal">
                                                                            <?php $__currentLoopData = $daySlots; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slot): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                <?php
                                                                                    $slotKey = $dayOfWeek . '_' . $slot['start_time'] . '_' . $dateStr;
                                                                                    $currentStatus = $attendanceMap[$slotKey] ?? null;
                                                                                    $startTime = \Carbon\Carbon::createFromFormat('H:i', $slot['start_time'])->format('g:i A');
                                                                                    $endTime = \Carbon\Carbon::createFromFormat('H:i', $slot['end_time'])->format('g:i A');
                                                                                ?>
                                                                                
                                                                                <div class="attendance-slot-item-modal">
                                                                                    <span class="attendance-slot-time-modal"><?php echo e($startTime); ?> - <?php echo e($endTime); ?></span>
                                                                                    <div class="attendance-slot-actions-modal">
                                                                                        <label class="attendance-radio-label-modal">
                                                                                            <input type="radio" 
                                                                                                   name="attendance[<?php echo e($slotKey); ?>]" 
                                                                                                   value="present"
                                                                                                   <?php echo e($currentStatus === 'present' ? 'checked' : ''); ?>

                                                                                                   class="attendance-radio-input"
                                                                                                   data-slot="<?php echo e($slotKey); ?>">
                                                                                            <span class="attendance-status-badge-modal present">Present</span>
                                                                                        </label>
                                                                                        <label class="attendance-radio-label-modal">
                                                                                            <input type="radio" 
                                                                                                   name="attendance[<?php echo e($slotKey); ?>]" 
                                                                                                   value="absent"
                                                                                                   <?php echo e($currentStatus === 'absent' ? 'checked' : ''); ?>

                                                                                                   class="attendance-radio-input"
                                                                                                   data-slot="<?php echo e($slotKey); ?>">
                                                                                            <span class="attendance-status-badge-modal absent">Absent</span>
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
                                                        </div>
                                                        <div class="modal-footer" style="padding: 0.75rem 1rem;">
                                                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn dashboard-btn-primary btn-sm" id="saveAttendanceBtn<?php echo e($booking->id); ?>">Save Attendance</button>
                                                        </div>
                                                    </form>
                                                    <script>
                                                        document.addEventListener('DOMContentLoaded', function() {
                                                            var form = document.querySelector('#attendanceModal<?php echo e($booking->id); ?> form');
                                                            if (form) {
                                                                form.addEventListener('submit', function(e) {
                                                                    // Get all radio button groups
                                                                    var radioGroups = {};
                                                                    var radioInputs = form.querySelectorAll('input[type="radio"].attendance-radio-input');
                                                                    
                                                                    radioInputs.forEach(function(input) {
                                                                        var name = input.name;
                                                                        if (!radioGroups[name]) {
                                                                            radioGroups[name] = [];
                                                                        }
                                                                        radioGroups[name].push(input);
                                                                    });
                                                                    
                                                                    // Check if at least one radio is selected in each group
                                                                    var allValid = true;
                                                                    for (var groupName in radioGroups) {
                                                                        var group = radioGroups[groupName];
                                                                        var hasSelection = Array.prototype.some.call(group, function(radio) {
                                                                            return radio.checked;
                                                                        });
                                                                        
                                                                        if (!hasSelection) {
                                                                            allValid = false;
                                                                            break;
                                                                        }
                                                                    }
                                                                    
                                                                    if (!allValid) {
                                                                        e.preventDefault();
                                                                        alert('Please select attendance (Present or Absent) for all time slots.');
                                                                        return false;
                                                                    }
                                                                });
                                                            }
                                                        });
                                                    </script>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <?php echo e($bookings->links()); ?>

                    </div>
                <?php else: ?>
                    <div class="card" style="background: #ffffff; border-radius: 12px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);">
                        <div class="card-body">
                            <div class="text-center py-5">
                                <i class="bi bi-calendar-x" style="font-size: 4rem; color: #999999;"></i>
                                <h3 class="mt-3" style="color: #333333;">No Bookings Yet</h3>
                                <p style="color: #666666;">You haven't received any bookings yet.</p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\hnsupplement\resources\views/trainer/bookings.blade.php ENDPATH**/ ?>