<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Dashboard - HN Supplement'); ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/jpeg" href="<?php echo e(asset('images/logo.jpg')); ?>">
    <link rel="apple-touch-icon" href="<?php echo e(asset('images/logo.jpg')); ?>">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- Vite CSS -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css']); ?>
    
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="dashboard-body">
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="dashboard-sidebar">
            <div class="sidebar-header">
                <a href="<?php echo e(route('home')); ?>" class="sidebar-brand">
                    <img src="<?php echo e(asset('images/logo.jpg')); ?>" alt="HN Supplement Logo" height="40" class="sidebar-logo">
                    <span class="sidebar-brand-text">HN SUPPLEMENT</span>
                </a>
            </div>
            
            <nav class="sidebar-nav">
                <?php echo $__env->yieldContent('sidebar-menu'); ?>
            </nav>
            
            <div class="sidebar-footer">
                <div class="sidebar-user">
                    <div class="sidebar-user-avatar">
                        <?php if(auth()->guard()->check()): ?>
                            <?php
                                $user = auth()->user();
                                $profilePicture = null;
                                if ($user->role === 'trainer' && $user->trainer && $user->trainer->profile_picture) {
                                    $profilePicture = $user->trainer->profile_picture;
                                } elseif ($user->role === 'customer' && $user->customer && $user->customer->profile_picture) {
                                    $profilePicture = $user->customer->profile_picture;
                                } elseif ($user->role === 'staff' && $user->staff && $user->staff->profile_picture) {
                                    $profilePicture = $user->staff->profile_picture;
                                }
                            ?>
                            <?php if($profilePicture): ?>
                                <img src="<?php echo e(asset('storage/' . $profilePicture)); ?>" alt="<?php echo e($user->name); ?>" class="sidebar-user-avatar-img">
                            <?php else: ?>
                                <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                    <div class="sidebar-user-info">
                        <div class="sidebar-user-name"><?php if(auth()->guard()->check()): ?><?php echo e(auth()->user()->name); ?><?php endif; ?></div>
                        <div class="sidebar-user-role"><?php if(auth()->guard()->check()): ?><?php echo e(ucfirst(auth()->user()->role)); ?><?php endif; ?></div>
                    </div>
                </div>
                <form action="<?php echo e(route('logout')); ?>" method="POST" class="sidebar-logout-form">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="sidebar-logout-btn">
                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                    </button>
                </form>
            </div>
        </aside>
        
        <!-- Main Content -->
        <main class="dashboard-main">
            <div class="dashboard-header">
                <h1 class="dashboard-title"><?php echo $__env->yieldContent('page-title', 'Dashboard'); ?></h1>
                <div class="dashboard-header-actions">
                    <?php echo $__env->yieldContent('header-actions'); ?>
                </div>
            </div>
            
            <div class="dashboard-content">
                <?php if(session('success')): ?>
                    <div class="alert alert-success dashboard-alert">
                        <?php echo e(session('success')); ?>

                    </div>
                <?php endif; ?>

                <?php if(session('error')): ?>
                    <div class="alert alert-danger dashboard-alert">
                        <?php echo e(session('error')); ?>

                    </div>
                <?php endif; ?>

                <?php if($errors->any()): ?>
                    <div class="alert alert-danger dashboard-alert">
                        <ul style="margin: 0; padding-left: 20px;">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php echo $__env->yieldContent('content'); ?>
            </div>
        </main>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Vite JS -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/js/app.js']); ?>
    
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>

<?php /**PATH D:\hnsupplement\resources\views/layouts/dashboard.blade.php ENDPATH**/ ?>