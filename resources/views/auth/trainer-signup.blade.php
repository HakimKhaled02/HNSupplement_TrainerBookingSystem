@extends('layouts.app')

@section('title', 'Trainer Registration - Book Your Trainer')

@section('content')
<div class="auth-container">
    <div class="auth-wrapper-wide">
        <div class="auth-card-wide">
            <div class="auth-header">
                <h2 class="auth-title">Become a Trainer</h2>
                <p class="auth-subtitle">Join our platform and help others achieve their fitness goals.</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger" style="background: rgba(220, 53, 69, 0.1); border: 1px solid rgba(220, 53, 69, 0.3); color: #ff6b6b; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-family: 'Poppins', sans-serif;">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form class="auth-form" method="POST" action="{{ route('trainer.signup.post') }}" enctype="multipart/form-data">
                @csrf
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="name" class="form-label">Full Name</label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            class="form-input" 
                            placeholder="Enter your full name"
                            value="{{ old('name') }}"
                            required
                            autofocus
                        >
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            class="form-input" 
                            placeholder="Enter your email"
                            value="{{ old('email') }}"
                            required
                        >
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input 
                            type="tel" 
                            id="phone" 
                            name="phone" 
                            class="form-input" 
                            placeholder="Enter your phone number"
                            value="{{ old('phone') }}"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="category" class="form-label">Trainer Category</label>
                        <select id="category" name="category" class="form-input" required>
                            <option value="">Select Category</option>
                            <option value="strength" {{ old('category') === 'strength' ? 'selected' : '' }}>Strength Training</option>
                            <option value="cardio" {{ old('category') === 'cardio' ? 'selected' : '' }}>Cardio & Fitness</option>
                            <option value="yoga" {{ old('category') === 'yoga' ? 'selected' : '' }}>Yoga & Wellness</option>
                            <option value="pilates" {{ old('category') === 'pilates' ? 'selected' : '' }}>Pilates</option>
                            <option value="boxing" {{ old('category') === 'boxing' ? 'selected' : '' }}>Boxing & MMA</option>
                            <option value="nutrition" {{ old('category') === 'nutrition' ? 'selected' : '' }}>Nutrition</option>
                            <option value="other" {{ old('category') === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="state" class="form-label">State</label>
                        <select id="state" name="state" class="form-input" required>
                            <option value="">Select State</option>
                            <option value="selangor" {{ old('state') === 'selangor' ? 'selected' : '' }}>Selangor</option>
                            <option value="kuala_lumpur" {{ old('state') === 'kuala_lumpur' ? 'selected' : '' }}>Kuala Lumpur</option>
                            <option value="johor" {{ old('state') === 'johor' ? 'selected' : '' }}>Johor</option>
                            <option value="penang" {{ old('state') === 'penang' ? 'selected' : '' }}>Penang</option>
                            <option value="sabah" {{ old('state') === 'sabah' ? 'selected' : '' }}>Sabah</option>
                            <option value="sarawak" {{ old('state') === 'sarawak' ? 'selected' : '' }}>Sarawak</option>
                            <option value="melaka" {{ old('state') === 'melaka' ? 'selected' : '' }}>Melaka</option>
                            <option value="negeri_sembilan" {{ old('state') === 'negeri_sembilan' ? 'selected' : '' }}>Negeri Sembilan</option>
                            <option value="perak" {{ old('state') === 'perak' ? 'selected' : '' }}>Perak</option>
                            <option value="kedah" {{ old('state') === 'kedah' ? 'selected' : '' }}>Kedah</option>
                            <option value="pahang" {{ old('state') === 'pahang' ? 'selected' : '' }}>Pahang</option>
                            <option value="terengganu" {{ old('state') === 'terengganu' ? 'selected' : '' }}>Terengganu</option>
                            <option value="kelantan" {{ old('state') === 'kelantan' ? 'selected' : '' }}>Kelantan</option>
                            <option value="perlis" {{ old('state') === 'perlis' ? 'selected' : '' }}>Perlis</option>
                            <option value="labuan" {{ old('state') === 'labuan' ? 'selected' : '' }}>Labuan</option>
                            <option value="putrajaya" {{ old('state') === 'putrajaya' ? 'selected' : '' }}>Putrajaya</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="area" class="form-label">Area</label>
                        <input 
                            type="text" 
                            id="area" 
                            name="area" 
                            class="form-input" 
                            placeholder="Enter your area (e.g., Petaling Jaya)"
                            value="{{ old('area') }}"
                            required
                        >
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="profile_picture" class="form-label">Profile Picture</label>
                        <input 
                            type="file" 
                            id="profile_picture" 
                            name="profile_picture" 
                            class="form-input-file" 
                            accept="image/*"
                            onchange="previewImage(this, 'profile-preview')"
                        >
                        <div id="profile-preview" class="image-preview"></div>
                    </div>

                    <div class="form-group">
                        <label for="qualification_file" class="form-label">Qualification/Certification</label>
                        <input 
                            type="file" 
                            id="qualification_file" 
                            name="qualification_file" 
                            class="form-input-file" 
                            accept="image/*,.pdf"
                            onchange="previewFile(this, 'qualification-preview')"
                        >
                        <div id="qualification-preview" class="file-preview"></div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Location (Auto-detected)</label>
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <button type="button" id="get-location" class="location-btn">
                            <i class="bi bi-geo-alt me-2"></i>Get My Location
                        </button>
                        <span id="location-status" style="color: #cccccc; font-size: 0.9rem; font-family: 'Poppins', sans-serif;"></span>
                    </div>
                    <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude') }}">
                    <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude') }}">
                </div>

                <div class="form-options">
                    <label class="checkbox-label">
                        <input type="checkbox" name="terms" required>
                        <span>I agree to the <a href="#" class="inline-link">Terms & Conditions</a> and <a href="#" class="inline-link">Trainer Agreement</a></span>
                    </label>
                </div>

                <button type="submit" class="auth-button">
                    <i class="bi bi-person-plus me-2"></i>Register as Trainer
                </button>
            </form>

            <div class="auth-footer">
                <p>Already have an account? <a href="{{ route('login') }}" class="auth-link">Login here</a></p>
                <p style="margin-top: 10px;">Looking to book a trainer? <a href="{{ route('signup') }}" class="auth-link">Sign up as Customer</a></p>
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
            preview.innerHTML = '<img src="' + e.target.result + '" style="max-width: 150px; max-height: 150px; border-radius: 8px; margin-top: 10px;">';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function previewFile(input, previewId) {
    const preview = document.getElementById(previewId);
    if (input.files && input.files[0]) {
        const fileName = input.files[0].name;
        preview.innerHTML = '<span style="color: var(--accent-green); margin-top: 10px; display: block; font-family: Poppins, sans-serif;">Selected: ' + fileName + '</span>';
    }
}

document.getElementById('get-location').addEventListener('click', function() {
    const status = document.getElementById('location-status');
    status.textContent = 'Getting location...';
    
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                document.getElementById('latitude').value = position.coords.latitude;
                document.getElementById('longitude').value = position.coords.longitude;
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

<style>
.auth-container {
    min-height: calc(100vh - 200px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 80px 20px;
    position: relative;
    background-image: url('{{ asset('images/homepage.jpg') }}');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
}

.auth-container::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(0, 0, 0, 0.4) 0%, rgba(0, 0, 0, 0.6) 100%);
    z-index: 0;
}

.auth-wrapper-wide {
    width: 100%;
    max-width: 900px;
    position: relative;
    z-index: 1;
}

.auth-card-wide {
    background: rgba(26, 26, 26, 0.95);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(0, 204, 102, 0.2);
    border-radius: 20px;
    padding: 40px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
}

.auth-header {
    text-align: center;
    margin-bottom: 35px;
}

.auth-title {
    font-size: 2.2rem;
    font-weight: 700;
    color: #ffffff;
    margin-bottom: 10px;
    letter-spacing: 1px;
    font-family: 'Poppins', sans-serif;
}

.auth-subtitle {
    color: #cccccc;
    font-size: 0.95rem;
    font-family: 'Poppins', sans-serif;
}

.auth-form {
    margin-bottom: 25px;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 20px;
}

.form-group {
    margin-bottom: 0;
}

.form-label {
    display: block;
    color: #ffffff;
    font-size: 0.9rem;
    font-weight: 500;
    margin-bottom: 8px;
    font-family: 'Poppins', sans-serif;
}

.form-input {
    width: 100%;
    padding: 14px 18px;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 10px;
    color: #ffffff;
    font-size: 1rem;
    font-family: 'Poppins', sans-serif;
    transition: all 0.3s ease;
    outline: none;
}

.form-input:focus {
    background: rgba(255, 255, 255, 0.08);
    border-color: var(--accent-green);
    box-shadow: 0 0 0 3px rgba(0, 204, 102, 0.1);
}

.form-input::placeholder {
    color: #666666;
}

.form-input option {
    background: #1a1a1a;
    color: #ffffff;
}

.form-input-file {
    width: 100%;
    padding: 14px 18px;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 10px;
    color: #ffffff;
    font-size: 1rem;
    font-family: 'Poppins', sans-serif;
    cursor: pointer;
    transition: all 0.3s ease;
}

.form-input-file:hover {
    background: rgba(255, 255, 255, 0.08);
    border-color: rgba(255, 255, 255, 0.2);
}

.image-preview img, .file-preview {
    display: block;
}

.location-btn {
    padding: 12px 24px;
    background: rgba(0, 204, 102, 0.1);
    border: 1px solid var(--accent-green);
    border-radius: 8px;
    color: var(--accent-green);
    font-size: 0.95rem;
    font-weight: 600;
    font-family: 'Poppins', sans-serif;
    cursor: pointer;
    transition: all 0.3s ease;
}

.location-btn:hover {
    background: rgba(0, 204, 102, 0.2);
    transform: translateY(-2px);
}

.form-options {
    display: flex;
    justify-content: flex-start;
    align-items: flex-start;
    margin: 30px 0 25px;
    font-size: 0.9rem;
}

.checkbox-label {
    display: flex;
    align-items: flex-start;
    color: #cccccc;
    cursor: pointer;
    font-family: 'Poppins', sans-serif;
    line-height: 1.5;
}

.checkbox-label input[type="checkbox"] {
    margin-right: 8px;
    margin-top: 3px;
    width: 18px;
    height: 18px;
    cursor: pointer;
    accent-color: var(--accent-green);
    flex-shrink: 0;
}

.inline-link {
    color: var(--accent-green);
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
}

.inline-link:hover {
    color: var(--accent-green-light);
    text-decoration: underline;
}

.auth-button {
    width: 100%;
    padding: 16px;
    background: var(--accent-green);
    color: #000000;
    border: none;
    border-radius: 10px;
    font-size: 1rem;
    font-weight: 700;
    font-family: 'Poppins', sans-serif;
    letter-spacing: 1px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    text-transform: uppercase;
}

.auth-button:hover {
    background: var(--accent-green-dark);
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0, 204, 102, 0.4);
}

.auth-footer {
    text-align: center;
    padding-top: 20px;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.auth-footer p {
    color: #cccccc;
    font-size: 0.95rem;
    font-family: 'Poppins', sans-serif;
    margin: 0;
}

.auth-link {
    color: var(--accent-green);
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
}

.auth-link:hover {
    color: var(--accent-green-light);
    text-decoration: underline;
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .auth-card-wide {
        padding: 30px 20px;
    }
    
    .auth-title {
        font-size: 1.8rem;
    }
}
</style>
@endsection
