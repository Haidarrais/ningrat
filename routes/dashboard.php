<?php

use App\Http\Controllers\Web\UserController;
use App\Http\Controllers\Web\OrderController;
use App\Http\Controllers\Web\PointController;
use App\Http\Controllers\Web\StockController;
use App\Http\Controllers\Web\RewardController;
use App\Http\Controllers\Web\CourierController;
use App\Http\Controllers\Web\ProductController;
use App\Http\Controllers\Web\ProfileController;
use App\Http\Controllers\Web\RoyaltyController;
use App\Http\Controllers\Web\SettingController;
use App\Http\Controllers\Web\CategoryController;
use App\Http\Controllers\Web\ContentController;
use App\Http\Controllers\Web\CategoryDiscountController;
use App\Http\Controllers\Web\DiscountController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\PageRewardController;
use App\Http\Controllers\Web\TestimonialController;
use App\Http\Controllers\Web\TransactionController;
use App\Http\Controllers\Web\UserMaintenanceController;
use App\Http\Controllers\Web\VariantController;
use Illuminate\Support\Facades\Route;

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

Route::group(['prefix' => 'dashboard', 'middleware' => ['auth','active']], function() {
    Route::get('/{id?}', [DashboardController::class, 'index'])->name('dashboard');
    // Pefix Index
    Route::group(['prefix' => 'pengaturan'], function() {
        Route::resource('/users', UserController::class)->except(['create', 'edit']);
        Route::group(['middleware' => 'role:superadmin'], function() {
            Route::get('courier', [CourierController::class, 'index'])->name('courier.index');
            Route::post('/courier/set-status', [CourierController::class, 'set_status'])->name('courier.set_status');
            Route::post('/user/set-status', [UserController::class, 'set_status'])->name('users.set_status');
            Route::post('/user/upgrade', [UserController::class, 'upgrade'])->name('user.upgrade');
            Route::resource('/reward', RewardController::class)->except(['create', 'edit']);
            Route::post('/reward/set_status', [RewardController::class, 'set_status'])->name('reward.set_status');
            Route::resource('/point', PointController::class)->except(['create', 'edit']);
            Route::resource('/content', ContentController::class);
            Route::resource('/testimonial', TestimonialController::class);
            Route::resource('/setting', SettingController::class)->except(['create', 'edit']);
            Route::get('/view/reward/user', [PageRewardController::class, 'reward_user'])->name('reward.user');
            // Accpet or Reject
            Route::post('/view/reward/set_status', [PageRewardController::class, 'set_status'])->name('reward.user.set_status');
            Route::get('/user-maintenace/{is_accepting_upgrade_req?}', [UserMaintenanceController::class, 'index'])->name('maintenance');
            Route::get('/user-maintenace/show/{id}', [UserMaintenanceController::class, 'show'])->name('maintenance.show');
            Route::patch('/user-maintenace/update', [UserMaintenanceController::class, 'update'])->name('maintenance.update');
            Route::post('/user-maintenace/downgrade/all', [UserMaintenanceController::class, 'downgradeAll'])->name('maintenance.downgrade_all');
        });

        Route::get('/users/{id}/hirarki', [UserController::class, 'hirarki'])->name('users.hirarki');
        Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::post('/upgrade', [ProfileController::class, 'upgrade'])->name('profile.upgrade');
    });
    // End Index

    // Pefix Master
    Route::group(['prefix' => 'master', 'middleware' => 'role:superadmin'], function() {
        Route::resource('/category', CategoryController::class);
        Route::resource('/variant', VariantController::class);
        Route::resource('/subsidi-ongkir', CategoryDiscountController::class);
        Route::resource('/royalty', RoyaltyController::class);
        Route::resource('/product', ProductController::class);
        Route::patch('product/setStatus/{id}', [ProductController::class, 'setStatus'])->name('product.set_status');
        Route::resource('/discount', DiscountController::class);
        Route::post('/discount/set-status', [DiscountController::class, 'set_status'])->name('discount.set_status');
        Route::delete('product/image/{id}', [ProductController::class, 'destroyImage'])->name('produt_image.delete');
    });

    // End Master

    // Pefix Order
    Route::group(['prefix' => 'order'], function() {
        Route::resource('/order', OrderController::class);
        Route::get('/order/show-resi/{id}', [OrderController::class, 'show_resi'])->name('order.show_resi');
        Route::post('/order/reseller', [OrderController::class, 'store_reseller'])->name('order.store.reseller');
        Route::post('/order/set-resi/{id}', [OrderController::class, 'set_resi'])->name('order.set_resi');
        Route::patch('/order/status/{id}/{status}', [OrderController::class, 'set_status'])->name('order.set_status');

        Route::resource('/transaction', TransactionController::class);
        Route::get('/transaction/show-resi/{id}', [TransactionController::class, 'show_resi'])->name('transaction.show_resi');
        Route::post('/transaction/set-resi/{id}', [TransactionController::class, 'set_resi'])->name('transaction.set_resi');
        Route::patch('/transaction/status/{id}/{status}', [TransactionController::class, 'set_status'])->name('transaction.set_status');

        Route::get('/stock', [StockController::class, 'index'])->name('stock.index');
        Route::get('/stock/{id}', [StockController::class, 'edit'])->name('stock.edit');
        Route::put('/stock/{id}', [StockController::class, "update"])->name('stock.update');
        Route::patch('/stock/status/{id}/{status}', [StockController::class, 'set_status'])->name('stock.set_status');
        Route::patch('/stock/discount/{id}/{status}', [StockController::class, 'set_status_discount'])->name('stock.set_status_discount');
    });
    // End Order

    // Other
    Route::group(['prefix' => 'page', 'as' => 'page.'], function() {
        Route::get('reward', [PageRewardController::class, 'index'])->name('reward.index');
        Route::post('reward/perolehan/{user_id}', [PageRewardController::class, 'riwayat_perolehan'])->name('reward.perolehan');
        Route::post('reward/penukaran/{user_id}', [PageRewardController::class, 'riwayat_penukaran'])->name('reward.penukaran');
        Route::post('penukaran-reward', [PageRewardController::class, 'penukaran_reward'])->name('reward.penukaran_reward');
    });
});
