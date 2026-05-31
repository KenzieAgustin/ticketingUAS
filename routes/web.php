<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TicketZoneController;
use App\Http\Controllers\WaitListController;
use App\Http\Controllers\TicketTokenController;
use App\Http\Controllers\PricingRuleController;

//masih redirect ke tiket karena belum ada login 
Route::get('/', function () {
    return redirect('/tickets');
});

Route::get('/view-tickets', [TicketController::class, 'indexWeb']);
//daftar tiket dan zonanya
Route::get('/tickets', [TicketController::class, 'indexWeb']);
Route::post('/tickets', [TicketController::class, 'store']);

Route::get('/tickets/{id}/buy',[TicketController::class, 'buyWeb']);
Route::get('/scan', [TicketTokenController::class, 'scanWeb']);

Route::post('/tokens/validate', [TicketTokenController::class, 'validateToken']);

Route::get('/zones/{ticket_id}', [TicketZoneController::class, 'getZoneByTicket']);
Route::post('/zones/reduce', [TicketZoneController::class, 'reduceQuota']);

Route::get('/tickets/{id}/calculate-price', [PricingRuleController::class, 'calculateFinalPrice']);

Route::post('/waitlist/join', [WaitListController::class, 'joinWaitList']);

Route::post('/tokens/generate', [TicketTokenController::class, 'generateToken']);

Route::get('/admin/tickets', [TicketController::class, 'adminWeb']);