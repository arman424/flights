<?php

use App\Http\Controllers\FlightController;
use Illuminate\Support\Facades\Route;

Route::middleware('api.key')->group(function () {
    Route::post('/flights', [FlightController::class, 'store']);
});

