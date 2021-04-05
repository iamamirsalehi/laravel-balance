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
                __DIR__ . '/../database/migrations/2021_04_04_082937_create_balances_table.php',
                __DIR__ . '/../database/migrations/2021_04_04_085445_create_coins_table.php',
                __DIR__ . '/Models/Balance.php',
                __DIR__ . '/Models/Coin.php',

            ], 'config');
        
        }

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

    }
}