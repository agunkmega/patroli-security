<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ScanController;
use App\Http\Controllers\Api\EmergencyController;
use App\Http\Controllers\Api\PatrolController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/scan', [ScanController::class, 'scanCheckpoint']);
    Route::get('/checkpoint/{code}', [ScanController::class, 'getCheckpoint']);

    Route::post('/emergency/sos', [EmergencyController::class, 'sendSOS']);

    Route::post('/patrol/start', [PatrolController::class, 'startPatrol']);
    Route::get('/patrol/active', [PatrolController::class, 'activePatrol']);
    Route::get('/patrol/history', [PatrolController::class, 'history']);
});
