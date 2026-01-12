@extends('layouts.dashboard')

@section('title', 'Admin Dashboard')

@section('page-title', 'Admin Dashboard')

@section('sidebar-menu')
    @include('components.admin-sidebar')
@endsection

@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card dashboard-stat-card">
            <div class="dashboard-stat-icon">
                <i class="bi bi-person-check"></i>
            </div>
            <h3 class="dashboard-stat-title">Pending Approvals</h3>
            <p class="dashboard-stat-value">
                {{ \App\Trainer::where('status', 'pending')->count() }}
            </p>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card dashboard-stat-card">
            <div class="dashboard-stat-icon">
                <i class="bi bi-people"></i>
            </div>
            <h3 class="dashboard-stat-title">Active Trainers</h3>
            <p class="dashboard-stat-value">
                {{ \App\Trainer::where('status', 'active')->count() }}
            </p>
        </div>
    </div>
</div>

<div class="card dashboard-card">
    <div class="card-body dashboard-card-body">
        <h2 class="dashboard-card-title mb-3">Welcome to Admin Dashboard</h2>
        <p class="dashboard-text mb-3">Manage trainer approvals, view statistics, and oversee the platform from here.</p>
        <a href="{{ route('admin.approvals') }}" class="btn dashboard-btn-primary">
            <i class="bi bi-person-check me-2"></i>Go to Approvals
        </a>
    </div>
</div>
@endsection
