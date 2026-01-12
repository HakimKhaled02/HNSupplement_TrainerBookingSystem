@extends('layouts.app')

@section('title', 'Home - Book Your Trainer')

@section('content')
<div class="container-fluid px-0">
    <!-- Hero Section with Background Image -->
    <section class="hero-fullscreen">
        <!-- Background Image -->
        <div class="hero-background" style="background-image: url('{{ asset('images/homepage.jpg') }}');"></div>
        
        <!-- Hero Content -->
        <div class="hero-content-wrapper">
            <h1 class="hero-title-large">TRAINERS</h1>
            <p class="hero-description">
                Book your perfect personal trainer and achieve your fitness goals.
            </p>
        </div>
        
        <!-- Search Form -->
        <div class="search-form-container">
            <form class="search-form" action="#" method="GET">
                <div class="search-form-group">
                    <label for="location">Location</label>
                    <input type="text" id="location" name="location" placeholder="City, Area...">
                </div>
                <div class="search-form-group">
                    <label for="specialization">Specialization</label>
                    <input type="text" id="specialization" name="specialization" placeholder="Strength, Cardio, Yoga...">
                </div>
                <div class="search-form-group">
                    <label for="date">Date</label>
                    <input type="date" id="date" name="date" placeholder="Select Date">
                </div>
                <div class="search-form-group">
                    <label for="time">Time</label>
                    <input type="time" id="time" name="time" placeholder="Select Time">
                </div>
                <button type="submit" class="search-button">Search</button>
            </form>
        </div>
        
        <!-- Social Icons - Right Side -->
        <div class="social-sidebar">
            <a href="#" class="social-sidebar-item">
                <i class="bi bi-facebook"></i>
            </a>
            <a href="#" class="social-sidebar-item">
                <i class="bi bi-instagram"></i>
            </a>
            <a href="#" class="social-sidebar-item">
                <i class="bi bi-twitter"></i>
            </a>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5" style="background-color: #1a1a1a; padding: 80px 0;">
        <div class="container">
            <h2 class="text-center mb-5" style="color: var(--accent-green); font-size: 2.5rem; font-weight: 700; letter-spacing: 2px;">Why Choose Our Trainers?</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="text-center p-4" style="background-color: #2d2d2d; border-radius: 15px; height: 100%; border: 1px solid rgba(0, 204, 102, 0.3); transition: all 0.3s ease;">
                        <div class="mb-3" style="font-size: 3.5rem; color: var(--accent-green);">
                            <i class="bi bi-award-fill"></i>
                        </div>
                        <h4 style="color: #ffffff; margin-bottom: 1rem; font-weight: 600;">Certified Professionals</h4>
                        <p style="color: #cccccc; line-height: 1.8;">
                            All our trainers are certified and experienced professionals dedicated to helping you achieve your goals.
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center p-4" style="background-color: #2d2d2d; border-radius: 15px; height: 100%; border: 1px solid rgba(0, 204, 102, 0.3); transition: all 0.3s ease;">
                        <div class="mb-3" style="font-size: 3.5rem; color: var(--accent-green);">
                            <i class="bi bi-person-check-fill"></i>
                        </div>
                        <h4 style="color: #ffffff; margin-bottom: 1rem; font-weight: 600;">Personalized Training</h4>
                        <p style="color: #cccccc; line-height: 1.8;">
                            Get customized workout plans tailored to your fitness level, goals, and preferences.
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center p-4" style="background-color: #2d2d2d; border-radius: 15px; height: 100%; border: 1px solid rgba(0, 204, 102, 0.3); transition: all 0.3s ease;">
                        <div class="mb-3" style="font-size: 3.5rem; color: var(--accent-green);">
                            <i class="bi bi-calendar-check-fill"></i>
                        </div>
                        <h4 style="color: #ffffff; margin-bottom: 1rem; font-weight: 600;">Flexible Scheduling</h4>
                        <p style="color: #cccccc; line-height: 1.8;">
                            Book sessions at your convenience with flexible scheduling options that fit your lifestyle.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Trainers Section -->
    <section class="py-5" style="background-color: #000000; padding: 80px 0;">
        <div class="container">
            <h2 class="text-center mb-5" style="color: var(--accent-green); font-size: 2.5rem; font-weight: 700; letter-spacing: 2px;">Featured Trainers</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100" style="background-color: #1a1a1a; border: 1px solid rgba(0, 204, 102, 0.3); border-radius: 15px; transition: all 0.3s ease;">
                        <div class="card-body text-center p-4">
                            <div class="mb-3" style="font-size: 4.5rem; color: var(--accent-green);">
                                <i class="bi bi-person-badge-fill"></i>
                            </div>
                            <h5 class="card-title mb-3" style="color: #ffffff; font-weight: 600; font-size: 1.3rem;">Strength Training</h5>
                            <p class="card-text" style="color: #cccccc; line-height: 1.8;">
                                Expert trainers specializing in strength and muscle building programs.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100" style="background-color: #1a1a1a; border: 1px solid rgba(0, 204, 102, 0.3); border-radius: 15px; transition: all 0.3s ease;">
                        <div class="card-body text-center p-4">
                            <div class="mb-3" style="font-size: 4.5rem; color: var(--accent-green);">
                                <i class="bi bi-heart-pulse-fill"></i>
                            </div>
                            <h5 class="card-title mb-3" style="color: #ffffff; font-weight: 600; font-size: 1.3rem;">Cardio & Fitness</h5>
                            <p class="card-text" style="color: #cccccc; line-height: 1.8;">
                                Professional trainers focused on cardiovascular health and overall fitness.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100" style="background-color: #1a1a1a; border: 1px solid rgba(0, 204, 102, 0.3); border-radius: 15px; transition: all 0.3s ease;">
                        <div class="card-body text-center p-4">
                            <div class="mb-3" style="font-size: 4.5rem; color: var(--accent-green);">
                                <i class="bi bi-flower1"></i>
                            </div>
                            <h5 class="card-title mb-3" style="color: #ffffff; font-weight: 600; font-size: 1.3rem;">Yoga & Wellness</h5>
                            <p class="card-text" style="color: #cccccc; line-height: 1.8;">
                                Experienced instructors for yoga, meditation, and holistic wellness practices.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
    /* Card hover effects */
    .card:hover {
        transform: translateY(-8px);
        border-color: var(--accent-green) !important;
        box-shadow: 0 15px 40px rgba(0, 204, 102, 0.3);
    }
    
    /* Feature card hover */
    section .text-center:hover {
        transform: translateY(-5px);
        border-color: var(--accent-green) !important;
        box-shadow: 0 10px 30px rgba(0, 204, 102, 0.2);
    }
</style>
@endsection
