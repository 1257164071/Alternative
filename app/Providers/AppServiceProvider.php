<?php

namespace App\Providers;

use App\Models\Admin;
use App\Models\Roles;
use App\Observers\Admin\AdminObserver;
use App\Observers\Admin\RolesObserver;
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
        //
        Admin::observe(AdminObserver::class);
        Roles::observe(RolesObserver::class);
    }
}
