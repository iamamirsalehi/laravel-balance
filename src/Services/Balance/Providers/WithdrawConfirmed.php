<?php

namespace Iamamirsalehi\LaravelBalance\Services\Balance\Providers;

use Carbon\Carbon;
use Iamamirsalehi\LaravelBalance\Services\Balance\Contracts\BalanceInterface;

class WithdrawConfirmed extends BalanceInterface
{
    /**
     *
     *
     */
    public function handle()
    {
        $unconfirmed_withdraw = $this->getTheLastUnconfirmedUserWithdraw();

        $data = [
            'balance_is_admin_confirmed'           => $this->withdraw_repository::CONFIRMED,
            'balance_admin_confirmation_date_time' => Carbon::now(),
            ''
        ];

        $confirmed_withdraw = $unconfirmed_withdraw->update();


    }

    private function getTheLastUnconfirmedUserWithdraw()
    {
        return $this->withdraw_repository->where([
            ['user_id', '=', $this->data->getUserId()],
            ['coin_id', '=', $this->data->getCoinId()],
            ['balance_is_admin_confirmed', '=', 0]
        ])->orderBy('id', 'desc')->first();
    }
}