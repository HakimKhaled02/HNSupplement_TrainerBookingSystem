@extends('layouts.dashboard')

@section('title', 'Edit Profile - Admin Dashboard')

@section('page-title', 'Edit Profile')

@section('sidebar-menu')
    @include('components.admin-sidebar')
@endsection

@section('content')
<div class="row g-4">
    <div class="col-md-12">
        <div class="card dashboard-card">
            <div class="card-header dashboard-card-header">
                <h2 class="dashboard-card-title">
                    <i class="bi bi-pencil-square me-2"></i>Edit Profile Information
                </h2>
            </div>
            <div class="card-body dashboard-card-body">
                @if (session('success'))
                    <div class="alert alert-success dashboard-alert">
                        {{ session('success') }}
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

                <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row g-4">
                        <!-- Left Column -->
                        <div class="col-md-6">
                            <div class="form-group-profile">
                                <label for="name" class="form-label-profile">
                                    <i class="bi bi-person me-2"></i>Name
                                </label>
                                <input type="text" 
                                       id="name" 
                                       name="name" 
                                       class="form-input-profile" 
                                       value="{{ old('name', $user->name ?? '') }}"
                                       placeholder="Enter your name"
                                       required>
                            </div>

                            <div class="form-group-profile">
                                <label for="email" class="form-label-profile">
                                    <i class="bi bi-envelope me-2"></i>Email
                                </label>
                                <input type="email" 
                                       id="email" 
                                       name="email" 
                                       class="form-input-profile" 
                                       value="{{ old('email', $user->email ?? '') }}"
                                       placeholder="Enter your email"
                                       required>
                            </div>

                            <div class="form-group-profile">
                                <label for="phone" class="form-label-profile">
                                    <i class="bi bi-telephone me-2"></i>Phone Number
                                </label>
                                <input type="text" 
                                       id="phone" 
                                       name="phone" 
                                       class="form-input-profile" 
                                       value="{{ old('phone', $staff->phone ?? '') }}"
                                       placeholder="Enter phone number">
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="col-md-6">
                            <div class="form-group-profile">
                                <label for="profile_picture" class="form-label-profile">
                                    <i class="bi bi-image me-2"></i>Profile Picture
                                </label>
                                <input type="file" 
                                       id="profile_picture" 
                                       name="profile_picture" 
                                       class="form-input-profile" 
                                       accept="image/*"
                                       onchange="previewImage(this, 'profile-preview')">
                                @if($staff && $staff->profile_picture)
                                    <p class="form-help-text">Current: <a href="{{ asset('storage/' . $staff->profile_picture) }}" target="_blank">View current picture</a></p>
                                @endif
                                <div id="profile-preview" class="image-preview-profile"></div>
                            </div>

                            <div class="form-group-profile">
                                <label for="department" class="form-label-profile">
                                    <i class="bi bi-building me-2"></i>Department
                                </label>
                                <input type="text" 
                                       id="department" 
                                       name="department" 
                                       class="form-input-profile" 
                                       value="{{ old('department', $staff->department ?? '') }}"
                                       placeholder="Enter department">
                            </div>

                            <div class="form-group-profile">
                                <label for="position" class="form-label-profile">
                                    <i class="bi bi-briefcase me-2"></i>Position
                                </label>
                                <input type="text" 
                                       id="position" 
                                       name="position" 
                                       class="form-input-profile" 
                                       value="{{ old('position', $staff->position ?? '') }}"
                                       placeholder="Enter position">
                            </div>
                        </div>
                    </div>

                    <div class="form-actions-profile">
                        <a href="{{ route('admin.profile') }}" class="btn btn-cancel-profile">
                            <i class="bi bi-x-lg me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn dashboard-btn-primary">
                            <i class="bi bi-check-lg me-2"></i>Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = '<img src="' + e.target.result + '" style="max-width: 150px; max-height: 150px; border-radius: 8px; margin-top: 10px; border: 2px solid var(--accent-green);">';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection

