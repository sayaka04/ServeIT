<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Table\UserController;
use App\Http\Middleware\IsAdmin;

Route::middleware(['auth'])->group(function () {



    Route::middleware([IsAdmin::class])->group(function () {
        Route::resource('users', UserController::class)->except('edit');
    });

    Route::resource('users', UserController::class)->only('show');


    Route::post('/users/picture/change', [UserController::class, 'changeProfilePicture'])
        ->middleware('auth')
        ->name('users.picture.change');
});
