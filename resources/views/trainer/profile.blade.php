@extends('layouts.dashboard')

@section('title', 'My Profile - Trainer Dashboard')

@section('page-title', 'My Profile')

@section('sidebar-menu')
    @include('components.trainer-sidebar')
@endsection

@section('header-actions')
    <a href="{{ route('trainer.profile.edit') }}" class="btn dashboard-btn-primary">
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
                    @if($trainer && $trainer->profile_picture)
                        <img src="{{ asset('storage/' . $trainer->profile_picture) }}" 
                             alt="{{ auth()->user()->name }}" 
                             class="profile-avatar-img">
                    @else
                        <div class="profile-avatar-placeholder">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div class="profile-header-info">
                    <h2 class="profile-header-name">{{ auth()->user()->name }}</h2>
                    <p class="profile-header-email">{{ auth()->user()->email }}</p>
                    @if($trainer && $trainer->status)
                        <span class="profile-status-badge-simple">
                            {{ ucfirst($trainer->status) }}
                        </span>
                    @endif
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
                            {{ $trainer->phone ?? 'Not provided' }}
                        </div>
                    </div>

                    <div class="profile-detail-row">
                        <div class="profile-detail-label-simple">
                            <i class="bi bi-geo-alt"></i>
                            <span>Location</span>
                        </div>
                        <div class="profile-detail-value-simple">
                            {{ $trainer->area ?? 'N/A' }}, {{ ucfirst(str_replace('_', ' ', $trainer->state ?? 'N/A')) }}
                        </div>
                    </div>

                    <div class="profile-detail-row">
                        <div class="profile-detail-label-simple">
                            <i class="bi bi-tag"></i>
                            <span>Category</span>
                        </div>
                        <div class="profile-detail-value-simple">
                            <span class="profile-category-badge-simple">
                                {{ ucfirst(str_replace('_', ' ', $trainer->category ?? 'Not specified')) }}
                            </span>
                        </div>
                    </div>

                    <div class="profile-detail-row">
                        <div class="profile-detail-label-simple">
                            <i class="bi bi-star"></i>
                            <span>Rating</span>
                        </div>
                        <div class="profile-detail-value-simple">
                            <span class="profile-rating-simple">
                                <i class="bi bi-star-fill"></i> {{ number_format($trainer->rating ?? 0, 2) }}
                            </span>
                        </div>
                    </div>

                    <div class="profile-detail-row profile-salary-row">
                        <div class="profile-detail-label-simple">
                            <i class="bi bi-wallet2"></i>
                            <span>Salary</span>
                        </div>
                        <div class="profile-detail-value-simple">
                            @if($trainer->salary)
                                <div class="profile-salary-amount-simple">RM {{ number_format($trainer->salary, 2) }}</div>
                                <div class="profile-salary-note-simple">Set by Admin</div>
                            @else
                                <span class="profile-salary-not-set-simple">Not set yet</span>
                            @endif
                        </div>
                    </div>

                    @if($trainer->qualification_file)
                    <div class="profile-detail-row">
                        <div class="profile-detail-label-simple">
                            <i class="bi bi-file-earmark-pdf"></i>
                            <span>Qualification File</span>
                        </div>
                        <div class="profile-detail-value-simple">
                            <a href="{{ asset('storage/' . $trainer->qualification_file) }}" 
                               target="_blank" 
                               class="profile-qualification-link-simple">
                                <i class="bi bi-download"></i> Download
                            </a>
                        </div>
                    </div>
                    @endif

                    @if($trainer->latitude && $trainer->longitude)
                    <div class="profile-detail-row">
                        <div class="profile-detail-label-simple">
                            <i class="bi bi-geo"></i>
                            <span>Location Coordinates</span>
                        </div>
                        <div class="profile-detail-value-simple">
                            <div class="profile-coordinates-simple">
                                <span>Lat: {{ $trainer->latitude }}, Long: {{ $trainer->longitude }}</span>
                                <a href="https://www.google.com/maps?q={{ $trainer->latitude }},{{ $trainer->longitude }}" 
                                   target="_blank" 
                                   class="profile-direction-link-simple">
                                    <i class="bi bi-compass"></i> Get Direction
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
