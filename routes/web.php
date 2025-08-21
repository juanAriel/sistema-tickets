<?php

use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/',[TicketController::class, 'formTicket'])->name('tickets.form');
Route::post('/tickets/generateTicket', [TicketController::class, 'generateTicket'])->name('tickets.generate');

Route::get('/panel/{type}', [TicketController::class, 'panel'])->name('panel');
Route::post('/panel/{type}/next', [TicketController::class, 'next'])->name('panel.next');

Route::get('/monitor/{type}', [TicketController::class, 'monitor'])->name('monitor');
Route::get('/status/{type}', [TicketController::class, 'status'])->name('status');