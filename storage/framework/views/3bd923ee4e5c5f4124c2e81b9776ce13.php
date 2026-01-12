

<?php $__env->startSection('title', 'Home - Book Your Trainer'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-0">
    <!-- Hero Section -->
    <section class="hero-section" style="background-image: url('<?php echo e(asset('images/homepage.jpg')); ?>');">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1 class="hero-title">Book Your Perfect Trainer</h1>
            <p class="hero-description">
                Find certified personal trainers near you<br>and achieve your fitness goals with personalized training programs.
            </p>
            <a href="<?php echo e(route('login')); ?>" class="btn btn-primary btn-hero">Get Started</a>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\hnsupplement\resources\views/home.blade.php ENDPATH**/ ?>