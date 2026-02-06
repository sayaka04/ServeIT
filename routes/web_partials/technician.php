<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\Table\TechnicianController;
use App\Http\Controllers\Table\TechnicianFileController;
use App\Http\Controllers\Table\TechnicianLinkController;
use App\Http\Middleware\IsClient;
use App\Http\Middleware\IsTechnician;

// Route::middleware(['auth'])->group(function () {
//     // Resource route
//     Route::resource('technicians', TechnicianController::class)->only(['show']);
//     // Route::get('technicians/{technician:id}', [TechnicianController::class, 'show']);
// });

Route::middleware(['auth'])->group(function () {

    Route::resource('technicians', TechnicianController::class)->only(['show']);


    Route::middleware([IsTechnician::class])->group(function () {
        // 1. GET: Route for the Expertise Management Form
        Route::get('/technician/{technician}/expertise/manage', [TechnicianController::class, 'editExpertise'])
            ->name('technician.expertise.manage');

        Route::put('/technician/{technician}/expertise/manage', [TechnicianController::class, 'updateExpertise'])
            ->name('technician.expertise.update');


        // Route for Technician Location Update
        Route::post('/technician/update-location', [TechnicianController::class, 'updateLocation'])
            ->name('technician.update.location');


        Route::resource('technicians', TechnicianController::class)->only(['index', 'store', 'update']);
        // Route::get('technicians/{technician:id}', [TechnicianController::class, 'show']);

        Route::resource('technician-files', TechnicianFileController::class);
        Route::resource('technician-links', TechnicianLinkController::class);


        Route::post('/technicians/banner/change', [TechnicianController::class, 'changeBannerPicture'])
            ->middleware('auth')
            ->name('technicians.banner.change');
    });
});




// Route::get('/technician/profile/{technician_code}', [SearchController::class, 'getProfile'])->name('technician.profile');

// Route::get('/check-verified', function () {
//     $url = 'https://t2mis.tesda.gov.ph/Rwac/IndexProc2?searchLName=limama&searchFName=Richard&firstFour=2411&lastFour=5536';
//     $html = @file_get_contents($url);

//     if ($html === false) {
//         return 'Failed to fetch the page.';
//     }

//     if (stripos($html, 'Verified') !== false) {
//         return '"Verified" found in page.';
//     }
//     if (stripos($html, 'No Data Found') !== false) {
//         $message = 'No Data Found';
//     } else {
//         $message = 'Something went wrong';
//     }

//     return $message;
// });
