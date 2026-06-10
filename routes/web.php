<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RefundController;
use App\Http\Controllers\RedeemController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/my-orders', [OrderController::class, 'index'])->name('order.index');
Route::get('/my-orders/{id}', [OrderController::class, 'show'])->name('order.show');


Route::post('/checkout', [App\Http\Controllers\OrderController::class, 'checkout']);

Route::get('/checkout', function () {
    return view('checkout');
});

Route::post('/check-voucher', [OrderController::class, 'checkVoucher'])->name('check.voucher');

Route::post('/order/{id}/refund', [RefundController::class, 'store'])->name('refund.store');
Route::get('/admin/refunds', [\App\Http\Controllers\RefundController::class, 'index'])->name('admin.refunds.index');
Route::post('/admin/refunds/{id}/approve', [\App\Http\Controllers\RefundController::class, 'approve'])->name('admin.refunds.approve');
Route::post('/admin/refunds/{id}/reject', [\App\Http\Controllers\RefundController::class, 'reject'])->name('admin.refunds.reject');

Route::get('/perbaiki-kolom-status', function () {
    DB::statement("ALTER TABLE orders MODIFY status VARCHAR(255) DEFAULT 'pending'");
    return 'Kolom status di tabel orders berhasil diperbaiki! Sekarang bisa nampung refund_pending.';
});


Route::get('/points', [RedeemController::class, 'myPoints']);
Route::post('/redeem', [RedeemController::class, 'redeem']);


