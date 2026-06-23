<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RedeemController;
use App\Http\Controllers\Api\EventCategoryController;
use App\Http\Controllers\Api\StageController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\PerformerController;
use App\Http\Controllers\Api\EventScheduleController;
use App\Http\Controllers\Api\EventMediaController;

// Event & Konser
Route::apiResource('event-categories', EventCategoryController::class);
Route::apiResource('stages', StageController::class);
Route::apiResource('events', EventController::class);
Route::apiResource('performers', PerformerController::class);
Route::apiResource('event-schedules', EventScheduleController::class);
Route::apiResource('event-media', EventMediaController::class);

// Order payment
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/midtrans-callback', [PaymentController::class, 'webhook']);
Route::post('/payment-callback', [PaymentController::class, 'webhook']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/points', [RedeemController::class, 'myPoints']);
    Route::post('/redeem', [RedeemController::class, 'redeem']);
});
