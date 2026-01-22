<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes untuk User (Android App)
|--------------------------------------------------------------------------
*/

// Route publik untuk autentikasi user
Route::prefix('user')->group(function () {
    // Login - mengecek email, password, dan role = user, lalu kirim OTP
    Route::post('/login', [AuthController::class, 'login']);
    
    // Verifikasi OTP
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
    
    // Kirim ulang OTP
    Route::post('/resend-otp', [AuthController::class, 'resendOtp']);
});

// Route yang memerlukan autentikasi
Route::middleware('auth:sanctum')->prefix('user')->group(function () {
    // Get data user yang sedang login
    Route::get('/me', [AuthController::class, 'me']);
    
    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);
});
