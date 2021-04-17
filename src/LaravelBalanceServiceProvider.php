<?php

namespace Iamamirsalehi\LaravelBalance;

use Iamamirsalehi\LaravelBalance\Services\Balance\BalanceService;
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

            $this->publishes([
                __DIR__.'/../database/migrations/' => database_path('migrations')
            ], 'migrations');

            $this->publishes([
                __DIR__.'/../database/seeders/' => database_path('seeders')
            ], 'seeders');

            $this->publishes([
                __DIR__.'/Models/Balance.php/' => app_path('Models'),
                __DIR__.'/Models/Coin.php/' => app_path('Models'),
                __DIR__.'/Models/Deposit.php/' => app_path('Models'),
                __DIR__.'/Models/Withdraw.php/' => app_path('Models'),
            ], 'Models');
        }
    }
}