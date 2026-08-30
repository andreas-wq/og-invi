<?php

use App\Http\Controllers\GuestbookController;
use Illuminate\Support\Facades\Route;

Route::get('/', [GuestbookController::class, 'page'])->name('home');

Route::get('/guestbook/messages', [GuestbookController::class, 'index'])
    ->name('guestbook.index');

Route::post('/guestbook', [GuestbookController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('guestbook.store');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
