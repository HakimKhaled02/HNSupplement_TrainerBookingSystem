<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container-fluid px-4">
        <a class="navbar-brand d-flex align-items-center brand-container" href="{{ route('home') }}">
            <div class="brand-wrapper">
                <img src="{{ asset('images/logo.jpg') }}" alt="HN Supplement Logo" height="40" class="brand-logo">
                <span class="brand-text">HN SUPPLEMENT</span>
            </div>
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">HOME</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('trainers') ? 'active' : '' }}" href="{{ route('trainers') }}">TRAINERS</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">ABOUT</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">CONTACT</a>
                </li>
            </ul>
            <div class="ms-auto d-flex align-items-center gap-3">
                @auth
                    @php
                        $user = auth()->user();
                        $profilePicture = null;
                        if ($user->trainer && $user->trainer->profile_picture) {
                            $profilePicture = $user->trainer->profile_picture;
                        } elseif ($user->customer && $user->customer->profile_picture) {
                            $profilePicture = $user->customer->profile_picture;
                        }
                    @endphp
                    <div class="dropdown profile-dropdown">
                        <button class="profile-trigger" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            @if($profilePicture)
                                <img src="{{ asset('storage/' . $profilePicture) }}" alt="Profile" class="profile-avatar">
                            @else
                                <div class="profile-avatar-text">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end profile-menu" aria-labelledby="profileDropdown">
                            <li>
                                <a class="dropdown-item profile-menu-item" href="{{ route('home') }}">
                                    <i class="bi bi-house me-2"></i>Home
                                </a>
                            </li>
                            <li>
                                @if($user->role === 'trainer')
                                    <a class="dropdown-item profile-menu-item" href="{{ route('trainer.dashboard') }}">
                                        <i class="bi bi-person-circle me-2"></i>Profile
                                    </a>
                                @elseif($user->role === 'staff')
                                    <a class="dropdown-item profile-menu-item" href="{{ route('admin.dashboard') }}">
                                        <i class="bi bi-person-circle me-2"></i>Profile
                                    </a>
                                @else
                                    <a class="dropdown-item profile-menu-item" href="{{ route('customer.profile') }}">
                                        <i class="bi bi-person-circle me-2"></i>Profile
                                    </a>
                                    <a class="dropdown-item profile-menu-item" href="{{ route('customer.bookings') }}">
                                        <i class="bi bi-calendar-check me-2"></i>My Bookings
                                    </a>
                                @endif
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item profile-menu-item logout-btn">
                                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-light me-2 login-signup-btn">
                        Login / Sign Up
                    </a>
                    <a href="{{ route('admin.login') }}" class="btn btn-light admin-panel-btn">
                        Admin Panel
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>
