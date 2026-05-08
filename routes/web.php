<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;    
use App\Http\Controllers\UnitKerjaController;
use App\Http\Controllers\SopController;

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
| Fitur Tertutup (Hanya bisa diakses setelah Login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 🔥 ROUTE 1: UPLOAD VERSI PDF (Aman dari publik)
    Route::post('/sop/{id}/upload-versi', [SopController::class, 'uploadVersi'])->name('sop.upload_versi');
});

/*
|--------------------------------------------------------------------------
| Fitur Publik & Resource
|--------------------------------------------------------------------------
| Catatan: Untuk keamanan ekstra, method create/edit/destroy di controller 
| ini wajib diproteksi menggunakan constructor middleware di controllernya.
*/
Route::resource('unit_kerja', UnitKerjaController::class); 
Route::resource('kategori', KategoriController::class);
Route::resource('sop', SopController::class);

Route::get('/sop/{id}/versions', [SopController::class, 'versions'])->name('sop.versions');
Route::get('/sop/file/{version_id}', [SopController::class, 'lihatPdf'])->name('sop.lihat_pdf');
// Route khusus download versi terbaru dari Landing Page
Route::get('/download-sop/{sop_id}', [SopController::class, 'downloadTerbaru'])->name('sop.download_terbaru');