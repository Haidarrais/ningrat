<?php

use App\Http\Controllers\Api\DataController;
use App\Http\Controllers\Api\ErrorController;
use App\Http\Controllers\Api\MasterController;
use App\Http\Controllers\Api\RajaOngkirController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('check_profile', [ErrorController::class, 'check_home'])->name('api.check_home');

Route::get('courier', [RajaOngkirController::class, 'get_all_courier'])->name('api.get_courier');
Route::group(['middleware', ['api_token']], function() {
    Route::post('cost', [RajaOngkirController::class, 'ongkir'])->name('api.get_ongkir');
    Route::post('lacak', [RajaOngkirController::class, 'lacak'])->name('api.lacak_ongkir');
});

Route::get('role', [MasterController::class, 'get_role'])->name('api.get_role');
Route::get('category', [MasterController::class, 'get_category'])->name('api.get_category');
Route::get('province/{id?}', [MasterController::class, 'get_provice'])->name('api.get_provice');
Route::get('city/{province_id?}', [MasterController::class, 'get_city'])->name('api.get_city');
Route::get('subdistict/{city_id?}', [MasterController::class, 'get_subdistict'])->name('api.get_subdistict');

Route::post('penjualan', [DataController::class, 'get_penjualan'])->name('api.get_penjualan');
Route::post('status-penjualan', [DataController::class, 'get_status_penjualan'])->name('api.get_status_penjualan');
Route::post('grafik-penjualan', [DataController::class, 'get_grafik_penjualan'])->name('api.get_grafik_penjualan');

Route::post('get-user-by-role', [DataController::class, 'get_user_by_id'])->name('api.get_user_by_role');
Route::post('rank', [DataController::class, 'get_rank'])->name('api.get_rank');
