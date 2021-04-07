<?php

namespace Iamamirsalehi\LaravelBalance;

use Iamamirsalehi\LaravelBalance\Services\Balance\BalanceService;
use Illuminate\Support\ServiceProvider;

class LaravelBalanceServiceProvider extends ServiceProvider
{
    public function register()
    {
//        $this->app->bind('balance', function($app) {
//            return new BalanceService();
//        });

        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'laravelBalance');
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {

            $this->publishes([
              __DIR__.'/../config/config.php' => config_path('laravelBalance.php'),
            ], 'config');

            $this->publishes([
                __DIR__.'/../database/migrations/' => database_path('migrations')
            ], 'migrations');

            $this->publishes([
                __DIR__.'/../database/seeders/' => database_path('seeders')
            ], 'seeders');
        }
    }
}