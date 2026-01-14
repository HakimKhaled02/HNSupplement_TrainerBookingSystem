

<?php $__env->startSection('title', 'Reset Password - Book Your Trainer'); ?>

<?php $__env->startSection('content'); ?>
<div class="auth-container">
    <div class="auth-wrapper">
        <div class="auth-card">
            <div class="auth-header">
                <h2 class="auth-title">Reset Password</h2>
                <p class="auth-subtitle">Enter your new password below.</p>
            </div>

            <?php if($errors->any()): ?>
                <div class="alert alert-danger" style="background: rgba(220, 53, 69, 0.1); border: 1px solid rgba(220, 53, 69, 0.3); color: #ff6b6b; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-family: 'Poppins', sans-serif;">
                    <ul style="margin: 0; padding-left: 20px;">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form class="auth-form" method="POST" action="<?php echo e(route('password.reset.post')); ?>">
                <?php echo csrf_field(); ?>
                
                <input type="hidden" name="token" value="<?php echo e($token); ?>">

                <div class="form-group">
                    <label for="email" class="form-label">
                        <i class="bi bi-envelope me-2"></i>Email Address
                    </label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        class="form-input" 
                        placeholder="Enter your email"
                        value="<?php echo e(old('email')); ?>"
                        required
                        autofocus
                    >
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">
                        <i class="bi bi-lock me-2"></i>New Password
                    </label>
                    <div class="password-input-wrapper">
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="form-input" 
                            placeholder="Enter new password (min 8 characters)"
                            required
                        >
                        <button type="button" class="password-toggle" onclick="togglePassword('password')">
                            <i class="bi bi-eye" id="password-toggle-icon"></i>
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password_confirmation" class="form-label">
                        <i class="bi bi-lock-fill me-2"></i>Confirm New Password
                    </label>
                    <div class="password-input-wrapper">
                        <input 
                            type="password" 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            class="form-input" 
                            placeholder="Confirm new password"
                            required
                        >
                        <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation')">
                            <i class="bi bi-eye" id="password_confirmation-toggle-icon"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="auth-button">
                    <i class="bi bi-check-circle me-2"></i>Reset Password
                </button>
            </form>

            <div class="auth-footer">
                <p>Remember your password? <a href="<?php echo e(route('login')); ?>" class="auth-link">Login here</a></p>
            </div>
        </div>
    </div>
</div>

<style>
.auth-container {
    min-height: calc(100vh - 200px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 100px 20px 80px;
    position: relative;
    background-image: url('<?php echo e(asset('images/homepage.jpg')); ?>');
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

.auth-wrapper {
    width: 100%;
    max-width: 450px;
    position: relative;
    z-index: 1;
}

.auth-card {
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

.form-group {
    margin-bottom: 25px;
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

.password-input-wrapper .form-input {
    padding-right: 50px;
}

.form-input:focus {
    background: rgba(255, 255, 255, 0.08);
    border-color: var(--accent-green);
    box-shadow: 0 0 0 3px rgba(0, 204, 102, 0.1);
}

.form-input::placeholder {
    color: #666666;
}

.password-input-wrapper {
    position: relative;
}

.password-toggle {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    background: transparent;
    border: none;
    color: #cccccc;
    cursor: pointer;
    padding: 5px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    z-index: 10;
}

.password-toggle:hover {
    color: var(--accent-green);
}

.password-toggle i {
    font-size: 1.2rem;
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

@media (max-width: 576px) {
    .auth-card {
        padding: 30px 20px;
    }
    
    .auth-title {
        font-size: 1.8rem;
    }
}
</style>

<script>
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(inputId + '-toggle-icon');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    }
}
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\hnsupplement\resources\views/auth/reset-password.blade.php ENDPATH**/ ?>