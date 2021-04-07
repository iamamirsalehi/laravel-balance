<?php

return [
    'maximum_withdraw' => 20000000,

    'maximum_deposit'  => 50000000,

    'minimum_withdraw' => 10000,

    'minimum_deposit'  => 100000,

    'repositories'     => [
        'coin'    => \Iamamirsalehi\LaravelBalance\Models\Coin::class,
        'balance' => \Iamamirsalehi\LaravelBalance\Models\Balance::class,
        'wihdraw' => \Iamamirsalehi\LaravelBalance\Models\Withdraw::class,
        'deposit' => \Iamamirsalehi\LaravelBalance\Models\Deposit::class,
    ]
];