<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;    
use App\Http\Controllers\UnitKerjaController;

/*
|--------------------------------------------------------------------------
| Landing Page
|--------------------------------------------------------------------------
*/
Route::get('/', [LandingController::class, 'index'])->name('landing');


/*
|--------------------------------------------------------------------------
| Auth (Login & Logout)
|--------------------------------------------------------------------------
*/
Route::prefix('auth')->group(function () {

    // tampil halaman login + generate captcha
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

    // proses login
    Route::post('/login', [LoginController::class, 'login'])->name('login.process');

    // logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

});


/*
|--------------------------------------------------------------------------
| Dashboard (setelah login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');
});

Route::resource('unit_kerja', UnitKerjaController::class); 

Route::resource('kategori', KategoriController::class);