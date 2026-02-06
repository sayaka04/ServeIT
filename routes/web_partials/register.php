<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Authenticate\LoginController;

Route::view('/register', 'authentication.register')->name('register');
Route::view('/register/client', 'authentication.register-client')->name('register-client');
// ✅ CORRECT: Use Route::get for controllers
Route::get('/register/technician', [LoginController::class, 'showRegistrationForm'])->name('register-technician');
// Route::view('/register/technician', 'authentication.register-technician')->name('register-technician');

Route::post('/register/client/create', [LoginController::class, 'registerClient'])->name('register-client-create');
Route::post('/register/technician/create', [LoginController::class, 'registerTechnician'])->name('register-technician-create');
