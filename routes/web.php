<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/my-orders', [OrderController::class, 'index'])->name('order.index');
Route::get('/my-orders/{id}', [OrderController::class, 'show'])->name('order.show');

Route::get('/test-checkout', [OrderController::class, 'checkout']);

