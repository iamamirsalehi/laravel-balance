<?php

namespace Iamamirsalehi\LaravelBalance\Services\Balance;


use Iamamirsalehi\LaravelBalance\Exceptions\ActionNotFoundException;
use Iamamirsaleho\LaravelBalance\Services\Balance\Providers\Deposit;

class BalanceService
{
    public static function __callStatic($name, $arguments)
    {
        $requested_class = ucfirst($name);

        $providers_base_namespace = "Iamamirsalehi\\LaravelBalance\\Services\\Balance\\Providers\\";

        if(class_exists($providers_base_namespace . $requested_class))
            return (new ($providers_base_namespace . $requested_class));

        throw new ActionNotFoundException($name . ' action not found, please call an existing action');
    }
}