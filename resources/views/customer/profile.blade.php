@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="customer-profile-page-container-new">
    <div class="container" style="padding-top: 2rem; padding-bottom: 3rem;">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="customer-profile-card-new">
                    <div class="customer-profile-header-new">
                        <div class="customer-profile-header-content">
                            <div class="customer-profile-avatar-wrapper-new">
                                @if($user->customer && $user->customer->profile_picture)
                                    <img src="{{ asset('storage/' . $user->customer->profile_picture) }}" 
                                         alt="{{ $user->name }}" 
                                         class="customer-profile-avatar-new">
                                @else
                                    <div class="customer-profile-avatar-placeholder-new">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <div class="customer-profile-header-info-new">
                                <h2 class="customer-profile-title-new">{{ $user->name }}</h2>
                                <p class="customer-profile-subtitle-new">{{ $user->email }}</p>
                            </div>
                        </div>
                        <a href="{{ route('customer.profile.edit') }}" class="btn customer-profile-edit-btn-new">
                            <i class="bi bi-pencil-square me-2"></i>Edit Profile
                        </a>
                    </div>

                    <div class="customer-profile-details-section-new">
                        <h3 class="customer-profile-section-title-new">Profile Information</h3>
                        <div class="customer-profile-info-new">
                            <div class="customer-profile-info-row-new">
                                <span class="customer-profile-info-label-new">Phone Number:</span>
                                <span class="customer-profile-info-value-new">
                                    {{ $user->customer->phone ?? 'Not provided' }}
                                </span>
                            </div>
                            <div class="customer-profile-info-row-new">
                                <span class="customer-profile-info-label-new">My Bookings:</span>
                                <span class="customer-profile-info-value-new">
                                    <a href="{{ route('customer.bookings') }}" class="customer-profile-link-new">
                                        View All Bookings
                                        <i class="bi bi-arrow-right ms-2"></i>
                                    </a>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
