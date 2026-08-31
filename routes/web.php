<?php

use App\Http\Controllers\Admin\GuestbookAdminController;
use App\Http\Controllers\Admin\InviteController;
use App\Http\Controllers\GuestbookController;
use Illuminate\Support\Facades\Route;

Route::get('/', [GuestbookController::class, 'page'])->name('home');
Route::get('/v2', [GuestbookController::class, 'page2'])->name('home2');

Route::get('/guestbook/messages', [GuestbookController::class, 'index'])
    ->name('guestbook.index');

Route::post('/guestbook', [GuestbookController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('guestbook.store');

// Netralkan beacon telemetri Cloudflare (RUM) bawaan modul WeddingPress:
// di lokal tidak ada Cloudflare, jadi beri respons kosong agar console bersih.
Route::post('/cdn-cgi/rum', fn () => response()->noContent())->name('cf.rum');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'dashboard')->name('dashboard');

    Route::prefix('admin')->name('admin.')->middleware('can:manage-guestbook')->group(function () {
        Route::get('guestbook', [GuestbookAdminController::class, 'index'])
            ->name('guestbook.index');
        Route::post('guestbook/{guestbook_message}/reply', [GuestbookAdminController::class, 'reply'])
            ->name('guestbook.reply');
        Route::delete('guestbook/{guestbook_message}', [GuestbookAdminController::class, 'destroy'])
            ->name('guestbook.destroy');
             
Route::get('/invite', [InviteController::class, 'index'])->name('invite');
    });
});

require __DIR__.'/settings.php';
