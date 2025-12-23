<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RawLuxMotionController;
use App\Http\Controllers\RawPowerController;
use App\Http\Controllers\RawSuhuHumidityController;
use App\Http\Controllers\GedungController;
use App\Http\Controllers\LantaiController;
use App\Http\Controllers\MainPowerController;
use App\Http\Controllers\RuanganController;
use App\Http\Controllers\SinglePowerController;
use App\Http\Controllers\HvacController;
use App\Http\Controllers\LightingController;
use App\Http\Controllers\ReportHvacController;
use App\Http\Controllers\ReportLightingController;
use App\Http\Controllers\ReportPowerController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;

// Lux & Motion API Routes
Route::prefix('raw-lux-motion')->group(function () {
    Route::post('batch-store', [RawLuxMotionController::class, 'batchStore']);
    Route::get('{deviceId}/stats', [RawLuxMotionController::class, 'getDeviceStats']);
    Route::get('{deviceId}/motion-history', [RawLuxMotionController::class, 'getMotionHistory']);
});

Route::resource('raw-lux-motion', RawLuxMotionController::class)
    ->except(['create', 'edit']);

// Power API Routes
Route::prefix('raw-power')->group(function () {
    Route::post('batch-store', [RawPowerController::class, 'batchStore']);
    Route::get('high-consumption', [RawPowerController::class, 'getHighConsumption']);
    Route::get('{deviceId}/stats', [RawPowerController::class, 'getDeviceStats']);
    Route::get('{deviceId}/trend', [RawPowerController::class, 'getPowerTrend']);
    Route::get('{deviceId}/power-factor', [RawPowerController::class, 'getPowerFactorAnalysis']);
});

Route::resource('raw-power', RawPowerController::class)
    ->except(['create', 'edit']);

// Suhu Humidity API Routes
Route::prefix('raw-suhu-humidity')->group(function () {
    Route::post('batch-store', [RawSuhuHumidityController::class, 'batchStore']);
    Route::get('{deviceId}/stats', [RawSuhuHumidityController::class, 'getDeviceStats']);
    Route::get('{deviceId}/trend', [RawSuhuHumidityController::class, 'getTrend']);
});

Route::resource('raw-suhu-humidity', RawSuhuHumidityController::class)
    ->except(['create', 'edit']);

// Gedung API Routes
Route::resource('gedung', GedungController::class)
    ->except(['create', 'edit']);

// Lantai API Routes
Route::get('lantai/gedung/{gedung_id}', [LantaiController::class, 'byGedung']);
Route::resource('lantai', LantaiController::class)
    ->except(['create', 'edit']);

// Main Power API Routes
Route::resource('main-power', MainPowerController::class)
    ->except(['create', 'edit']);

// Ruangan API Routes
Route::get('ruangan/lantai/{lantai_id}', [RuanganController::class, 'byLantai']);
Route::resource('ruangan', RuanganController::class)
    ->except(['create', 'edit']);

// Single Power API Routes
Route::get('single-power/ruangan/{ruangan_id}', [SinglePowerController::class, 'byRuangan']);
Route::resource('single-power', SinglePowerController::class)
    ->except(['create', 'edit']);

// HVAC API Routes
Route::get('hvac/ruangan/{ruangan_id}', [HvacController::class, 'byRuangan']);
Route::resource('hvac', HvacController::class)
    ->except(['create', 'edit']);

// Lighting API Routes
Route::get('lighting/ruangan/{ruangan_id}', [LightingController::class, 'byRuangan']);
Route::resource('lighting', LightingController::class)
    ->except(['create', 'edit']);

// HVAC Report API Routes
Route::post('report-hvac/aggregate', [ReportHvacController::class, 'aggregate']);
Route::get('report-hvac/filter', [ReportHvacController::class, 'filter']);
Route::get('report-hvac/filter-by-device', [ReportHvacController::class, 'filterByDevice']);
Route::resource('report-hvac', ReportHvacController::class)
    ->except(['create', 'edit']);

// Lighting Report API Routes
Route::post('report-lighting/aggregate', [ReportLightingController::class, 'aggregate']);
Route::get('report-lighting/filter', [ReportLightingController::class, 'filter']);
Route::get('report-lighting/filter-by-device', [ReportLightingController::class, 'filterByDevice']);
Route::resource('report-lighting', ReportLightingController::class)
    ->except(['create', 'edit']);

// Power Report API Routes
Route::post('report-power/aggregate', [ReportPowerController::class, 'aggregate']);
Route::get('report-power/filter', [ReportPowerController::class, 'filter']);
Route::get('report-power/filter-by-device', [ReportPowerController::class, 'filterByDevice']);
Route::resource('report-power', ReportPowerController::class)
    ->except(['create', 'edit']);

// User API Routes
Route::get('user/{id}/roles', [UserController::class, 'getRoles']);
Route::post('user/{id}/assign-role', [UserController::class, 'assignRole']);
Route::post('user/{id}/remove-role', [UserController::class, 'removeRole']);
Route::resource('user', UserController::class)
    ->except(['create', 'edit']);

// Role API Routes
Route::resource('role', RoleController::class)
    ->except(['create', 'edit']);
