<?php

use App\Http\Middleware\AllowIframe;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\TicketController as AdminTicketController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/widget', function () {
    return view('widget');
})->middleware(AllowIframe::class);

Route::prefix('admin')->group(function () {
    Route::get('/tickets', [AdminTicketController::class, 'index'])->name('admin.tickets.index');
    Route::get('/tickets/{ticket}', [AdminTicketController::class, 'show'])->name('admin.tickets.show');
    Route::patch('/tickets/{ticket}', [AdminTicketController::class, 'update'])->name('admin.tickets.update');
});
