<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\JobDetailsController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'auth/Login')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', DashboardController::class)->name('dashboard');
    Route::resource('JobApplication', JobApplicationController::class);
});

require __DIR__.'/settings.php';