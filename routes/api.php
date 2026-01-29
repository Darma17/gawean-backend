<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserProfileController;
use App\Http\Controllers\Api\JobController;
use App\Http\Controllers\Api\FavoriteJobController;
use App\Http\Controllers\Api\AppliedJobController;
use App\Http\Controllers\Api\CompanyProfileController;

/*
|--------------------------------------------------------------------------
| API Routes untuk User (Android App)
|--------------------------------------------------------------------------
*/

// Route publik untuk autentikasi user
Route::prefix('user')->group(function () {
    // Login - mengecek email, password, dan role (user/perusahaan), lalu kirim OTP
    Route::post('/login', [AuthController::class, 'login']);
    
    // Verifikasi OTP
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
    
    // Kirim ulang OTP
    Route::post('/resend-otp', [AuthController::class, 'resendOtp']);
    
    // Register user baru
    Route::post('/register', [AuthController::class, 'register']);
    
    // Forgot password - kirim OTP
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    
    // Verifikasi OTP untuk reset password
    Route::post('/verify-reset-otp', [AuthController::class, 'verifyResetOtp']);
    
    // Reset password
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
});

// Route yang memerlukan autentikasi
Route::middleware('auth:sanctum')->prefix('user')->group(function () {
    // Get data user yang sedang login
    Route::get('/me', [AuthController::class, 'me']);
    
    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // Profile routes
    Route::get('/profile', [UserProfileController::class, 'getProfile']);
    Route::post('/profile', [UserProfileController::class, 'updateProfile']);
    Route::delete('/profile/certificate/{id}', [UserProfileController::class, 'deleteCertificate']);

    // Company Profile routes (khusus untuk role perusahaan)
    Route::get('/company-profile', [CompanyProfileController::class, 'getProfile']);
    Route::post('/company-profile', [CompanyProfileController::class, 'updateProfile']);

    // Company Jobs routes (khusus untuk role perusahaan)
    Route::get('/company/jobs/count', [JobController::class, 'countCompanyJobs']);
    Route::get('/company/jobs', [JobController::class, 'getCompanyJobs']);
    Route::get('/company/jobs/{jobId}', [JobController::class, 'getJobDetailCompany']);
    Route::get('/company/jobs/{jobId}/accepted-applicants', [JobController::class, 'getJobAcceptedApplicants']);
    Route::post('/company/jobs', [JobController::class, 'store']);
    Route::patch('/company/jobs/{jobId}', [JobController::class, 'update']);
    Route::delete('/company/jobs/{jobId}', [JobController::class, 'destroy']);
    Route::get('/company/jobs/{jobId}/applicants', [JobController::class, 'getJobApplicants']);
    Route::get('/company/jobs/{jobId}/applicants/{applicantId}/detail', [JobController::class, 'getApplicantDetail']);
    Route::patch('/company/jobs/{jobId}/applicants/{applicantId}/status', [JobController::class, 'updateApplicantStatus']);
    Route::patch('/company/jobs/{jobId}/applicants/{applicantId}/reject', [JobController::class, 'rejectApplicant']);
    Route::get('/company/jobs/applicants/count', [JobController::class, 'countJobApplicants']);
    Route::get('/company/accepted-applicants', [JobController::class, 'getAcceptedApplicants']);

    // Favorite Jobs routes
    Route::get('/favorites', [FavoriteJobController::class, 'index']);
    Route::post('/favorites', [FavoriteJobController::class, 'store']);
    Route::post('/favorites/toggle', [FavoriteJobController::class, 'toggle']);
    Route::get('/favorites/check/{jobId}', [FavoriteJobController::class, 'check']);
    Route::delete('/favorites/{jobId}', [FavoriteJobController::class, 'destroy']);

    // Applied Jobs routes
    Route::get('/applied', [AppliedJobController::class, 'index']);
    Route::post('/applied', [AppliedJobController::class, 'store']);
    Route::get('/applied/count', [AppliedJobController::class, 'count']);
    Route::get('/applied/check/{jobId}', [AppliedJobController::class, 'check']);
    Route::get('/applied/{id}', [AppliedJobController::class, 'show']);
    Route::delete('/applied/{id}', [AppliedJobController::class, 'destroy']);
    Route::patch('/applied/{id}/confirm', [AppliedJobController::class, 'confirmOffer']);
    Route::patch('/applied/{id}/reject', [AppliedJobController::class, 'rejectOffer']);
});


/*
|--------------------------------------------------------------------------
| API Routes untuk Jobs
|--------------------------------------------------------------------------
*/

// Route publik untuk melihat lowongan
Route::prefix('jobs')->group(function () {
    Route::get('/', [JobController::class, 'index']);
    Route::get('/cities', [JobController::class, 'getCities']);
    Route::get('/{id}', [JobController::class, 'show']);
});
