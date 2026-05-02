<?php

use App\Http\Controllers\FlightController;
use Illuminate\Support\Facades\Route;

Route::middleware('api.key')->group(function () {
    Route::get('/flights/{flightId}', [FlightController::class, 'get']);
    Route::post('/flights', [FlightController::class, 'store']);
    Route::put('/flights/{flightId}', [FlightController::class, 'update']);
});

