<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\ClientConversationController;
// use App\Http\Controllers\Technician\RepairController as TechnicianRepairController;

use App\Http\Controllers\RepairController as OldRepairController;
use App\Http\Controllers\Table\RepairCancelRequestController;
use App\Http\Controllers\Table\RepairController;
use App\Http\Controllers\Table\RepairProgressController;
use App\Http\Controllers\Table\RepairRatingController;
use App\Http\Middleware\IsClient;
use App\Http\Middleware\IsTechnician;

//-----------------------------------------------------------------------------------------------------------------------------
//Repair
//-----------------------------------------------------------------------------------------------------------------------------
Route::middleware(['auth'])->group(function () {
    Route::middleware([IsTechnician::class])->group(function () {
        Route::put('/repairs/{repair}/received', [RepairController::class, 'received'])->name('repairs.received');
        Route::post('/repairs/{repair}/add-breakdown', [RepairController::class, 'addBreakdown'])->name('repairs.addBreakdown');
    });

    Route::middleware([IsClient::class])->group(function () {
        // Route::post('/repairs/{repair}/accept', [RepairController::class, 'accept'])->name('repairs.accept');
        // Route::post('/repairs/{repair}/decline', [RepairController::class, 'decline'])->name('repairs.decline');
    });


    Route::post('/repairs/{repair}/accept', [RepairController::class, 'accept'])->name('repairs.accept');
    Route::post('/repairs/{repair}/decline', [RepairController::class, 'decline'])->name('repairs.decline');


    Route::post('/repairs/{id}/client-confirm', [RepairController::class, 'clientFinalConfirmation'])->name('repairs.clientConfirm');

    Route::put('/repairs/{repair}/claimed', [RepairController::class, 'claimed'])->name('repairs.claimed');


    // Route for Technicians/Admins to update the breakdown
    Route::put('/repairs/{id}/update-breakdown', [RepairController::class, 'updateBreakdown'])->name('repairs.updateBreakdown');

    Route::get('/repairs/history', [RepairController::class, 'history'])->name('repairs.history');

    //Repair Revisions
    Route::get('/repairs/{repair}/revision', [RepairController::class, 'show'])->name('repairs.show.revision');




    Route::put('/repairs/{repair}/cancel', [RepairCancelRequestController::class, 'cancel'])->name('repairs.cancel');
    Route::post('/cancel-requests/{id}/accept', [RepairCancelRequestController::class, 'accept'])->name('cancel_requests.accept');
    Route::post('/cancel-requests/{id}/decline', [RepairCancelRequestController::class, 'decline'])->name('cancel_requests.decline');

    // Resource route
    Route::resource('repairs', RepairController::class)->only(['index', 'store', 'show', 'update', 'create']);
    Route::resource('repair_ratings', RepairRatingController::class);
    Route::resource('repair-cancel-requests', RepairCancelRequestController::class);
});

//-----------------------------------------------------------------------------------------------------------------------------
//Repair Progress
//-----------------------------------------------------------------------------------------------------------------------------
Route::middleware(['auth', IsTechnician::class])->group(function () {
    Route::resource('repair-progress', RepairProgressController::class)->only(['store', 'update']);
});












































// web.php or api.php
// Route::post('/repairs/accept/{repair}', [ClientConversationController::class, 'acceptRepair'])->name('acceptRepair');
// Route::post('/repairs/decline/{repair}', [ClientConversationController::class, 'declineRepair'])->name('declineRepair');

    
// (/)
// Route::post('/create-repair', [ClientConversationController::class, 'createRepair']);
// Route::get('/repair/lists', [OldRepairController::class, 'repairLists']);
// Route::get('/repair/{id}', [OldRepairController::class, 'getRepair'])->name('getRepair');
// Route::put('/repair/{id}', [OldRepairController::class, 'update'])->name('repair.update');

// (x)
// Route::post('/repair-progress/store', [OldRepairController::class, 'store'])->name('repair-progress.store');
// Route::put('/repair/progress/{id}', [OldRepairController::class, 'updateProgress'])->name('repair.progress.update');

//Technician




//Client





// Route::middleware(['auth'])
//     ->name('repairs.')
//     ->prefix('repairs')
//     ->group(function () {

        // Route::resource('repairs', TechnicianRepairController::class)->except(['destroy']);
        // Route::resource('repairs', TechnicianRepairController::class)->only(['destroy']);
        // Route::resource('repairs', TechnicianRepairController::class)->only(['store, show, destroy']);



    // });
