<?php

namespace Iamamirsalehi\LaravelBalance\Services\Balance\Providers;

use Carbon\Carbon;
use Iamamirsalehi\LaravelBalance\Services\Balance\Contracts\BalanceInterface;
use Iamamirsalehi\LaravelBalance\src\Resources\WithdrawConfirmedResource;
use Iamamirsalehi\LaravelBalance\src\Services\Balance\Exceptions\ThereIsNoRecordException;
use Iamamirsalehi\LaravelBalance\Utilities\CodeGenerator;

class WithdrawConfirmed extends BalanceInterface
{
    /**
     *
     *
     */
    public function handle()
    {
        $unconfirmed_withdraw = $this->getTheLastUnconfirmedUserWithdraw();

        if(is_null($unconfirmed_withdraw))
            throw new ThereIsNoRecordException('There is no unconfirmed record to confirm');

        $action_asset = $unconfirmed_withdraw->action_liability;

        $asset = ($unconfirmed_withdraw->action_liability * -1) + $unconfirmed_withdraw->asset;

        $action_liability = $unconfirmed_withdraw->liability;

        $free_balance =  $unconfirmed_withdraw->liability - $asset;

        $liability = $unconfirmed_withdraw->liability - $unconfirmed_withdraw->action_liability;

        $data_confirmed = [
            'tracking_code'                => CodeGenerator::make(),
            'action_asset'                 => $action_asset * -1,
            'asset'                        => $asset,
            'action_liability'             => $action_liability * -1,
            'liability'                    => $liability,
            'equity'                       => $free_balance,
            'is_admin_confirmed'           => $this->withdraw_repository::CONFIRMED,
            'admin_confirmation_date_time' => Carbon::now(),
            'user_id'                      => $unconfirmed_withdraw->user_id,
            'coin_id'                      => $unconfirmed_withdraw->coin_id,
        ];

        $data_balance = [
            'tracking_code'                => CodeGenerator::make(),
            'action_asset'                 => $action_asset  * -1,
            'asset'                        => $asset ,
            'action_liability'             => $action_liability * -1,
            'liability'                    => $liability,
            'equity'                       => $free_balance,
            'user_id'                      => $unconfirmed_withdraw->user_id,
            'coin_id'                      => $unconfirmed_withdraw->coin_id,
        ];

        $confirmed_withdraw = $unconfirmed_withdraw->update($data_confirmed);

        $this->storeWithdrawconfirmed($data_balance);

        return (new WithdrawConfirmedResource($confirmed_withdraw))->toArray();
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