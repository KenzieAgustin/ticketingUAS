<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GateController;

Route::get('/test', function () {
    return response()->json(['message' => 'Hello, World!']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/gates', [GateController::class, 'index']);
    Route::get('/gates/{gate}', [GateController::class, 'show']);
    Route::post('/gates', [GateController::class, 'store']);
    Route::put('/gates/{gate}', [GateController::class, 'update']);
    Route::delete('/gates/{gate}', [GateController::class, 'destroy']);
});