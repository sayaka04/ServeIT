<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\ClientConversationController;
// use App\Http\Controllers\Technician\RepairController as TechnicianRepairController;

use App\Http\Controllers\RepairController as OldRepairController;
use App\Http\Controllers\Table\AdminActionController;
use App\Http\Controllers\Table\ExpertiseCategoryController;
use App\Http\Controllers\Table\RepairController;
use App\Http\Controllers\Table\RepairProgressController;
use App\Http\Controllers\Table\RepairRatingController;
use App\Http\Middleware\IsAdmin;

//-----------------------------------------------------------------------------------------------------------------------------
//Admin
//-----------------------------------------------------------------------------------------------------------------------------
Route::middleware(['auth'])->group(function () {

    Route::middleware([IsAdmin::class])->group(function () {
        Route::patch('/admins/{user}/toggle-disable', [AdminController::class, 'toggleDisable'])->name('admins.toggleDisable');

        Route::resource('admins', AdminController::class);
        Route::resource('admin-actions', AdminActionController::class);



        Route::put('expertise-categories/{expertiseCategory}/archive', [ExpertiseCategoryController::class, 'archive'])
            ->name('expertise-categories.archive');
        Route::put('expertise-categories/{expertiseCategory}/restore', [ExpertiseCategoryController::class, 'restore'])
            ->name('expertise-categories.restore');
        Route::resource('expertise-categories', ExpertiseCategoryController::class);
    });
});
