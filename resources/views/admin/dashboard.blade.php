<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard - Trainer Approvals</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/jpeg" href="{{ asset('images/logo.jpg') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.jpg') }}">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- Vite CSS -->
    @vite(['resources/css/app.css'])
</head>
<body style="background-color: #000000; color: #ffffff; font-family: 'Poppins', sans-serif;">
    <!-- Admin Dashboard Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background: rgba(26, 26, 26, 0.95) !important; backdrop-filter: blur(10px); border-bottom: 1px solid rgba(0, 204, 102, 0.3); padding: 1rem 0;">
        <div class="container-fluid px-4">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('admin.dashboard') }}">
                <img src="{{ asset('images/logo.jpg') }}" alt="HN Supplement Logo" height="45" class="me-3" style="border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 204, 102, 0.2);">
                <span>HN SUPPLEMENT</span>
            </a>
            
            <div class="ms-auto d-flex align-items-center gap-3">
                @php
                    $user = auth()->user();
                @endphp
                <div class="profile-dropdown">
                    <button class="profile-trigger" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="profile-avatar-text">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
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
                            <a class="dropdown-item" href="#">
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

    <div class="container-fluid" style="padding: 100px 20px 80px; background-color: #000000; min-height: 100vh;">
    <div class="container">
        <h1 class="mb-4" style="color: var(--accent-green); font-size: 2.5rem; font-weight: 700; font-family: 'Poppins', sans-serif;">Admin Dashboard</h1>

        @if(session('success'))
            <div class="alert alert-success" style="background: rgba(0, 204, 102, 0.1); border: 1px solid var(--accent-green); color: var(--accent-green); padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger" style="background: rgba(220, 53, 69, 0.1); border: 1px solid #dc3545; color: #ff6b6b; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                {{ session('error') }}
            </div>
        @endif

        <!-- Pending Trainers -->
        <div class="card mb-4" style="background-color: #1a1a1a; border: 1px solid rgba(0, 204, 102, 0.3); border-radius: 15px;">
            <div class="card-header" style="background-color: #2d2d2d; border-bottom: 1px solid rgba(0, 204, 102, 0.3); padding: 20px;">
                <h2 style="color: var(--accent-green); margin: 0; font-size: 1.8rem; font-weight: 700; font-family: 'Poppins', sans-serif;">
                    Pending Trainer Approvals ({{ $pendingTrainers->count() }})
                </h2>
            </div>
            <div class="card-body" style="padding: 20px;">
                @if($pendingTrainers->count() > 0)
                    <div class="table-responsive">
                        <table class="table" style="color: #ffffff; margin: 0;">
                            <thead>
                                <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
                                    <th style="padding: 15px; font-weight: 600; font-family: 'Poppins', sans-serif;">Name</th>
                                    <th style="padding: 15px; font-weight: 600; font-family: 'Poppins', sans-serif;">Email</th>
                                    <th style="padding: 15px; font-weight: 600; font-family: 'Poppins', sans-serif;">Registered</th>
                                    <th style="padding: 15px; font-weight: 600; font-family: 'Poppins', sans-serif;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingTrainers as $trainer)
                                    <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.05);">
                                        <td style="padding: 15px; font-family: 'Poppins', sans-serif;">{{ $trainer->user->name }}</td>
                                        <td style="padding: 15px; font-family: 'Poppins', sans-serif;">{{ $trainer->user->email }}</td>
                                        <td style="padding: 15px; font-family: 'Poppins', sans-serif;">{{ $trainer->created_at->format('M d, Y') }}</td>
                                        <td style="padding: 15px;">
                                            <form action="{{ route('admin.approve.trainer', $trainer->id) }}" method="POST" style="display: inline-block; margin-right: 10px;">
                                                @csrf
                                                <button type="submit" class="btn btn-success" style="background: var(--accent-green); color: #000; border: none; padding: 8px 20px; border-radius: 6px; font-weight: 600; cursor: pointer; font-family: 'Poppins', sans-serif;">
                                                    Approve
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.reject.trainer', $trainer->id) }}" method="POST" style="display: inline-block;">
                                                @csrf
                                                <button type="submit" class="btn btn-danger" style="background: #dc3545; color: #fff; border: none; padding: 8px 20px; border-radius: 6px; font-weight: 600; cursor: pointer; font-family: 'Poppins', sans-serif;">
                                                    Reject
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p style="color: #cccccc; text-align: center; padding: 40px; font-family: 'Poppins', sans-serif;">No pending trainer approvals.</p>
                @endif
            </div>
        </div>

        <!-- Active Trainers -->
        <div class="card" style="background-color: #1a1a1a; border: 1px solid rgba(0, 204, 102, 0.3); border-radius: 15px;">
            <div class="card-header" style="background-color: #2d2d2d; border-bottom: 1px solid rgba(0, 204, 102, 0.3); padding: 20px;">
                <h2 style="color: var(--accent-green); margin: 0; font-size: 1.8rem; font-weight: 700; font-family: 'Poppins', sans-serif;">
                    Active Trainers ({{ $activeTrainers->count() }})
                </h2>
            </div>
            <div class="card-body" style="padding: 20px;">
                @if($activeTrainers->count() > 0)
                    <div class="table-responsive">
                        <table class="table" style="color: #ffffff; margin: 0;">
                            <thead>
                                <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
                                    <th style="padding: 15px; font-weight: 600; font-family: 'Poppins', sans-serif;">Name</th>
                                    <th style="padding: 15px; font-weight: 600; font-family: 'Poppins', sans-serif;">Email</th>
                                    <th style="padding: 15px; font-weight: 600; font-family: 'Poppins', sans-serif;">Status</th>
                                    <th style="padding: 15px; font-weight: 600; font-family: 'Poppins', sans-serif;">Approved</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($activeTrainers as $trainer)
                                    <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.05);">
                                        <td style="padding: 15px; font-family: 'Poppins', sans-serif;">{{ $trainer->user->name }}</td>
                                        <td style="padding: 15px; font-family: 'Poppins', sans-serif;">{{ $trainer->user->email }}</td>
                                        <td style="padding: 15px; font-family: 'Poppins', sans-serif;">
                                            <span style="background: rgba(0, 204, 102, 0.2); color: var(--accent-green); padding: 5px 12px; border-radius: 12px; font-size: 0.85rem; font-weight: 600;">
                                                {{ ucfirst($trainer->status) }}
                                            </span>
                                        </td>
                                        <td style="padding: 15px; font-family: 'Poppins', sans-serif;">{{ $trainer->updated_at->format('M d, Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p style="color: #cccccc; text-align: center; padding: 40px; font-family: 'Poppins', sans-serif;">No active trainers.</p>
                @endif
            </div>
        </div>
    </div>
</div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Vite JS -->
    @vite(['resources/js/app.js'])
</body>
</html>
