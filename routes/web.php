<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\CompanyProfileController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\FavoriteJobController;
use App\Http\Controllers\AppliedJobController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\DashboardController;

// Guest Routes (Unauthenticated)
Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return redirect()->route('login');
    });
    
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/verify-otp', [AuthController::class, 'showOtpForm'])->name('otp.verify.form');
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('otp.verify');
    Route::post('/resend-otp', [AuthController::class, 'resendOtp'])->name('otp.resend');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    // Admin Dashboard
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Resource Routes
    Route::resource('users', UserController::class);
    Route::resource('user-profiles', UserProfileController::class);
    Route::resource('company-profiles', CompanyProfileController::class);
    Route::resource('jobs', JobController::class);
    Route::resource('favorite-jobs', FavoriteJobController::class);
    Route::resource('applied-jobs', AppliedJobController::class);
    Route::post('users/{user}/ban', [UserController::class, 'ban'])->name('users.ban');
    Route::post('users/{user}/unban', [UserController::class, 'unban'])->name('users.unban');
});
