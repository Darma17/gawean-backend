sssssssssssssssssssssssssssssss<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\CompanyProfileController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\FavoriteJobController;
use App\Http\Controllers\AppliedJobController;

Route::get('/', function () {
    return view('welcome');
});

// Resource Routes
Route::resource('users', UserController::class);
Route::resource('user-profiles', UserProfileController::class);
Route::resource('company-profiles', CompanyProfileController::class);
Route::resource('jobs', JobController::class);
Route::resource('favorite-jobs', FavoriteJobController::class);
Route::resource('applied-jobs', AppliedJobController::class);
