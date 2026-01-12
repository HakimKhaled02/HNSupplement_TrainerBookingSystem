<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Book Your Trainer - HN Supplement'); ?></title>
    
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
<body>
    <?php echo $__env->make('components.navbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    
    <main class="main-content">
        <?php echo $__env->yieldContent('content'); ?>
    </main>
    
    <?php echo $__env->make('components.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Vite JS -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/js/app.js']); ?>
    
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>

<?php /**PATH D:\hnsupplement\resources\views/layouts/app.blade.php ENDPATH**/ ?>