<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BuildingController;

// Redirect root ke login (jika belum login)
Route::get('/', function () {
    return redirect()->route('login');
});

// Routes untuk autentikasi (login / logout)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Group route yang butuh user sudah login (middleware auth)
Route::middleware(['auth'])->group(function () {

    // Halaman pilih gedung setelah login
    Route::get('/select-building', [DashboardController::class, 'selectBuilding'])->name('building.select');
    Route::post('/select-building', [DashboardController::class, 'setBuilding'])->name('building.set');

    // Daftar gedung / buildings (misal overview daftar gedung)
    Route::get('/buildings', [BuildingController::class, 'index'])->name('buildings.index');

    // Dashboard utama setelah memilih gedung
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

});
