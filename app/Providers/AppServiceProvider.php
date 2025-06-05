<?php

namespace App\Providers;

use App\Models\Admin;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
       view()->composer(['web.layout.main'], function ($view){
            $sessionId = Session::getId();
            $admin = Admin::where('session_id', $sessionId)->first();
            $username = $admin ? $admin->username : 'Guest';
            $avatar = $admin ? $admin->avatar : 'default.png';

            $view->with(['username'=>$username, 'avatar'=> $avatar]);
        });
    }
}
