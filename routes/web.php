<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BuildingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\RawLuxMotionController;
use App\Http\Controllers\RawPowerController;
use App\Http\Controllers\RawSuhuHumidityController;

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
Route::get('/buildings', [BuildingController::class, 'index'])->name('buildings.index');

// Dashboard Utama
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Power System page (Device Control)
Route::get('/dashboard/power', [DashboardController::class, 'power'])->name('dashboard.power');

// HVAC System page (Device Control)
Route::get('/dashboard/hvac', [DashboardController::class, 'hvac'])->name('dashboard.hvac');

// Lighting System page (Device Control)
Route::get('/dashboard/lighting', [DashboardController::class, 'lighting'])->name('dashboard.lighting');

// Device API for power UI
Route::get('/dashboard/power/data', [DeviceController::class, 'powerData'])->name('dashboard.power.data');
Route::get('/dashboard/power/devices', [DeviceController::class, 'getDevices'])->name('dashboard.power.devices');
Route::get('/dashboard/power/statuses', [DeviceController::class, 'getStatuses'])->name('dashboard.power.statuses');
Route::post('/device/single-power/{id}/toggle', [DeviceController::class, 'toggleSinglePower'])->name('device.single.toggle');
Route::post('/device/main-power/{id}/toggle', [DeviceController::class, 'toggleMainPower'])->name('device.main.toggle');
Route::post('/device/hvac/{id}/toggle', [DeviceController::class, 'toggleHvac'])->name('device.hvac.toggle');
});

