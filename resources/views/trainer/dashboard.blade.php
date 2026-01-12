<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Trainer Dashboard - Book Your Trainer</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/jpeg" href="{{ asset('images/logo.jpg') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.jpg') }}">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- Vite CSS -->
    @vite(['resources/css/app.css'])
    
    <style>
        body {
            background-color: #000000;
            color: #ffffff;
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden;
        }
        
        .dashboard-container {
            display: flex;
            min-height: 100vh;
            padding-top: 70px;
        }
        
        /* Sidebar Styles */
        .sidebar {
            width: 280px;
            background: rgba(26, 26, 26, 0.95);
            backdrop-filter: blur(10px);
            border-right: 1px solid rgba(0, 204, 102, 0.3);
            position: fixed;
            left: 0;
            top: 70px;
            height: calc(100vh - 70px);
            overflow-y: auto;
            z-index: 1000;
            transition: all 0.3s ease;
        }
        
        .sidebar-header {
            padding: 25px 20px;
            border-bottom: 1px solid rgba(0, 204, 102, 0.2);
            display: flex;
            align-items: center;
            justify-content: flex-start;
        }
        
        .sidebar-header h3 {
            color: var(--accent-green);
            font-size: 1.2rem;
            font-weight: 700;
            margin: 0;
            font-family: 'Poppins', sans-serif;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .sidebar-header h3 i {
            font-size: 1.3rem;
        }
        
        .sidebar-menu {
            padding: 20px 0;
        }
        
        .menu-item {
            display: flex;
            align-items: center;
            padding: 15px 25px;
            color: #cccccc;
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
            border-left: 3px solid transparent;
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
        }
        
        .menu-item:hover {
            background: rgba(0, 204, 102, 0.1);
            color: var(--accent-green);
            border-left-color: var(--accent-green);
        }
        
        .menu-item.active {
            background: rgba(0, 204, 102, 0.15);
            color: var(--accent-green);
            border-left-color: var(--accent-green);
        }
        
        .menu-item i {
            font-size: 1.2rem;
            margin-right: 15px;
            width: 24px;
        }
        
        /* Content Area */
        .content-area {
            flex: 1;
            margin-left: 280px;
            padding: 30px;
            transition: all 0.3s ease;
        }
        
        .content-section {
            display: none;
            animation: fadeIn 0.3s ease;
        }
        
        .content-section.active {
            display: block;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .section-header {
            margin-bottom: 30px;
        }
        
        .section-header h2 {
            color: var(--accent-green);
            font-size: 2rem;
            font-weight: 700;
            margin: 0;
            font-family: 'Poppins', sans-serif;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: rgba(26, 26, 26, 0.8);
            border: 1px solid rgba(0, 204, 102, 0.3);
            border-radius: 15px;
            padding: 25px;
            text-align: center;
            transition: all 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            border-color: var(--accent-green);
            box-shadow: 0 10px 30px rgba(0, 204, 102, 0.2);
        }
        
        .stat-card-icon {
            font-size: 3rem;
            color: var(--accent-green);
            margin-bottom: 15px;
        }
        
        .stat-card-title {
            color: #ffffff;
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 10px;
            font-family: 'Poppins', sans-serif;
        }
        
        .stat-card-value {
            color: #cccccc;
            font-size: 2rem;
            font-weight: 700;
            font-family: 'Poppins', sans-serif;
        }
        
        .content-card {
            background: rgba(26, 26, 26, 0.8);
            border: 1px solid rgba(0, 204, 102, 0.3);
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 20px;
        }
        
        .content-card h3 {
            color: var(--accent-green);
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            font-family: 'Poppins', sans-serif;
        }
        
        .content-card p {
            color: #cccccc;
            line-height: 1.8;
            font-family: 'Poppins', sans-serif;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.open {
                transform: translateX(0);
            }
            
            .content-area {
                margin-left: 0;
            }
            
            .menu-toggle {
                display: block;
            }
        }
        
        .menu-toggle {
            display: none;
            position: fixed;
            top: 85px;
            left: 20px;
            z-index: 1001;
            background: rgba(26, 26, 26, 0.95);
            border: 1px solid rgba(0, 204, 102, 0.3);
            color: var(--accent-green);
            padding: 10px 15px;
            border-radius: 8px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <!-- Trainer Dashboard Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top" style="background: rgba(26, 26, 26, 0.95) !important; backdrop-filter: blur(10px); border-bottom: 1px solid rgba(0, 204, 102, 0.3); padding: 1rem 0; z-index: 1050;">
        <div class="container-fluid px-4">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('trainer.dashboard') }}">
                <img src="{{ asset('images/logo.jpg') }}" alt="HN Supplement Logo" height="45" class="me-3" style="border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 204, 102, 0.2);">
                <span>HN SUPPLEMENT</span>
            </a>
            
            <div class="ms-auto d-flex align-items-center gap-3">
                @php
                    $user = auth()->user();
                    $profilePicture = null;
                    if ($user->trainer && $user->trainer->profile_picture) {
                        $profilePicture = $user->trainer->profile_picture;
                    }
                @endphp
                <div class="profile-dropdown">
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
                            <h6 class="dropdown-header" style="color: #ffffff; font-family: 'Poppins', sans-serif; font-weight: 600;">
                                {{ $user->name }}
                            </h6>
                            <p class="dropdown-header-text" style="color: #cccccc; font-size: 0.85rem; font-family: 'Poppins', sans-serif; margin: 0;">
                                {{ $user->email }}
                            </p>
                        </li>
                        <li><hr class="dropdown-divider" style="border-color: rgba(255, 255, 255, 0.1);"></li>
                        <li>
                            <a class="dropdown-item" href="#" onclick="showSection('profile')">
                                <i class="bi bi-person me-2"></i>Profile
                            </a>
                        </li>
                        <li><hr class="dropdown-divider" style="border-color: rgba(255, 255, 255, 0.1);"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                                @csrf
                                <button type="submit" class="dropdown-item logout-btn">
                                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="dashboard-container">
        <!-- Sidebar Menu -->
        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <h3>
                    <i class="bi bi-speedometer2"></i>
                    <span>Trainer Dashboard</span>
                </h3>
            </div>
            <div class="sidebar-menu">
                <a class="menu-item active" onclick="showSection('dashboard')">
                    <i class="bi bi-house-door"></i>
                    <span>Overview</span>
                </a>
                <a class="menu-item" onclick="showSection('bookings')">
                    <i class="bi bi-calendar-check"></i>
                    <span>Bookings</span>
                </a>
                <a class="menu-item" onclick="showSection('profile')">
                    <i class="bi bi-person"></i>
                    <span>Profile</span>
                </a>
                <a class="menu-item" onclick="showSection('settings')">
                    <i class="bi bi-gear"></i>
                    <span>Settings</span>
                </a>
            </div>
        </div>

        <!-- Content Area -->
        <div class="content-area">
            <button class="menu-toggle" onclick="toggleSidebar()">
                <i class="bi bi-list"></i>
            </button>

            <!-- Dashboard Overview Section -->
            <div id="dashboard-section" class="content-section active">
                <div class="section-header">
                    <h2>Welcome, {{ auth()->user()->name }}!</h2>
                </div>
                
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-card-icon">
                            <i class="bi bi-calendar-check"></i>
                        </div>
                        <div class="stat-card-title">Bookings</div>
                        <div class="stat-card-value">0</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-card-icon">
                            <i class="bi bi-star-fill"></i>
                        </div>
                        <div class="stat-card-title">Rating</div>
                        <div class="stat-card-value">{{ $trainer->rating ?? '0.00' }}</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-card-icon">
                            <i class="bi bi-currency-dollar"></i>
                        </div>
                        <div class="stat-card-title">Earnings</div>
                        <div class="stat-card-value">$0</div>
                    </div>
                </div>
                
                <div class="content-card">
                    <h3>Quick Actions</h3>
                    <p style="color: #cccccc; font-family: 'Poppins', sans-serif;">Complete your trainer profile to start receiving bookings.</p>
                    <button class="btn" onclick="showSection('profile')" style="background: var(--accent-green); color: #000; padding: 12px 30px; border-radius: 8px; border: none; font-weight: 600; margin-top: 15px; font-family: 'Poppins', sans-serif; cursor: pointer;">
                        Edit Profile
                    </button>
                </div>
            </div>

            <!-- Bookings Section -->
            <div id="bookings-section" class="content-section">
                <div class="section-header">
                    <h2>Bookings</h2>
                </div>
                
                <div class="content-card">
                    <h3>Your Bookings</h3>
                    <p style="color: #cccccc; font-family: 'Poppins', sans-serif;">No bookings yet. Once customers start booking your services, they will appear here.</p>
                </div>
            </div>

            <!-- Profile Section -->
            <div id="profile-section" class="content-section">
                <div class="section-header">
                    <h2>Profile</h2>
                </div>
                
                <div class="content-card">
                    <h3>Trainer Information</h3>
                    <div style="color: #cccccc; font-family: 'Poppins', sans-serif;">
                        <p><strong style="color: var(--accent-green);">Name:</strong> {{ auth()->user()->name }}</p>
                        <p><strong style="color: var(--accent-green);">Email:</strong> {{ auth()->user()->email }}</p>
                        @if($trainer)
                            <p><strong style="color: var(--accent-green);">Phone:</strong> {{ $trainer->phone ?? 'N/A' }}</p>
                            <p><strong style="color: var(--accent-green);">State:</strong> {{ $trainer->state ?? 'N/A' }}</p>
                            <p><strong style="color: var(--accent-green);">Area:</strong> {{ $trainer->area ?? 'N/A' }}</p>
                            <p><strong style="color: var(--accent-green);">Category:</strong> {{ $trainer->category ?? 'N/A' }}</p>
                            <p><strong style="color: var(--accent-green);">Status:</strong> 
                                <span style="background: rgba(0, 204, 102, 0.2); color: var(--accent-green); padding: 5px 12px; border-radius: 12px; font-size: 0.85rem; font-weight: 600;">
                                    {{ ucfirst($trainer->status ?? 'pending') }}
                                </span>
                            </p>
                        @endif
                    </div>
                    <button class="btn" style="background: var(--accent-green); color: #000; padding: 12px 30px; border-radius: 8px; border: none; font-weight: 600; margin-top: 20px; font-family: 'Poppins', sans-serif; cursor: pointer;">
                        Edit Profile
                    </button>
                </div>
            </div>

            <!-- Settings Section -->
            <div id="settings-section" class="content-section">
                <div class="section-header">
                    <h2>Settings</h2>
                </div>
                
                <div class="content-card">
                    <h3>Account Settings</h3>
                    <p style="color: #cccccc; font-family: 'Poppins', sans-serif;">Manage your account settings and preferences here.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Vite JS -->
    @vite(['resources/js/app.js'])
    
    <script>
        function showSection(sectionId) {
            // Hide all sections
            document.querySelectorAll('.content-section').forEach(section => {
                section.classList.remove('active');
            });
            
            // Show selected section
            document.getElementById(sectionId + '-section').classList.add('active');
            
            // Update active menu item
            document.querySelectorAll('.menu-item').forEach(item => {
                item.classList.remove('active');
            });
            event.target.closest('.menu-item').classList.add('active');
            
            // Close sidebar on mobile
            if (window.innerWidth <= 768) {
                document.getElementById('sidebar').classList.remove('open');
            }
        }
        
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
        }
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const menuToggle = document.querySelector('.menu-toggle');
            
            if (window.innerWidth <= 768 && 
                !sidebar.contains(event.target) && 
                !menuToggle.contains(event.target) &&
                sidebar.classList.contains('open')) {
                sidebar.classList.remove('open');
            }
        });
    </script>
</body>
</html>
