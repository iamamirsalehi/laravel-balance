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

        $action_asset = $unconfirmed_withdraw->action_liability * -1;

        $asset = $unconfirmed_withdraw->action_liability - $unconfirmed_withdraw->asset;

        $action_liability = $unconfirmed_withdraw->liability * -1;

        $free_balance = $asset - $unconfirmed_withdraw->liability;

        $data = [
            'is_admin_confirmed'           => $this->withdraw_repository::CONFIRMED,
            'admin_confirmation_date_time' => Carbon::now(),
            'tracking_code'                => '',
            'action_asset'                 => '',
        ];

        $confirmed_withdraw = $unconfirmed_withdraw->update();


    }

    private function getTheLastUnconfirmedUserWithdraw()
    {
        return $this->withdraw_repository->where([
            ['user_id', '=', $this->data->getUserId()],
            ['coin_id', '=', $this->data->getCoinId()],
            ['is_admin_confirmed', '=', 0]
        ])->orderBy('id', 'desc')->first();
    }
}