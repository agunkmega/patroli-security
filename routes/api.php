<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ScanController;
use App\Http\Controllers\Api\EmergencyController;
use App\Http\Controllers\Api\PatrolController;
use App\Http\Controllers\Api\AttendanceController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Dashboard
    Route::get('/dashboard', [PatrolController::class, 'dashboard']);

    // Scan
    Route::post('/scan', [ScanController::class, 'scanCheckpoint']);
    Route::get('/checkpoint/{code}', [ScanController::class, 'getCheckpoint']);
    Route::post('/validate-checkpoint', [PatrolController::class, 'validateCheckpoint']);

    // Patrol
    Route::post('/patrol/start', [PatrolController::class, 'startPatrol']);
    Route::get('/patrol/active', [PatrolController::class, 'activePatrol']);
    Route::get('/patrol/history', [PatrolController::class, 'history']);
    Route::get('/patrol/{patrol}', [PatrolController::class, 'show']);
    Route::post('/patrol/{patrol}/complete', [PatrolController::class, 'complete']);

    // Areas & Schedules
    Route::get('/areas', [PatrolController::class, 'areas']);
    Route::get('/schedules', [PatrolController::class, 'schedules']);

    // Emergency
    Route::get('/emergency', [EmergencyController::class, 'index']);
    Route::get('/emergency/{report}', [EmergencyController::class, 'show']);
    Route::post('/emergency/sos', [EmergencyController::class, 'sendSOS']);

    // Attendance
    Route::get('/attendance', [AttendanceController::class, 'index']);
    Route::get('/attendance/today', [AttendanceController::class, 'today']);
    Route::post('/attendance/check-in', [AttendanceController::class, 'checkIn']);
    Route::post('/attendance/check-out', [AttendanceController::class, 'checkOut']);
});
