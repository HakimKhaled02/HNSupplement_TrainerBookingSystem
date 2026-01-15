<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TrainerController;
use App\Http\Controllers\CustomerController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/trainers', [HomeController::class, 'trainers'])->name('trainers');
Route::get('/trainer/{id}/book', [HomeController::class, 'bookTrainer'])->name('trainer.book')->middleware('auth');
Route::get('/trainer/{id}/check-availability', [HomeController::class, 'checkAvailability'])->name('trainer.check-availability');
Route::get('/booking', function() {
    return redirect()->route('trainers')->with('info', 'Please select a trainer to book.');
})->name('booking.index');
Route::post('/booking', [App\Http\Controllers\BookingController::class, 'store'])->name('booking.store')->middleware('auth');
Route::get('/booking/{id}/payment', [App\Http\Controllers\BookingController::class, 'payment'])->name('booking.payment')->middleware('auth');
Route::post('/booking/{id}/payment', [App\Http\Controllers\BookingController::class, 'processPayment'])->name('booking.payment.process')->middleware('auth');
Route::get('/booking/{id}/success', [App\Http\Controllers\BookingController::class, 'success'])->name('booking.success')->middleware('auth');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/signup', [AuthController::class, 'showSignup'])->name('signup');
Route::post('/signup', [AuthController::class, 'signup'])->name('signup.post');
Route::get('/trainer/signup', [AuthController::class, 'showTrainerSignup'])->name('trainer.signup');
Route::post('/trainer/signup', [AuthController::class, 'trainerSignup'])->name('trainer.signup.post');

// Password Reset Routes
Route::get('/password/forgot', [AuthController::class, 'showForgotPassword'])->name('password.forgot');
Route::post('/password/forgot', [AuthController::class, 'sendPasswordReset'])->name('password.forgot.post');
Route::get('/password/reset/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
Route::post('/password/reset', [AuthController::class, 'resetPassword'])->name('password.reset.post');

// Admin Routes
Route::get('/admin/login', [AdminController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.post');

Route::middleware('auth')->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/approvals', [AdminController::class, 'approvals'])->name('admin.approvals');
    Route::get('/admin/trainers', [AdminController::class, 'trainers'])->name('admin.trainers');
    Route::post('/admin/trainer/{id}/approve', [AdminController::class, 'approveTrainer'])->name('admin.approve.trainer');
    Route::post('/admin/trainer/{id}/reject', [AdminController::class, 'rejectTrainer'])->name('admin.reject.trainer');
    Route::post('/admin/trainer/{id}/update-salary', [AdminController::class, 'updateSalary'])->name('admin.trainer.update-salary');
    Route::get('/admin/profile', [AdminController::class, 'profile'])->name('admin.profile');
    Route::get('/admin/profile/edit', [AdminController::class, 'editProfile'])->name('admin.profile.edit');
    Route::post('/admin/profile/update', [AdminController::class, 'updateProfile'])->name('admin.profile.update');
    Route::get('/admin/monitor/bookings', [AdminController::class, 'monitorBookings'])->name('admin.monitor.bookings');
});

// Trainer Routes
Route::middleware('auth')->group(function () {
    Route::get('/trainer/dashboard', [TrainerController::class, 'dashboard'])->name('trainer.dashboard');
    Route::get('/trainer/profile', [TrainerController::class, 'profile'])->name('trainer.profile');
    Route::get('/trainer/profile/edit', [TrainerController::class, 'editProfile'])->name('trainer.profile.edit');
    Route::post('/trainer/profile/update', [TrainerController::class, 'updateProfile'])->name('trainer.profile.update');
    Route::get('/trainer/reviews', [TrainerController::class, 'reviews'])->name('trainer.reviews');
    Route::get('/trainer/availability', [TrainerController::class, 'availability'])->name('trainer.availability');
    Route::post('/trainer/availability/update', [TrainerController::class, 'updateAvailability'])->name('trainer.availability.update');
    Route::get('/trainer/bookings', [TrainerController::class, 'bookings'])->name('trainer.bookings');
    Route::get('/trainer/booking/{id}/attendance', [TrainerController::class, 'manageAttendance'])->name('trainer.attendance');
    Route::post('/trainer/booking/{id}/attendance', [TrainerController::class, 'updateAttendance'])->name('trainer.attendance.update');
});

// Customer Routes
Route::middleware('auth')->group(function () {
    Route::get('/customer/profile', [CustomerController::class, 'profile'])->name('customer.profile');
    Route::get('/customer/profile/edit', [CustomerController::class, 'editProfile'])->name('customer.profile.edit');
    Route::post('/customer/profile/update', [CustomerController::class, 'updateProfile'])->name('customer.profile.update');
    Route::get('/customer/bookings', [CustomerController::class, 'bookings'])->name('customer.bookings');
    Route::get('/customer/booking/{id}/attendance', [CustomerController::class, 'viewAttendance'])->name('customer.attendance');
    Route::post('/customer/booking/{id}/reminder', [CustomerController::class, 'setReminder'])->name('customer.booking.reminder');
    Route::post('/customer/booking/{id}/feedback', [CustomerController::class, 'submitFeedback'])->name('customer.booking.feedback');
});

// Logout Route
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

