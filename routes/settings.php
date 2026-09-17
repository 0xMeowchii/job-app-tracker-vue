<?php

use App\Http\Controllers\Settings\JobSourceController;
use App\Http\Controllers\Settings\ProfileController;
use App\Http\Controllers\Settings\SecurityController;
use Illuminate\Auth\Middleware\RequirePassword;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', '/settings/profile');

    Route::get('settings/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('settings/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('settings/job-sources', [JobSourceController::class, 'index'])->name('job-sources.index');
    Route::post('settings/job-sources', [JobSourceController::class, 'store'])->name('job-sources.store');
    Route::patch('settings/job-sources/{job_source}', [JobSourceController::class, 'update'])->name('job-sources.update');
    Route::delete('settings/job-sources/{job_source}', [JobSourceController::class, 'destroy'])->name('job-sources.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::delete('settings/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('settings/security', [SecurityController::class, 'edit'])
        ->middleware(RequirePassword::class)
        ->name('security.edit');

    Route::put('settings/password', [SecurityController::class, 'update'])
        ->middleware('throttle:6,1')
        ->name('user-password.update');

    Route::inertia('settings/appearance', 'settings/Appearance')->name('appearance.edit');
});
