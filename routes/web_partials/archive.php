<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocationController;
use App\Http\Middleware\IsClient;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Storage\StorageController;
use Illuminate\Support\Facades\Mail;


//-----------------------------------------------------------------------------
//These are routes that no longer serve a purpose (Merely old and prototypes)
//-----------------------------------------------------------------------------

Route::view('/haversine', 'prototype/haversine')->name('haversine');
Route::view('/map-sample', 'prototype.map-sample')->name('map-sample');

// Route::view('/search', 'client.search')->name('client-search');


Route::view('/ongoing-repairs', 'client.ongoing_repairs')->name('client-ongoing-repairs');
Route::view('/ongoing-repairs-list', 'client.ongoing_repairs_lists')->name('client-ongoing-repairs_list');

Route::view('/inquiry', 'client/inquiry')->name('client-inquiry');

Route::view('/technician-profile', 'client.technician_profile')->name('client-technician-profile');
Route::view('/technician-technician-profile', 'technician.technician_profile')->name('technician-technician-profile');


// Chat Route
Route::view('/chat', 'chat')->name('chat');
Route::view('/chat-client', 'chat-client')->name('chat-client');



Route::view('technician/ongoing-repairs', 'technician.ongoing_repairs')->name('technician-ongoing-repairs');
Route::view('technician/ongoing-repairs-lists', 'technician.ongoing_repairs_lists')->name('technician-ongoing-repairs_lists');


// Route::get('/map', [LocationController::class, 'showMap'])->name('map');
// Route::get('/my-location', [LocationController::class, 'showLocationPage'])->name('my.location');
// Route::get('/encrytionTesting', [LocationController::class, 'encrytionTesting'])->name('encrytionTesting');

// Admin Routes
Route::middleware(['auth', 'is_admin'])->prefix('admin')->group(function () {
    Route::view('/dashboard-admin', 'admin/dashboard')->name('admin-dashboard');
});

// Technician Routes
Route::middleware(['auth', 'is_technician'])->prefix('technician')->group(function () {
    Route::view('/dashboard-technician', 'technician/dashboard')->name('technician-dashboard');
    Route::view('/notifications', 'technician/notifications')->name('technician-notifications');
    Route::view('/new-repair', 'technician/new_repair')->name('technician-new-repair');
    Route::view('/customers', 'technician/customers')->name('technician-customers');
    Route::view('/repair_history', 'technician/repair_history')->name('technician-repair-history');
});


// Client Routes
Route::middleware(['auth', IsClient::class])->prefix('client')->group(function () {
    Route::view('/dashboard-client', 'client/dashboard')->name('client-dashboard');
    Route::view('/search', 'client/search')->name('client-search');
    Route::view('/inquiry', 'client/inquiry')->name('client-inquiry');
});











//-----------------------------------------------------------------------------------
//IMPORTANT!!!
//-----------------------------------------------------------------------------------

//Verify Technician (Testing Prototype)
Route::get('/check-verified', function () {
    $url = 'https://t2mis.tesda.gov.ph/Rwac/IndexProc2?searchLName=limama&searchFName=Richard&firstFour=2411&lastFour=5536';
    $html = @file_get_contents($url);

    if ($html === false) {
        return 'Failed to fetch the page.';
    }

    if (stripos($html, 'Verified') !== false) {
        return '"Verified" found in page.';
    }
    if (stripos($html, 'No Data Found') !== false) {
        $message = 'No Data Found';
    } else {
        $message = 'Something went wrong';
    }

    return $message;
});




//Send Email Route (Testing Prototype)
Route::get('/send-mail', function () {
    Mail::to('d.franco.536439@umindanao.edu.ph')->send(new \App\Mail\MailSender(
        'Subject Here',
        'This is the body of the email.'
    ));

    return 'Email sent!';
});

// Route::get('/send-notification', function () {
//     Mail::to('d.franco.536439@umindanao.edu.ph')->send(new \App\Mail\MailSenderNotification(
//         'Notification Subject',
//         'This is the body of the email.'
//     ));

//     return 'Email sent!';
// });

// File Storage Routes
Route::get('/file/{filename}', [StorageController::class, 'showFile'])->name('getFile');
Route::get('/file/file/{filename}', [StorageController::class, 'showFile2'])
    ->where('filename', '.*')
    ->name('getFile2');


// In routes/web.php
// In routes/web.php

// Route to handle file download with a dynamic filename pattern (.* allows any file name and path)
Route::get('download-repair-order/{filename}', [StorageController::class, 'downloadRepairOrder'])
    ->where('filename', '.*')
    ->name('downloadRepairOrder');



//--------------------------------------------------------------------------------------------------------------------
//Delete this. This is merely an example.
// Route::get('activities', ActivityController::class)->name('activities.index');

// conversations.admin-dashboard
// comversations/dashboard

// Route::middleware(['auth', 'verified'])->name('conversations.')->prefix('conversations')->group(function() {
//     Route::view('/dashboard', 'admin/dashboard')->name('admin-dashboard');
// });
//--------------------------------------------------------------------------------------------------------------------









// Route::get('/technician/open/conversation/{technician_code}', [ClientConversationController::class, 'conversation']);

// Route::get('/technician/conversation/{conversation_code}', [ClientConversationController::class, 'getConversation'])->name('get.conversation');


// Route::get('/conversation/technician/{conversation_code}', [ClientConversationController::class, 'getConversation'])->name('conversation.technician.get');
// Route::get('/conversation/client/{conversation_code}', [ClientConversationController::class, 'getConversation'])->name('conversation.client.get');
// Route::get('/conversation/lists', [Controller::class, 'getConversationLists'])->name("conversation.lists");
// Route::post('/conversation/send/{conversation_code}', [ClientConversationController::class, 'sendMessage']);
// Route::get('/conversation/new/{conversation_id}', [ClientConversationController::class, 'newMessage']);
// Route::post('/conversation/send/{conversation_code}', [ClientConversationController::class, 'sendMessage']);



// Route::middleware(['auth'])
//     ->name('repairs.')
//     ->prefix('repairs')
//     ->group(function () {

// Route::resource('repairs', TechnicianRepairController::class)->except(['destroy']);
// Route::resource('repairs', TechnicianRepairController::class)->only(['destroy']);
// Route::resource('repairs', TechnicianRepairController::class)->only(['store, show, destroy']);



// });