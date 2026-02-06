<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocationController;
use App\Http\Middleware\IsClient;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Storage\StorageController;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\DashboardController;

// Route::middleware(['auth'])->group(function () {
//     Route::view('/dashboard', 'dashboard.dashboard');
// });


Route::middleware(['auth'])
    ->name('dashboard.')
    ->prefix('dashboard')
    ->group(function () {

        Route::get('/', [DashboardController::class, 'index'])->name('index');
    });
