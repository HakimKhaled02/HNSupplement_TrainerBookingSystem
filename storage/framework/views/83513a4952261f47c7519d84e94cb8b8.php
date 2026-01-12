<ul class="sidebar-menu">
    <li class="sidebar-menu-item">
        <a href="<?php echo e(route('admin.dashboard')); ?>" class="sidebar-menu-link <?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>">
            <i class="bi bi-speedometer2 me-2"></i>
            <span>Dashboard</span>
        </a>
    </li>
    <li class="sidebar-menu-item">
        <a href="<?php echo e(route('admin.trainers')); ?>" class="sidebar-menu-link <?php echo e(request()->routeIs('admin.trainers') ? 'active' : ''); ?>">
            <i class="bi bi-people me-2"></i>
            <span>Trainers</span>
        </a>
    </li>
    <li class="sidebar-menu-item">
        <a href="<?php echo e(route('admin.approvals')); ?>" class="sidebar-menu-link <?php echo e(request()->routeIs('admin.approvals') ? 'active' : ''); ?>">
            <i class="bi bi-person-check me-2"></i>
            <span>Approvals</span>
        </a>
    </li>
    <li class="sidebar-menu-item">
        <a href="#" class="sidebar-menu-link">
            <i class="bi bi-calendar-event me-2"></i>
            <span>Bookings</span>
        </a>
    </li>
    <li class="sidebar-menu-item">
        <a href="#" class="sidebar-menu-link">
            <i class="bi bi-gear me-2"></i>
            <span>Settings</span>
        </a>
    </li>
</ul>

<?php /**PATH D:\hnsupplement\resources\views/components/admin-sidebar.blade.php ENDPATH**/ ?>