<?php

use App\Http\Controllers\Web\ProductController;
use App\Http\Controllers\Web\ReviewController;
use App\Http\Controllers\Web\TransactionController;
use App\Http\Livewire\CartComponent;
use App\Http\Livewire\CategoryComponent;
use App\Http\Livewire\CheckoutComponent;
use App\Http\Livewire\CheckoutReseller;
use App\Http\Livewire\HomeComponent;
use App\Http\Livewire\MemberComponent;
use App\Http\Livewire\MyProfileComponent;
use App\Http\Livewire\SaveProfile;
use App\Http\Livewire\ShopComponent;
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\DetailOrderComponent;
use App\Http\Livewire\ShowAllProducts;
use App\Http\Livewire\ShowBlog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;

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
Route::get('/', HomeComponent::class)->name('home.page');
Route::get('/blog/{id}', ShowBlog::class)->name('show.blog');
Route::get('/all-product', ShowAllProducts::class)->name('show.prod.all');
Route::get('/pictures/{id}', [ProductController::class, 'pictureShow'])->name('picture.show');
Route::get('/cetak/invoice/{id}', [TransactionController::class, 'printInvoice'])->middleware('auth')->name('print.invoice');
Route::resource('review', ReviewController::class)->middleware('auth');
Route::group(['prefix' => 'reseller','middleware' => ['role:reseller','auth','active']], function() {
    Route::get('/shop/{name}-{id}', ShopComponent::class)->name('member.shopr');
    Route::get('/seller', MemberComponent::class)->name('member.showr');
    Route::get('/shop/categories/{id}', CategoryComponent::class)->name('product.category');
    Route::get('/shop/adprod/{$id}', [ShopComponent::class, 'store']);
    Route::get('/checkout', CheckoutReseller::class)->name('checkout.reseller');
    Route::get('/cart', CartComponent::class)->name('cart.reseller');
    Route::get('/detailorder/{transaction}', DetailOrderComponent::class)->name('detail.orderreseller');
    Route::get('/profile', MyProfileComponent::class)->name('profile.reseller');
    Route::get('/profile/lacak/{id}', [MyProfileComponent::class, 'lacak'])->name('reseller.lacak');
    Route::patch('/profile', SaveProfile::class)->name('profile.saver');
});
Route::get('/', HomeComponent::class)->name('home.page');
Route::group(['prefix' => 'customer','middleware' => ['role:customer','auth','active']], function() {
    Route::get('/shop/{name}-{id}', ShopComponent::class)->name('member.shopc');
    Route::get('/seller', MemberComponent::class)->name('member.showc');
    Route::get('/shop/categories/{id}', CategoryComponent::class)->name('product.category');
    Route::get('/shop/adprod/{$id}', [ShopComponent::class, 'store']);
    Route::get('/checkout', CheckoutComponent::class)->name('checkout.customer');
    Route::get('/cart', CartComponent::class)->name('cart.customer');
    Route::get('/detailorder/{transaction}', DetailOrderComponent::class)->name('detail.ordercustomer');
    Route::get('/profile', MyProfileComponent::class)->name('profile.customer');
    Route::get('/profile/lacak/{id}', [MyProfileComponent::class, 'lacak'])->name('customer.lacak');
    Route::patch('/profile', SaveProfile::class)->name('profile.savec');
});

Route::get('storage-link', function() {
    return Artisan::call("storage:link");
});
