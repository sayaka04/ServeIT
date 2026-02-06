<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ActivityController,
    ProfileController,
    Storage\StorageController,
};
use App\Http\Middleware\IsClient;
use App\Http\Middleware\IsVerified;


use App\Http\Controllers\CryptoDemoController;

// Home and Dashboard Routes
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/dashboard/old', function () {
    return redirect()->route('dashboard.index');
})->middleware(['auth', 'verified'])->name('dashboard');


// Profile Routes (Auth Middleware)
Route::middleware('auth')->group(function () {
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
});

// Authentication Routes
require __DIR__ . '/auth.php';

//Arranged
require __DIR__ . '/web_partials/archive.php';
require __DIR__ . '/web_partials/register.php';

Route::middleware([IsVerified::class])->group(
    function () {
        require __DIR__ . '/web_partials/admin.php';
        require __DIR__ . '/web_partials/client.php';
        require __DIR__ . '/web_partials/conversation.php';
        require __DIR__ . '/web_partials/repair.php';
        require __DIR__ . '/web_partials/report.php';
        require __DIR__ . '/web_partials/search.php';
        require __DIR__ . '/web_partials/technician.php';
        require __DIR__ . '/web_partials/notification.php';
        require __DIR__ . '/web_partials/dashboard.php';
        require __DIR__ . '/web_partials/user.php';
    }
);


Route::view('/repair-progress-email', 'mail.repair-progress-email');

Route::view('/terms', 'terms');
Route::view('/privacy', 'privacy');
Route::view('/about', 'about');

Route::view('/layout', 'technician.dashboard');

// Route::get('/encrypt/{data}', [CryptoDemoController::class, 'encrypt']);
// Route::get('/decrypt/{data}', [CryptoDemoController::class, 'decrypt']);
// Route::get('/aes256/{data}', [CryptoDemoController::class, 'demoAES256']);
