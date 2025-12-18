<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BuildingController;
use App\Http\Controllers\DashboardController;

// Redirect root ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// Routes Authentication
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('auth.login.post');
// Ensure route name matches view usage
Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');

// Group Protected Routes
Route::middleware(['auth'])->group(function () {

    // Pemilihan Gedung
    Route::get('/select-building', [DashboardController::class, 'selectBuilding'])->name('building.select');
    Route::post('/select-building', [DashboardController::class, 'setBuilding'])->name('building.set');

    // Manajemen Gedung (CRUD)
    Route::get('/building/create', [BuildingController::class, 'create'])->name('building.create');
    Route::post('/building', [BuildingController::class, 'store'])->name('building.store');
    // Opsional: jika ingin list terpisah
    Route::get('/buildings', [BuildingController::class, 'index'])->name('buildings.index');

    // Dashboard Utama
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

});
