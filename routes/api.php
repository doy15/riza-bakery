<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DistributionController;
use App\Http\Controllers\Api\LineController;
use App\Http\Controllers\Api\MaterialController;
use App\Http\Controllers\Api\MaterialStockController;
use App\Http\Controllers\Api\ProductionDataController;
use App\Http\Controllers\Api\ShiftController;
use App\Models\ProductionData;
use Illuminate\Support\Facades\Route;


Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['jwt.auth'])->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/materialstock/store', [MaterialStockController::class, 'store']);

    Route::post('/distribution/store', [DistributionController::class, 'store']);

    Route::group(['prefix' => 'material', 'as' => 'material.'], function () {
        Route::get('/get_data', [MaterialController::class, 'get_data']);
        Route::get('/get_data_raw', [MaterialController::class, 'get_data_raw']);
        Route::get('/get_data_finish_good', [MaterialController::class, 'get_data_finish_good']);
    });

    Route::group(['prefix' => 'line', 'as' => 'line.'], function () {
        Route::get('/get_data', [LineController::class, 'get_data']);
    });

    Route::group(['prefix' => 'shift', 'as' => 'shift.'], function () {
        Route::get('/get_data', [ShiftController::class, 'get_data']);
    });

    Route::group(['prefix' => 'production', 'as' => 'production.'], function () {
        Route::get('/select', [ProductionDataController::class, 'select']);
        Route::post('/generate', [ProductionDataController::class, 'generate']);
    });
});
