<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\GuardController;
use App\Http\Controllers\Admin\AreaController;
use App\Http\Controllers\Admin\CheckpointController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Supervisor\DashboardController as SupervisorDashboard;
use App\Http\Controllers\Guard\DashboardController as GuardDashboard;
use App\Http\Controllers\Guard\PatrolController;
use App\Http\Controllers\EmergencyController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\QRCodeController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {

    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

        Route::resource('guards', GuardController::class)->except(['show']);
        Route::get('guards/{guard}', [GuardController::class, 'show'])->name('guards.show');

        Route::resource('areas', AreaController::class)->except(['show']);
        Route::get('checkpoints/print-all-qr', [CheckpointController::class, 'printAllQR'])->name('checkpoints.print-all-qr');
        Route::resource('checkpoints', CheckpointController::class);
        Route::post('checkpoints/{checkpoint}/generate-qr', [CheckpointController::class, 'generateQR'])->name('checkpoints.generate-qr');
        Route::get('checkpoints/{checkpoint}/print-qr', [CheckpointController::class, 'printQR'])->name('checkpoints.print-qr');

        Route::resource('schedules', ScheduleController::class);
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/export-excel', [ReportController::class, 'exportExcel'])->name('reports.export-excel');
        Route::get('reports/export-pdf', [ReportController::class, 'exportPDF'])->name('reports.export-pdf');
    });

    Route::middleware(['role:supervisor'])->prefix('supervisor')->name('supervisor.')->group(function () {
        Route::get('/dashboard', [SupervisorDashboard::class, 'index'])->name('dashboard');
        Route::get('/monitoring', [SupervisorDashboard::class, 'monitoring'])->name('monitoring');
    });

    Route::middleware(['role:guard'])->prefix('guard')->name('guard.')->group(function () {
        Route::get('/dashboard', [GuardDashboard::class, 'index'])->name('dashboard');

        Route::get('/patrol/start', [PatrolController::class, 'startPatrol'])->name('patrol.start');
        Route::post('/patrol/begin', [PatrolController::class, 'beginPatrol'])->name('patrol.begin');
        Route::get('/patrol/active', [PatrolController::class, 'activePatrol'])->name('patrol.active');
        Route::get('/patrol/{patrol}/scan', [PatrolController::class, 'scan'])->name('patrol.scan');
        Route::post('/patrol/{patrol}/scan', [PatrolController::class, 'processScan'])->name('patrol.scan.process');
        Route::post('/patrol/{patrol}/complete', [PatrolController::class, 'completePatrol'])->name('patrol.complete');
        Route::get('/history', [PatrolController::class, 'history'])->name('history');
    });

    Route::prefix('emergency')->name('emergency.')->group(function () {
        Route::get('/', [EmergencyController::class, 'index'])->name('index');
        Route::get('/create', [EmergencyController::class, 'create'])->name('create');
        Route::post('/store', [EmergencyController::class, 'store'])->name('store');
        Route::post('/{report}/respond', [EmergencyController::class, 'respond'])->name('respond');
        Route::post('/{report}/resolve', [EmergencyController::class, 'resolve'])->name('resolve');
    });

    Route::prefix('attendance')->name('attendance.')->group(function () {
        Route::get('/', [AttendanceController::class, 'index'])->name('index');
        Route::post('/check-in', [AttendanceController::class, 'checkIn'])->name('check-in');
        Route::post('/check-out', [AttendanceController::class, 'checkOut'])->name('check-out');
    });

    Route::prefix('qrcode')->name('qrcode.')->group(function () {
        Route::get('/print/{checkpoint}', [QRCodeController::class, 'print'])->name('print');
        Route::get('/print-all', [QRCodeController::class, 'printAll'])->name('print-all');
        Route::get('/download/{checkpoint}', [QRCodeController::class, 'download'])->name('download');
    });
});
