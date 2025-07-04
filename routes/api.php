<?php

use App\Http\Controllers\EntityController;
use App\Http\Controllers\ReportController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/tickets/pdf', [ReportController::class, 'generatePdf']);
Route::post('/reports/tickets/json', [ReportController::class, 'fetchTickets'])->name('reports.tickets.json');
Route::post('/tickets', [ReportController::class, 'fetchTickets']);

Route::get('/entities', [EntityController::class, 'getEntities']);
