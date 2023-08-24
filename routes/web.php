<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BisController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PelajarController;
use App\Http\Controllers\SekolahController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MonitoringController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('beranda');
})->name("beranda");
Route::prefix('monitoring')->group(function() { // KHUSUS UNTUK FRONT END DI ANDROID
    Route::get('maps', [MonitoringController::class, 'mapsView'])->name('monitoring.maps');
    Route::post('maps/update', [MonitoringController::class, 'mapsUpdate'])->name('monitoring.maps.update');
    Route::get('pelajar/{nisn}', [MonitoringController::class, 'pelajarView'])->name('monitoring.pelajar');
    Route::post('pelajar/check', [MonitoringController::class, 'pelajarCek'])->name('monitoring.pelajar.check');    
});
Route::middleware('guest')->group(function() { // ROUTE UNTUK LOGIN
    Route::post('login', [LoginController::class, 'login'])->name('login');
    Route::post('login/modal', [LoginController::class, 'viewModal'])->name('login.modal');
});
Route::middleware('auth')->group(function() { // BACK END
    Route::middleware('PasswordReset')->group(function() {
        Route::middleware('admin')->group(function() { // KHUSUS SUPER ADMIN DAN ADMIN
            Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
            Route::post('dashboard/detailMonitoring', [DashboardController::class, 'viewModal'])->name('detailMonitoring.modal');
        });
        Route::prefix('manajemen')->group(function() { // URL MANAJEMEN
            Route::prefix('admin')->middleware('super')->group(function() { // URL ADMIN
                Route::get('/', [AdminController::class, 'index'])->name('admin');
                Route::post('input', [AdminController::class, 'store'])->name('admin.store');
                Route::put('{id}/edit', [AdminController::class, 'update'])->name('admin.update');
                Route::delete('{id}/hapus', [AdminController::class, 'destroy'])->name('admin.hapus');
                Route::post('check/email', [AdminController::class, 'cekEmail'])->name('admin.email');
                Route::post('show/modal', [AdminController::class, 'viewModal'])->name('admin.modal');
            });
            Route::prefix('bus')->middleware('admin')->group(function() { // URL BUS
                Route::get('/', [BisController::class, 'index'])->name('bus');
                Route::post('input', [BisController::class, 'store'])->name('bus.store');
                Route::put('{plat_nomor}/edit', [BisController::class, 'update'])->name('bus.update');
                Route::delete('{plat_nomor}/hapus', [BisController::class, 'destroy'])->name('bus.hapus');
                Route::post('check/platNomor', [BisController::class, 'cekPlatNomor'])->name('bus.platNomor');
                Route::post('show/modal', [BisController::class, 'viewModal'])->name('bus.modal');
            });
            Route::prefix('sekolah')->middleware('admin')->group(function() {
                Route::get('/', [SekolahController::class, 'index'])->name('sekolah');
                Route::post('input', [SekolahController::class, 'store'])->name('sekolah.store');
                Route::put('{kode_sekolah}/edit', [SekolahController::class, 'update'])->name('sekolah.update');
                Route::delete('{kode_sekolah}/hapus', [SekolahController::class, 'destroy'])->name('sekolah.hapus');
                Route::post('check/kodeSekolah', [SekolahController::class, 'cekKodeSekolah'])->name('sekolah.kodeSekolah');
                Route::post('check/email', [SekolahController::class, 'cekEmail'])->name('sekolah.emailAdminSekolah');
                Route::post('show/modal', [SekolahController::class, 'viewModal'])->name('sekolah.modal');
            });
            Route::prefix('pelajar')->middleware('admin.sekolah')->group(function() {
                Route::get('/', [PelajarController::class, 'index'])->name('pelajar');
                Route::get('input', [PelajarController::class, 'create'])->name('pelajar.input');
                Route::post('input', [PelajarController::class, 'store'])->name('pelajar.store');
                Route::put('{nisn}/edit', [PelajarController::class, 'update'])->name('pelajar.update');
                Route::delete('{nisn}/hapus', [PelajarController::class, 'destroy'])->name('pelajar.hapus');
                Route::post('check/uid', [PelajarController::class, 'cekUid'])->name('pelajar.uid');
                Route::post('check/nisn', [PelajarController::class, 'cekNisn'])->name('pelajar.nisn');
                Route::post('show/modal', [PelajarController::class, 'viewModal'])->name('pelajar.modal');
                Route::put('{nisn}/reset', [PelajarController::class, 'resetPassword'])->name('pelajar.reset');
            });
        });
    });
    Route::prefix('dashboard/admin')->group(function() {
        Route::get('logout', [LoginController::class, 'logoutaksi'])->name('logoutaksi');
        Route::get('ubahPassword', [DashboardController::class, 'viewUbahPassword'])->name('user.viewUbahPassword');
        Route::put('updatePassword/{email}', [DashboardController::class, 'updatePassword'])->name('user.updatePassword');
        Route::put('{id}/reset', [AdminController::class, 'resetPassword'])->name('user.reset');
    });
});