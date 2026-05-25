<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JobApplicationController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'auth/Login')->name('home');

Route::middleware(['auth'])->group(function () {
<<<<<<< Updated upstream
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
    Route::resource('JobApplications', JobApplicationController::class);
=======
    Route::get('dashboard', DashboardController::class)->name('dashboard');
    Route::resource('JobApplication', JobApplicationController::class);
>>>>>>> Stashed changes

});

require __DIR__.'/settings.php';
