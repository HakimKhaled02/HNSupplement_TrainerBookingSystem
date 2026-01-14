@extends('layouts.dashboard')

@section('title', 'Edit Profile - Trainer Dashboard')

@section('page-title', 'Edit Profile')

@section('sidebar-menu')
    @include('components.trainer-sidebar')
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
                @if ($errors->any())
                    <div class="alert alert-danger dashboard-alert">
                        <ul style="margin: 0; padding-left: 20px;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('trainer.profile.update') }}" method="POST" enctype="multipart/form-data">
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
                                       value="{{ old('name', auth()->user()->name ?? '') }}"
                                       placeholder="Enter your name"
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
                                       value="{{ old('phone', $trainer->phone ?? '') }}"
                                       placeholder="Enter phone number">
                            </div>

                            <div class="form-group-profile">
                                <label for="category" class="form-label-profile">
                                    <i class="bi bi-tag me-2"></i>Trainer Category
                                </label>
                                <select id="category" name="category" class="form-input-profile">
                                    <option value="">Select Category</option>
                                    <option value="strength" {{ old('category', $trainer->category ?? '') === 'strength' ? 'selected' : '' }}>Strength Training</option>
                                    <option value="cardio" {{ old('category', $trainer->category ?? '') === 'cardio' ? 'selected' : '' }}>Cardio & Fitness</option>
                                    <option value="yoga" {{ old('category', $trainer->category ?? '') === 'yoga' ? 'selected' : '' }}>Yoga & Wellness</option>
                                    <option value="pilates" {{ old('category', $trainer->category ?? '') === 'pilates' ? 'selected' : '' }}>Pilates</option>
                                    <option value="boxing" {{ old('category', $trainer->category ?? '') === 'boxing' ? 'selected' : '' }}>Boxing & MMA</option>
                                    <option value="nutrition" {{ old('category', $trainer->category ?? '') === 'nutrition' ? 'selected' : '' }}>Nutrition</option>
                                    <option value="other" {{ old('category', $trainer->category ?? '') === 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>

                            <div class="form-group-profile">
                                <label for="state" class="form-label-profile">
                                    <i class="bi bi-geo-alt me-2"></i>State
                                </label>
                                <select id="state" name="state" class="form-input-profile">
                                    <option value="">Select State</option>
                                    <option value="selangor" {{ old('state', $trainer->state ?? '') === 'selangor' ? 'selected' : '' }}>Selangor</option>
                                    <option value="kuala_lumpur" {{ old('state', $trainer->state ?? '') === 'kuala_lumpur' ? 'selected' : '' }}>Kuala Lumpur</option>
                                    <option value="johor" {{ old('state', $trainer->state ?? '') === 'johor' ? 'selected' : '' }}>Johor</option>
                                    <option value="penang" {{ old('state', $trainer->state ?? '') === 'penang' ? 'selected' : '' }}>Penang</option>
                                    <option value="sabah" {{ old('state', $trainer->state ?? '') === 'sabah' ? 'selected' : '' }}>Sabah</option>
                                    <option value="sarawak" {{ old('state', $trainer->state ?? '') === 'sarawak' ? 'selected' : '' }}>Sarawak</option>
                                    <option value="melaka" {{ old('state', $trainer->state ?? '') === 'melaka' ? 'selected' : '' }}>Melaka</option>
                                    <option value="negeri_sembilan" {{ old('state', $trainer->state ?? '') === 'negeri_sembilan' ? 'selected' : '' }}>Negeri Sembilan</option>
                                    <option value="perak" {{ old('state', $trainer->state ?? '') === 'perak' ? 'selected' : '' }}>Perak</option>
                                    <option value="kedah" {{ old('state', $trainer->state ?? '') === 'kedah' ? 'selected' : '' }}>Kedah</option>
                                    <option value="pahang" {{ old('state', $trainer->state ?? '') === 'pahang' ? 'selected' : '' }}>Pahang</option>
                                    <option value="terengganu" {{ old('state', $trainer->state ?? '') === 'terengganu' ? 'selected' : '' }}>Terengganu</option>
                                    <option value="kelantan" {{ old('state', $trainer->state ?? '') === 'kelantan' ? 'selected' : '' }}>Kelantan</option>
                                    <option value="perlis" {{ old('state', $trainer->state ?? '') === 'perlis' ? 'selected' : '' }}>Perlis</option>
                                    <option value="labuan" {{ old('state', $trainer->state ?? '') === 'labuan' ? 'selected' : '' }}>Labuan</option>
                                    <option value="putrajaya" {{ old('state', $trainer->state ?? '') === 'putrajaya' ? 'selected' : '' }}>Putrajaya</option>
                                </select>
                            </div>

                            <div class="form-group-profile">
                                <label for="area" class="form-label-profile">
                                    <i class="bi bi-geo me-2"></i>Area
                                </label>
                                <input type="text" 
                                       id="area" 
                                       name="area" 
                                       class="form-input-profile" 
                                       value="{{ old('area', $trainer->area ?? '') }}"
                                       placeholder="Enter your area (e.g., Petaling Jaya)">
                            </div>

                            <div class="form-group-profile">
                                <label for="current_password" class="form-label-profile">
                                    <i class="bi bi-lock me-2"></i>Current Password
                                </label>
                                <input type="password" 
                                       id="current_password" 
                                       name="current_password" 
                                       class="form-input-profile" 
                                       placeholder="Enter current password to change password">
                                <small class="form-help-text">Leave blank if you don't want to change password</small>
                            </div>

                            <div class="form-group-profile">
                                <label for="password" class="form-label-profile">
                                    <i class="bi bi-key me-2"></i>New Password
                                </label>
                                <input type="password" 
                                       id="password" 
                                       name="password" 
                                       class="form-input-profile" 
                                       placeholder="Enter new password">
                                <small class="form-help-text">Minimum 8 characters</small>
                            </div>

                            <div class="form-group-profile">
                                <label for="password_confirmation" class="form-label-profile">
                                    <i class="bi bi-key-fill me-2"></i>Confirm New Password
                                </label>
                                <input type="password" 
                                       id="password_confirmation" 
                                       name="password_confirmation" 
                                       class="form-input-profile" 
                                       placeholder="Confirm new password">
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
                                @if($trainer && $trainer->profile_picture)
                                    <p class="form-help-text">Current: <a href="{{ asset('storage/' . $trainer->profile_picture) }}" target="_blank">View current picture</a></p>
                                @endif
                                <div id="profile-preview" class="image-preview-profile"></div>
                            </div>

                            <div class="form-group-profile">
                                <label class="form-label-profile">Location (Auto-detected)</label>
                                <div class="location-group">
                                    <button type="button" id="get-location" class="location-btn-profile">
                                        <i class="bi bi-geo-alt me-2"></i>Get My Location
                                    </button>
                                    <span id="location-status" class="location-status-text"></span>
                                </div>
                                <div class="location-coordinates">
                                    <div class="coordinate-display">
                                        <span class="coordinate-label">Latitude:</span>
                                        <span id="latitude-display" class="coordinate-value">{{ $trainer->latitude ?? 'Not set' }}</span>
                                    </div>
                                    <div class="coordinate-display">
                                        <span class="coordinate-label">Longitude:</span>
                                        <span id="longitude-display" class="coordinate-value">{{ $trainer->longitude ?? 'Not set' }}</span>
                                    </div>
                                </div>
                                <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude', $trainer->latitude ?? '') }}">
                                <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude', $trainer->longitude ?? '') }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-actions-profile">
                        <a href="{{ route('trainer.profile') }}" class="btn btn-cancel-profile">
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

document.getElementById('get-location').addEventListener('click', function() {
    const status = document.getElementById('location-status');
    const latDisplay = document.getElementById('latitude-display');
    const lngDisplay = document.getElementById('longitude-display');
    const latInput = document.getElementById('latitude');
    const lngInput = document.getElementById('longitude');
    
    status.textContent = 'Getting location...';
    status.style.color = '#ffc107';
    
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                
                latInput.value = lat;
                lngInput.value = lng;
                latDisplay.textContent = lat.toFixed(8);
                lngDisplay.textContent = lng.toFixed(8);
                
                status.textContent = 'Location detected!';
                status.style.color = 'var(--accent-green)';
            },
            function(error) {
                status.textContent = 'Unable to get location. Please allow location access.';
                status.style.color = '#ff6b6b';
            }
        );
    } else {
        status.textContent = 'Geolocation is not supported by your browser.';
        status.style.color = '#ff6b6b';
    }
});
</script>
@endsection
