<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;

// Redirect root ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// Route Authentication
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Route yang butuh Login (Protected)
Route::middleware(['auth'])->group(function () {

    // [PENTING] Route ini target redirect dari AuthController
    Route::get('/select-building', [DashboardController::class, 'selectBuilding'])->name('building.select');
    Route::post('/select-building', [DashboardController::class, 'setBuilding'])->name('building.set');

    // Route Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});
