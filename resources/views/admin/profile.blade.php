@extends('layouts.dashboard')

@section('title', 'My Profile - Admin Dashboard')

@section('page-title', 'My Profile')

@section('sidebar-menu')
    @include('components.admin-sidebar')
@endsection

@section('header-actions')
    <a href="{{ route('admin.profile.edit') }}" class="btn dashboard-btn-primary">
        <i class="bi bi-pencil-square me-2"></i>Edit Profile
    </a>
@endsection

@section('content')
<div class="profile-single-container">
    <div class="card dashboard-card profile-unified-card">
        <div class="card-body dashboard-card-body">
            <!-- Profile Header -->
            <div class="profile-header-section">
                <div class="profile-avatar-section">
                    @if($staff && $staff->profile_picture)
                        <img src="{{ asset('storage/' . $staff->profile_picture) }}" 
                             alt="{{ $user->name }}" 
                             class="profile-avatar-img">
                    @else
                        <div class="profile-avatar-placeholder">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div class="profile-header-info">
                    <h2 class="profile-header-name">{{ $user->name }}</h2>
                    <p class="profile-header-email">{{ $user->email }}</p>
                    <span class="profile-status-badge-simple">
                        Administrator
                    </span>
                </div>
            </div>

            <!-- Profile Details -->
            <div class="profile-details-section">
                <div class="profile-details-list">
                    <div class="profile-detail-row">
                        <div class="profile-detail-label-simple">
                            <i class="bi bi-telephone"></i>
                            <span>Phone Number</span>
                        </div>
                        <div class="profile-detail-value-simple">
                            {{ $staff->phone ?? 'Not provided' }}
                        </div>
                    </div>

                    <div class="profile-detail-row">
                        <div class="profile-detail-label-simple">
                            <i class="bi bi-building"></i>
                            <span>Department</span>
                        </div>
                        <div class="profile-detail-value-simple">
                            {{ $staff->department ?? 'Not provided' }}
                        </div>
                    </div>

                    <div class="profile-detail-row">
                        <div class="profile-detail-label-simple">
                            <i class="bi bi-briefcase"></i>
                            <span>Position</span>
                        </div>
                        <div class="profile-detail-value-simple">
                            {{ $staff->position ?? 'Not provided' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

