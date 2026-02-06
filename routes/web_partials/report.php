<?php

use App\Http\Controllers\Table\ReportCategoryController;
use App\Http\Controllers\Table\ReportController;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsClient;
use App\Http\Middleware\IsTechnician;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth'])->group(function () {
    Route::resource('reports', ReportController::class)->only('store');


    Route::middleware([IsAdmin::class])->group(function () {
        Route::get('/reports/user-history/{user}', [ReportController::class, 'userHistory'])->name('reports.userHistory');
        Route::resource('reports', ReportController::class)->only(['index', 'show', 'edit', 'update', 'destroy']);
        Route::resource('report-categories', ReportCategoryController::class);
    });
});
