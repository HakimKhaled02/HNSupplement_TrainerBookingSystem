<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Admin Dashboard - Trainer Approvals</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/jpeg" href="<?php echo e(asset('images/logo.jpg')); ?>">
    <link rel="apple-touch-icon" href="<?php echo e(asset('images/logo.jpg')); ?>">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- Vite CSS -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css']); ?>
</head>
<body style="background-color: #000000; color: #ffffff; font-family: 'Poppins', sans-serif;">
    <!-- Admin Dashboard Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background: rgba(26, 26, 26, 0.95) !important; backdrop-filter: blur(10px); border-bottom: 1px solid rgba(0, 204, 102, 0.3); padding: 1rem 0;">
        <div class="container-fluid px-4">
            <a class="navbar-brand d-flex align-items-center" href="<?php echo e(route('admin.dashboard')); ?>">
                <img src="<?php echo e(asset('images/logo.jpg')); ?>" alt="HN Supplement Logo" height="45" class="me-3" style="border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 204, 102, 0.2);">
                <span>HN SUPPLEMENT</span>
            </a>
            
            <div class="ms-auto d-flex align-items-center gap-3">
                <?php
                    $user = auth()->user();
                ?>
                <div class="profile-dropdown">
                    <button class="profile-trigger" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="profile-avatar-text">
                            <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

                        </div>
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
            </div>
        </div>
    </nav>

    <div class="container-fluid" style="padding: 100px 20px 80px; background-color: #000000; min-height: 100vh;">
    <div class="container">
        <h1 class="mb-4" style="color: var(--accent-green); font-size: 2.5rem; font-weight: 700; font-family: 'Poppins', sans-serif;">Admin Dashboard</h1>

        <?php if(session('success')): ?>
            <div class="alert alert-success" style="background: rgba(0, 204, 102, 0.1); border: 1px solid var(--accent-green); color: var(--accent-green); padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="alert alert-danger" style="background: rgba(220, 53, 69, 0.1); border: 1px solid #dc3545; color: #ff6b6b; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                <?php echo e(session('error')); ?>

            </div>
        <?php endif; ?>

        <!-- Pending Trainers -->
        <div class="card mb-4" style="background-color: #1a1a1a; border: 1px solid rgba(0, 204, 102, 0.3); border-radius: 15px;">
            <div class="card-header" style="background-color: #2d2d2d; border-bottom: 1px solid rgba(0, 204, 102, 0.3); padding: 20px;">
                <h2 style="color: var(--accent-green); margin: 0; font-size: 1.8rem; font-weight: 700; font-family: 'Poppins', sans-serif;">
                    Pending Trainer Approvals (<?php echo e($pendingTrainers->count()); ?>)
                </h2>
            </div>
            <div class="card-body" style="padding: 20px;">
                <?php if($pendingTrainers->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table" style="color: #ffffff; margin: 0;">
                            <thead>
                                <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
                                    <th style="padding: 15px; font-weight: 600; font-family: 'Poppins', sans-serif;">Name</th>
                                    <th style="padding: 15px; font-weight: 600; font-family: 'Poppins', sans-serif;">Email</th>
                                    <th style="padding: 15px; font-weight: 600; font-family: 'Poppins', sans-serif;">Registered</th>
                                    <th style="padding: 15px; font-weight: 600; font-family: 'Poppins', sans-serif;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $pendingTrainers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trainer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.05);">
                                        <td style="padding: 15px; font-family: 'Poppins', sans-serif;"><?php echo e($trainer->user->name); ?></td>
                                        <td style="padding: 15px; font-family: 'Poppins', sans-serif;"><?php echo e($trainer->user->email); ?></td>
                                        <td style="padding: 15px; font-family: 'Poppins', sans-serif;"><?php echo e($trainer->created_at->format('M d, Y')); ?></td>
                                        <td style="padding: 15px;">
                                            <form action="<?php echo e(route('admin.approve.trainer', $trainer->id)); ?>" method="POST" style="display: inline-block; margin-right: 10px;">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="btn btn-success" style="background: var(--accent-green); color: #000; border: none; padding: 8px 20px; border-radius: 6px; font-weight: 600; cursor: pointer; font-family: 'Poppins', sans-serif;">
                                                    Approve
                                                </button>
                                            </form>
                                            <form action="<?php echo e(route('admin.reject.trainer', $trainer->id)); ?>" method="POST" style="display: inline-block;">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="btn btn-danger" style="background: #dc3545; color: #fff; border: none; padding: 8px 20px; border-radius: 6px; font-weight: 600; cursor: pointer; font-family: 'Poppins', sans-serif;">
                                                    Reject
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p style="color: #cccccc; text-align: center; padding: 40px; font-family: 'Poppins', sans-serif;">No pending trainer approvals.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Active Trainers -->
        <div class="card" style="background-color: #1a1a1a; border: 1px solid rgba(0, 204, 102, 0.3); border-radius: 15px;">
            <div class="card-header" style="background-color: #2d2d2d; border-bottom: 1px solid rgba(0, 204, 102, 0.3); padding: 20px;">
                <h2 style="color: var(--accent-green); margin: 0; font-size: 1.8rem; font-weight: 700; font-family: 'Poppins', sans-serif;">
                    Active Trainers (<?php echo e($activeTrainers->count()); ?>)
                </h2>
            </div>
            <div class="card-body" style="padding: 20px;">
                <?php if($activeTrainers->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table" style="color: #ffffff; margin: 0;">
                            <thead>
                                <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
                                    <th style="padding: 15px; font-weight: 600; font-family: 'Poppins', sans-serif;">Name</th>
                                    <th style="padding: 15px; font-weight: 600; font-family: 'Poppins', sans-serif;">Email</th>
                                    <th style="padding: 15px; font-weight: 600; font-family: 'Poppins', sans-serif;">Status</th>
                                    <th style="padding: 15px; font-weight: 600; font-family: 'Poppins', sans-serif;">Approved</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $activeTrainers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trainer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.05);">
                                        <td style="padding: 15px; font-family: 'Poppins', sans-serif;"><?php echo e($trainer->user->name); ?></td>
                                        <td style="padding: 15px; font-family: 'Poppins', sans-serif;"><?php echo e($trainer->user->email); ?></td>
                                        <td style="padding: 15px; font-family: 'Poppins', sans-serif;">
                                            <span style="background: rgba(0, 204, 102, 0.2); color: var(--accent-green); padding: 5px 12px; border-radius: 12px; font-size: 0.85rem; font-weight: 600;">
                                                <?php echo e(ucfirst($trainer->status)); ?>

                                            </span>
                                        </td>
                                        <td style="padding: 15px; font-family: 'Poppins', sans-serif;"><?php echo e($trainer->updated_at->format('M d, Y')); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p style="color: #cccccc; text-align: center; padding: 40px; font-family: 'Poppins', sans-serif;">No active trainers.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Vite JS -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/js/app.js']); ?>
</body>
</html>
<?php /**PATH D:\hnsupplement\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>