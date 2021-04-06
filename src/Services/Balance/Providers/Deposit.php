<?php

namespace Iamamirsalehi\LaravelBalance\Services\Balance\Providers;

use Iamamirsalehi\LaravelBalance\Services\Balance\Contracts\BalanceInterface;

class Deposit extends BalanceInterface
{
    /**
     * asset formula is D(n)=C(n)+D(n-1)
     *
     * C(n) ->
     */

    public function handle()
    {
        $c = $this->data->getDepositPrice();

        $last_balance_record_of_user =  $this->getTheLastBalanceRecordOfUser();


    }


}