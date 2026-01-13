<ul class="sidebar-menu">
    <li class="sidebar-menu-item">
        <a href="<?php echo e(route('trainer.dashboard')); ?>" class="sidebar-menu-link <?php echo e(request()->routeIs('trainer.dashboard') ? 'active' : ''); ?>">
            <i class="bi bi-speedometer2 me-2"></i>
            <span>Dashboard</span>
        </a>
    </li>
    <li class="sidebar-menu-item">
        <a href="<?php echo e(route('trainer.bookings')); ?>" class="sidebar-menu-link <?php echo e(request()->routeIs('trainer.bookings') ? 'active' : ''); ?>">
            <i class="bi bi-calendar-check me-2"></i>
            <span>Bookings</span>
        </a>
    </li>
    <li class="sidebar-menu-item">
        <a href="<?php echo e(route('trainer.profile')); ?>" class="sidebar-menu-link <?php echo e(request()->routeIs('trainer.profile') ? 'active' : ''); ?>">
            <i class="bi bi-person-circle me-2"></i>
            <span>Profile</span>
        </a>
    </li>
    <li class="sidebar-menu-item">
        <a href="#" class="sidebar-menu-link">
            <i class="bi bi-star me-2"></i>
            <span>Reviews</span>
        </a>
    </li>
    <li class="sidebar-menu-item">
        <a href="<?php echo e(route('trainer.availability')); ?>" class="sidebar-menu-link <?php echo e(request()->routeIs('trainer.availability') ? 'active' : ''); ?>">
            <i class="bi bi-calendar-event me-2"></i>
            <span>Availability</span>
        </a>
    </li>
</ul>

<?php /**PATH D:\hnsupplement\resources\views/components/trainer-sidebar.blade.php ENDPATH**/ ?>