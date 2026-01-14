@extends('layouts.dashboard')

@section('title', 'Trainer Approvals - Admin Dashboard')

@section('page-title', 'Trainer Approvals')

@section('sidebar-menu')
    @include('components.admin-sidebar')
@endsection

@push('styles')
<style>
    .dashboard-title {
        background: linear-gradient(135deg, #ffffff 0%, var(--accent-green) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
</style>
@endpush

@section('content')
<!-- Summary Cards -->
<div class="row g-2 mb-3">
    <div class="col-md-6">
        <div class="approval-summary-card pending">
            <div class="approval-summary-icon">
                <i class="bi bi-clock-history"></i>
            </div>
            <div class="approval-summary-content">
                <h3 class="approval-summary-title">Pending Approvals</h3>
                <p class="approval-summary-count">{{ $pendingTrainers->count() }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="approval-summary-card active">
            <div class="approval-summary-icon">
                <i class="bi bi-check-circle"></i>
            </div>
            <div class="approval-summary-content">
                <h3 class="approval-summary-title">Active Trainers</h3>
                <p class="approval-summary-count">{{ $activeTrainers->count() }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Pending Trainers -->
<div class="card mb-3 dashboard-card">
    <div class="card-header dashboard-card-header">
        <h2 class="dashboard-card-title">
            <i class="bi bi-person-check me-2"></i>
            Pending Trainer Approvals
            <span class="approval-count-badge">{{ $pendingTrainers->count() }}</span>
        </h2>
    </div>
    <div class="card-body dashboard-card-body">
        @if($pendingTrainers->count() > 0)
            <div class="trainer-list">
                @foreach($pendingTrainers as $trainer)
                    <div class="trainer-card pending-card">
                        <div class="trainer-card-header">
                            <div class="trainer-avatar">
                                @if($trainer->profile_picture)
                                    <img src="{{ asset('storage/' . $trainer->profile_picture) }}" 
                                         alt="{{ $trainer->user->name }}">
                                @else
                                    {{ strtoupper(substr($trainer->user->name, 0, 1)) }}
                                @endif
                            </div>
                            <div class="trainer-info">
                                <h3 class="trainer-name">{{ $trainer->user->name }}</h3>
                                <p class="trainer-email">
                                    <i class="bi bi-envelope me-1"></i>{{ $trainer->user->email }}
                                </p>
                            </div>
                            <div class="trainer-date">
                                <i class="bi bi-calendar3 me-1"></i>
                                <span>{{ $trainer->created_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                        <div class="trainer-card-footer">
                            <form action="{{ route('admin.approve.trainer', $trainer->id) }}" method="POST" class="d-inline me-2">
                                @csrf
                                <button type="submit" class="btn trainer-action-btn approve-btn">
                                    <i class="bi bi-check-lg me-1"></i>Approve
                                </button>
                            </form>
                            <form action="{{ route('admin.reject.trainer', $trainer->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn trainer-action-btn reject-btn">
                                    <i class="bi bi-x-lg me-1"></i>Reject
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <i class="bi bi-inbox empty-state-icon"></i>
                <p class="empty-state-text">No pending trainer approvals.</p>
            </div>
        @endif
    </div>
</div>

<!-- Active Trainers -->
<div class="card dashboard-card">
    <div class="card-header dashboard-card-header">
        <h2 class="dashboard-card-title">
            <i class="bi bi-people me-2"></i>
            Active Trainers
            <span class="approval-count-badge">{{ $activeTrainers->count() }}</span>
        </h2>
    </div>
    <div class="card-body dashboard-card-body">
        @if($activeTrainers->count() > 0)
            <div class="trainer-list">
                @foreach($activeTrainers as $trainer)
                    <div class="trainer-card active-card">
                        <div class="trainer-card-header">
                            <div class="trainer-avatar active">
                                @if($trainer->profile_picture)
                                    <img src="{{ asset('storage/' . $trainer->profile_picture) }}" 
                                         alt="{{ $trainer->user->name }}">
                                @else
                                    {{ strtoupper(substr($trainer->user->name, 0, 1)) }}
                                @endif
                            </div>
                            <div class="trainer-info">
                                <h3 class="trainer-name">{{ $trainer->user->name }}</h3>
                                <p class="trainer-email">
                                    <i class="bi bi-envelope me-1"></i>{{ $trainer->user->email }}
                                </p>
                            </div>
                            <div class="trainer-status-info">
                                <span class="badge badge-success-large">
                                    <i class="bi bi-check-circle me-1"></i>Active
                                </span>
                                <div class="trainer-date">
                                    <i class="bi bi-calendar-check me-1"></i>
                                    <span>Approved: {{ $trainer->updated_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <i class="bi bi-inbox empty-state-icon"></i>
                <p class="empty-state-text">No active trainers.</p>
            </div>
        @endif
    </div>
</div>
@endsection
