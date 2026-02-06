<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocationController;
use App\Http\Middleware\IsClient;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Storage\StorageController;
use App\Http\Controllers\NotificationController;

use Illuminate\Support\Facades\Mail;

Route::middleware(['auth'])->group(function () {
    // Route::view('/notifications', 'notification.notification');



    Route::middleware(['auth'])->group(function () {
        Route::resource('notifications', NotificationController::class)
            ->only(['index', 'show', 'create', 'store']);

        Route::get('/notifications/{id}/view-resource', [NotificationController::class, 'markAsSeenAndRedirect'])->name('notifications.view-resource');
    });
});
