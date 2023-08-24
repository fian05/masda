<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\PelajarController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::prefix('monitoring')->group(function() {
    Route::post('bus', [MonitoringController::class, 'monitor']);
    Route::post('loginNISN', [PelajarController::class, 'loginNISN']);
    Route::prefix('pelajar')->group(function() {
        Route::post('/', [MonitoringController::class, 'tap']);
        Route::post('getNotifikasi', [MonitoringController::class, 'getNotif']);
        Route::post('listNotifikasi', [MonitoringController::class, 'listNotif']);
        Route::post('updatePassword', [PelajarController::class, 'updatePassword']);
    });
});