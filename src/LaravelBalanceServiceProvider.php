<?php

namespace Iamamirsalehi\LaravelBalance;

use Illuminate\Support\ServiceProvider;

class LaravelBalanceServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'laravelBalance');
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {

            $this->publishes([
              __DIR__.'/../config/config.php' => config_path('laravelBalance.php'),
            ], 'config');
        
        }

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

    }
}