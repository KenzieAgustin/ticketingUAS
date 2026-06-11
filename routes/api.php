<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EventCategoryController;
use App\Http\Controllers\Api\StageController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\PerformerController;
use App\Http\Controllers\Api\EventScheduleController;
use App\Http\Controllers\Api\EventMediaController;

Route::apiResource('event-categories', EventCategoryController::class);
Route::apiResource('stages', StageController::class);
Route::apiResource('events', EventController::class);
Route::apiResource('performers', PerformerController::class);
Route::apiResource('event-schedules', EventScheduleController::class);
Route::apiResource('event-media', EventMediaController::class);