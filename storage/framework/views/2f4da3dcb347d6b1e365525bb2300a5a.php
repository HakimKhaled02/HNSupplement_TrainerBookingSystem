<nav class="navbar navbar-expand-lg navbar-dark navbar-travel fixed-top">
    <div class="container-fluid px-4">
        <a class="navbar-brand d-flex align-items-center" href="<?php echo e(route('home')); ?>">
            <img src="<?php echo e(asset('images/logo.jpg')); ?>" alt="HN Supplement Logo" height="45" class="me-3" style="border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 204, 102, 0.2);">
            <span>HN SUPPLEMENT</span>
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation"
                style="border-color: rgba(255, 255, 255, 0.3);">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <?php
                $isAuthPage = in_array(request()->route()->getName(), ['login', 'signup', 'trainer.signup', 'admin.login']);
            ?>
            
            <?php if(!$isAuthPage): ?>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link <?php echo e(request()->routeIs('home') ? 'active' : ''); ?>" href="<?php echo e(route('home')); ?>">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Trainers</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Contact</a>
                    </li>
                </ul>
            <?php endif; ?>
            
            <div class="ms-auto d-flex align-items-center gap-3">
                <?php if(auth()->guard()->check()): ?>
                    <?php
                        $user = auth()->user();
                        $profilePicture = null;
                        if ($user->trainer && $user->trainer->profile_picture) {
                            $profilePicture = $user->trainer->profile_picture;
                        } elseif ($user->customer && $user->customer->profile_picture) {
                            $profilePicture = $user->customer->profile_picture;
                        } elseif ($user->staff && $user->staff->profile_picture) {
                            $profilePicture = $user->staff->profile_picture;
                        }
                    ?>
                    <!-- Profile Dropdown -->
                    <div class="profile-dropdown">
                        <button class="profile-trigger" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php if($profilePicture): ?>
                                <img src="<?php echo e(asset('storage/' . $profilePicture)); ?>" alt="Profile" class="profile-avatar">
                            <?php else: ?>
                                <div class="profile-avatar-text">
                                    <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

                                </div>
                            <?php endif; ?>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end profile-menu" aria-labelledby="profileDropdown">
                            <li>
                                <h6 class="dropdown-header" style="color: #ffffff; font-family: 'Poppins', sans-serif; font-weight: 600;">
                                    <?php echo e($user->name); ?>

                                </h6>
                                <p class="dropdown-header-text" style="color: #cccccc; font-size: 0.85rem; font-family: 'Poppins', sans-serif; margin: 0;">
                                    <?php echo e($user->email); ?>

                                </p>
                            </li>
                            <li><hr class="dropdown-divider" style="border-color: rgba(255, 255, 255, 0.1);"></li>
                            <li>
                                <a class="dropdown-item" href="#">
                                    <i class="bi bi-person me-2"></i>Profile
                                </a>
                            </li>
                            <?php if($user->role === 'staff'): ?>
                                <li>
                                    <a class="dropdown-item" href="<?php echo e(route('admin.dashboard')); ?>">
                                        <i class="bi bi-shield-lock me-2"></i>Admin Dashboard
                                    </a>
                                </li>
                            <?php elseif($user->role === 'trainer'): ?>
                                <li>
                                    <a class="dropdown-item" href="<?php echo e(route('trainer.dashboard')); ?>">
                                        <i class="bi bi-speedometer2 me-2"></i>Trainer Dashboard
                                    </a>
                                </li>
                            <?php endif; ?>
                            <li><hr class="dropdown-divider" style="border-color: rgba(255, 255, 255, 0.1);"></li>
                            <li>
                                <form method="POST" action="<?php echo e(route('logout')); ?>" style="margin: 0;">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="dropdown-item logout-btn">
                                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                <?php else: ?>
                    <!-- Login Buttons (when not authenticated) -->
                    <?php if(!$isAuthPage): ?>
                        <a href="<?php echo e(route('login')); ?>" class="nav-btn nav-btn-login">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Login / Sign Up
                        </a>
                        <a href="<?php echo e(route('admin.login')); ?>" class="nav-btn nav-btn-admin">
                            <i class="bi bi-shield-lock me-2"></i>Admin Panel
                        </a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
<?php /**PATH D:\hnsupplement\resources\views/components/navbar.blade.php ENDPATH**/ ?>