<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('conversations.chat.index');
    }
    return view('welcome');
})->name('home');

Route::get('/dashboard', function () {
    return redirect()->route('conversations.chat.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::name('profile.')->controller(ProfileController::class)->group(function () {

        Route::get('/profile', 'edit')->name('edit');
        Route::patch('/profile', 'update')->name('update');
        Route::delete('/profile', 'destroy')->name('destroy');
    });



    Route::name('conversations.')->controller(ChatController::class)->group(function () {

        Route::get('/chat', 'index')->name('chat.index');
        Route::get('/conversations', 'list')->name('index');
        Route::post('/conversations', 'createConversation')->name('create');
        Route::get('/conversations/{id}', 'show')->name('show');
        Route::delete('/conversations/{id}', 'delete')->name('delete');
        Route::post('/chat/send', 'sendMessage')->name('api.chat.send')->middleware('throttle.request');
        Route::get('/models', 'getAvailableModels')->name('models');

    });

});

require __DIR__ . '/auth.php';