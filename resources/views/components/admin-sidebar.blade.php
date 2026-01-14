<ul class="sidebar-menu">
    <li class="sidebar-menu-item">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-menu-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2 me-2"></i>
            <span>Dashboard</span>
        </a>
    </li>
    <li class="sidebar-menu-item">
        <a href="{{ route('admin.trainers') }}" class="sidebar-menu-link {{ request()->routeIs('admin.trainers') ? 'active' : '' }}">
            <i class="bi bi-people me-2"></i>
            <span>Trainers</span>
        </a>
    </li>
    <li class="sidebar-menu-item">
        <a href="{{ route('admin.approvals') }}" class="sidebar-menu-link {{ request()->routeIs('admin.approvals') ? 'active' : '' }}">
            <i class="bi bi-person-check me-2"></i>
            <span>Approvals</span>
        </a>
    </li>
    <li class="sidebar-menu-item">
        <a href="{{ route('admin.monitor.bookings') }}" class="sidebar-menu-link {{ request()->routeIs('admin.monitor.bookings') ? 'active' : '' }}">
            <i class="bi bi-calendar-check me-2"></i>
            <span>Monitor Bookings</span>
        </a>
    </li>
    <li class="sidebar-menu-item">
        <a href="{{ route('admin.profile') }}" class="sidebar-menu-link {{ request()->routeIs('admin.profile*') ? 'active' : '' }}">
            <i class="bi bi-person-circle me-2"></i>
            <span>Profile</span>
        </a>
    </li>
</ul>

