<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Website\AuthController;
use App\Http\Controllers\Website\HomeController;
use App\Http\Controllers\Website\MaterialController;
use App\Http\Controllers\Website\LineController;
use App\Http\Controllers\Website\ShiftController;
use App\Http\Controllers\Website\UserController;


Route::get('/login', [AuthController::class, 'login_page'])->name('login_page');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/register', [AuthController::class, 'register_page'])->name('register_page');
Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::middleware('auth')->group(function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/', [HomeController::class, 'index'])->name('index');

    Route::group(['prefix' => 'material', 'as' => 'material.', 'middleware' => 'admin'], function () {
        Route::get('/', [MaterialController::class, 'index'])->name('index');
        Route::get('/data', [MaterialController::class, 'data'])->name('data');
        Route::get('/create', [MaterialController::class, 'create'])->name('create');
        Route::post('/store', [MaterialController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [MaterialController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [MaterialController::class, 'update'])->name('update');
        Route::delete('/destroy/{id}', [MaterialController::class, 'destroy'])->name('destroy');
    });

    Route::group(['prefix' => 'line', 'as' => 'line.', 'middleware' => 'admin'], function () {
        Route::get('/', [LineController::class, 'index'])->name('index');
        Route::get('/data', [LineController::class, 'data'])->name('data');
        Route::get('/create', [LineController::class, 'create'])->name('create');
        Route::post('/store', [LineController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [LineController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [LineController::class, 'update'])->name('update');
        Route::delete('/destroy/{id}', [LineController::class, 'destroy'])->name('destroy');
    });

    Route::group(['prefix' => 'shift', 'as' => 'shift.', 'middleware' => 'admin'], function () {
        Route::get('/', [ShiftController::class, 'index'])->name('index');
        Route::get('/data', [ShiftController::class, 'data'])->name('data');
        Route::get('/create', [ShiftController::class, 'create'])->name('create');
        Route::post('/store', [ShiftController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [ShiftController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [ShiftController::class, 'update'])->name('update');
        Route::delete('/destroy/{id}', [ShiftController::class, 'destroy'])->name('destroy');
    });

    Route::group(['prefix' => 'user', 'as' => 'user.', 'middleware' => 'admin'], function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/data', [UserController::class, 'data'])->name('data');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/store', [UserController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [UserController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/destroy/{id}', [UserController::class, 'destroy'])->name('destroy');
    });
});
