<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard - HN Supplement')</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/jpeg" href="{{ asset('images/logo.jpg') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.jpg') }}">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- Vite CSS -->
    @vite(['resources/css/app.css'])
    
    @stack('styles')
</head>
<body class="dashboard-body">
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="dashboard-sidebar">
            <div class="sidebar-header">
                <a href="{{ route('home') }}" class="sidebar-brand">
                    <img src="{{ asset('images/logo.jpg') }}" alt="HN Supplement Logo" height="40" class="sidebar-logo">
                    <span class="sidebar-brand-text">HN SUPPLEMENT</span>
                </a>
            </div>
            
            <nav class="sidebar-nav">
                @yield('sidebar-menu')
            </nav>
            
            <div class="sidebar-footer">
                <div class="sidebar-user">
                    <div class="sidebar-user-avatar">
                        @auth
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        @endauth
                    </div>
                    <div class="sidebar-user-info">
                        <div class="sidebar-user-name">@auth{{ auth()->user()->name }}@endauth</div>
                        <div class="sidebar-user-role">@auth{{ ucfirst(auth()->user()->role) }}@endauth</div>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="sidebar-logout-form">
                    @csrf
                    <button type="submit" class="sidebar-logout-btn">
                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                    </button>
                </form>
            </div>
        </aside>
        
        <!-- Main Content -->
        <main class="dashboard-main">
            <div class="dashboard-header">
                <h1 class="dashboard-title">@yield('page-title', 'Dashboard')</h1>
                <div class="dashboard-header-actions">
                    @yield('header-actions')
                </div>
            </div>
            
            <div class="dashboard-content">
                @if(session('success'))
                    <div class="alert alert-success dashboard-alert">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger dashboard-alert">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger dashboard-alert">
                        <ul style="margin: 0; padding-left: 20px;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Vite JS -->
    @vite(['resources/js/app.js'])
    
    @stack('scripts')
</body>
</html>

