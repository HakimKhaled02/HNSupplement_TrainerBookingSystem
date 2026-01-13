@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div class="customer-edit-profile-page-container">
    <div class="container" style="padding-top: 2rem; padding-bottom: 3rem;">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="customer-edit-profile-card">
                    <div class="customer-edit-profile-header">
                        <h2 class="customer-edit-profile-title">
                            <i class="bi bi-pencil-square me-2"></i>Edit Profile
                        </h2>
                        <a href="{{ route('customer.profile') }}" class="btn customer-edit-profile-back-btn">
                            <i class="bi bi-arrow-left me-2"></i>Back
                        </a>
                    </div>

                    <form action="{{ route('customer.profile.update') }}" method="POST" enctype="multipart/form-data" class="customer-edit-profile-form">
                        @csrf

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row g-4">
                            <!-- Left Column -->
                            <div class="col-md-6">
                                <div class="customer-form-group">
                                    <label for="name" class="customer-form-label">
                                        <i class="bi bi-person me-2"></i>Full Name
                                    </label>
                                    <input type="text" 
                                           id="name" 
                                           name="name" 
                                           class="customer-form-input @error('name') is-invalid @enderror" 
                                           value="{{ old('name', $user->name) }}" 
                                           placeholder="Enter your name"
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="customer-form-group">
                                    <label for="email" class="customer-form-label">
                                        <i class="bi bi-envelope me-2"></i>Email Address
                                    </label>
                                    <input type="email" 
                                           id="email" 
                                           name="email" 
                                           class="customer-form-input @error('email') is-invalid @enderror" 
                                           value="{{ old('email', $user->email) }}" 
                                           placeholder="Enter your email"
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="customer-form-group">
                                    <label for="phone" class="customer-form-label">
                                        <i class="bi bi-telephone me-2"></i>Phone Number
                                    </label>
                                    <input type="text" 
                                           id="phone" 
                                           name="phone" 
                                           class="customer-form-input @error('phone') is-invalid @enderror" 
                                           value="{{ old('phone', $user->customer->phone ?? '') }}" 
                                           placeholder="e.g., 0123456789">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="col-md-6">
                                <div class="customer-form-group">
                                    <label for="profile_picture" class="customer-form-label">
                                        <i class="bi bi-image me-2"></i>Profile Picture
                                    </label>
                                    <input type="file" 
                                           id="profile_picture" 
                                           name="profile_picture" 
                                           class="customer-form-input" 
                                           accept="image/*"
                                           onchange="previewImage(this, 'profile-preview')">
                                    @if($user->customer && $user->customer->profile_picture)
                                        <p class="customer-form-help-text">Current: <a href="{{ asset('storage/' . $user->customer->profile_picture) }}" target="_blank">View current picture</a></p>
                                    @endif
                                    <div id="profile-preview" class="customer-image-preview"></div>
                                </div>

                                <div class="customer-form-group">
                                    <label for="password" class="customer-form-label">
                                        <i class="bi bi-lock me-2"></i>New Password (leave blank to keep current password)
                                    </label>
                                    <input type="password" 
                                           id="password" 
                                           name="password" 
                                           class="customer-form-input @error('password') is-invalid @enderror" 
                                           placeholder="Enter new password">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="customer-form-group">
                                    <label for="password_confirmation" class="customer-form-label">
                                        <i class="bi bi-lock-fill me-2"></i>Confirm New Password
                                    </label>
                                    <input type="password" 
                                           id="password_confirmation" 
                                           name="password_confirmation" 
                                           class="customer-form-input" 
                                           placeholder="Confirm new password">
                                </div>
                            </div>
                        </div>

                        <div class="customer-form-actions">
                            <a href="{{ route('customer.profile') }}" class="btn customer-form-cancel-btn">
                                Cancel
                            </a>
                            <button type="submit" class="btn customer-form-submit-btn">
                                <i class="bi bi-check-circle me-2"></i>Update Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    preview.innerHTML = '';
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.style.maxWidth = '200px';
            img.style.maxHeight = '200px';
            img.style.borderRadius = '8px';
            img.style.marginTop = '10px';
            img.style.border = '2px solid rgba(0, 204, 102, 0.3)';
            preview.appendChild(img);
        };
        
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
