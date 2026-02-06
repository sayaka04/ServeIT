<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\HaversineFormulaController;
use App\Http\Middleware\IsClient;
use GuzzleHttp\Middleware;
use App\Http\Middleware\IsTechnician;
// IsTechnician::class]

Route::middleware(['auth'])->group(function () {
    Route::middleware([IsClient::class])->group(function () {
        // Route for testing the paginated web view (pass parameters as query string)
        Route::get('/technicians/test-search-web', [SearchController::class, 'testSearchTechniciansWeb'])->name('technicians.test_search.web');

        // Route for testing the paginated JSON API response
        Route::get('/technicians/test-search-api', [SearchController::class, 'testSearchTechniciansAPI'])->name('technicians.test_search.api');

        //Functions
        Route::post('/haversine-calculate', [HaversineFormulaController::class, 'calculateDistance'])->name('haversine.calculate');
        Route::post('/haversine-boundary', [HaversineFormulaController::class, 'findBoundaryCoordinates'])->name('haversine.boundary');
        Route::post('/technician/search2', [SearchController::class, 'searchTechnicians2'])->name('technicians.search.result');

        Route::get('/search', [SearchController::class, 'search'])->name('search');
    });
});
