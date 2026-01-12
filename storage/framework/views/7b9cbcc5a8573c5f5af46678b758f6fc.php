<footer class="mt-auto" style="background-color: #1a1a1a; border-top: 2px solid var(--accent-green); padding: 3rem 0 1rem;">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4">
                <h5 class="mb-3" style="color: var(--accent-green);">
                    <i class="bi bi-person-badge me-2"></i>HN SUPPLEMENT
                </h5>
                <p class="text-secondary" style="color: #cccccc;">
                    Your trusted platform for booking certified personal trainers. Achieve your fitness goals with professional guidance.
                </p>
            </div>
            
            <div class="col-md-4 mb-4">
                <h5 class="mb-3" style="color: var(--accent-green);">Quick Links</h5>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="<?php echo e(route('home')); ?>" class="text-decoration-none" style="color: #cccccc; transition: color 0.3s;">
                            <i class="bi bi-arrow-right me-2"></i>Home
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="text-decoration-none" style="color: #cccccc; transition: color 0.3s;">
                            <i class="bi bi-arrow-right me-2"></i>Trainers
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="text-decoration-none" style="color: #cccccc; transition: color 0.3s;">
                            <i class="bi bi-arrow-right me-2"></i>About Us
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="text-decoration-none" style="color: #cccccc; transition: color 0.3s;">
                            <i class="bi bi-arrow-right me-2"></i>Contact
                        </a>
                    </li>
                </ul>
            </div>
            
            <div class="col-md-4 mb-4">
                <h5 class="mb-3" style="color: var(--accent-green);">Connect With Us</h5>
                <div class="d-flex gap-3">
                    <a href="#" class="text-decoration-none" style="color: #cccccc; font-size: 1.5rem; transition: color 0.3s;">
                        <i class="bi bi-facebook"></i>
                    </a>
                    <a href="#" class="text-decoration-none" style="color: #cccccc; font-size: 1.5rem; transition: color 0.3s;">
                        <i class="bi bi-instagram"></i>
                    </a>
                    <a href="#" class="text-decoration-none" style="color: #cccccc; font-size: 1.5rem; transition: color 0.3s;">
                        <i class="bi bi-twitter"></i>
                    </a>
                    <a href="#" class="text-decoration-none" style="color: #cccccc; font-size: 1.5rem; transition: color 0.3s;">
                        <i class="bi bi-youtube"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <hr style="border-color: #2d2d2d; margin: 2rem 0;">
        
        <div class="row">
            <div class="col-12 text-center">
                <p class="mb-0" style="color: #888888;">
                    &copy; <?php echo e(date('Y')); ?> HN Supplement. All rights reserved.
                </p>
            </div>
        </div>
    </div>
</footer>

<style>
    footer a:hover {
        color: var(--accent-green) !important;
    }
    
    footer .list-unstyled a:hover {
        transform: translateX(5px);
        display: inline-block;
    }
</style>

<?php /**PATH D:\hnsupplement\resources\views/components/footer.blade.php ENDPATH**/ ?>