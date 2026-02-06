<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Table\ConversationController;
use App\Http\Controllers\Table\ConversationMessageController;




Route::middleware(['auth'])->group(function () {
    Route::resource('conversations', ConversationController::class)->only(['index', 'store', 'show']);
    Route::post('/conversations/{id}/update-last-seen', [ConversationController::class, 'updateLastSeen'])->name('conversations.updateLastSeen');
    Route::get('/conversations/{conversation}/all', [ConversationController::class, 'showAll'])->name('conversations.showAll');
});




Route::middleware(['auth'])->group(function () {
    Route::resource('messages', ConversationMessageController::class)->only(['store']);
});
