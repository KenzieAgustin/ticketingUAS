<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\StageWebController;
use App\Http\Controllers\Web\EventCategoryWebController;
use App\Http\Controllers\Web\EventWebController;
use App\Http\Controllers\Web\PerformerWebController;
use App\Http\Controllers\Web\EventScheduleWebController;
use App\Http\Controllers\Web\EventMediaWebController;

Route::get('/', function () {
    return redirect()->route('web.events.index');
});

Route::resource('stages', StageWebController::class)->names('web.stages');
Route::resource('event-categories', EventCategoryWebController::class)->names('web.event-categories');
Route::resource('events', EventWebController::class)->names('web.events');
Route::resource('performers', PerformerWebController::class)->names('web.performers');
Route::resource('event-schedules', EventScheduleWebController::class)->names('web.event-schedules');
Route::resource('event-media', EventMediaWebController::class)->names('web.event-media');