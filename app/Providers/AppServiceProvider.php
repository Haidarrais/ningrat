<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Order;
use App\Models\Stock;
use App\Models\RequestUpgrade;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();
        Carbon::setLocale('id');
        // 3 Hari auto Selesai
        DB::beginTransaction();
        try {
            $order = Order::where(function($sub) {
                $sub->where('status', 2)->orWhere('status', 3);
            })->whereDate('updated_at', Carbon::now()->subDays(3))->get();
            $order->update([
                'status', 4
            ]);
            DB::commit();
        } catch(\Exception $e) {
            DB::rollback();
        }
        view()->composer('partials.dashboard.navbar', function($view) {
            $user_id = auth()->id();
            $user = User::find($user_id);
            $beep = false;
            $product = [];
            $request_upgrade = [];
            if($user->getRoleNames()->first() != 'superadmin') {
                $product = Stock::with(['product'])
                                ->where('user_id', $user_id)
                                ->where('stock', '<=', 10)
                                ->where('status', 1)
                                ->get();
                if(count($product) > 0 ) {
                    $beep = true;
                }
            }
            if($user->getRoleNames()->first() == 'superadmin') {
                $request_upgrade = RequestUpgrade::with(['user', 'role'])->where("status", 1)->get();
                if(count($request_upgrade) > 0) {
                    $beep = true;
                }
            }
            $view->with([
                'beep' => $beep,
                'product' => $product,
                'request_upgrade' => $request_upgrade
            ]);
        });
    }
}
