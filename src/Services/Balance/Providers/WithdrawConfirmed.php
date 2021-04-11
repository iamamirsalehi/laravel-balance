<?php

namespace Iamamirsalehi\LaravelBalance\Services\Balance\Providers;

use Carbon\Carbon;
use Iamamirsalehi\LaravelBalance\Resources\WithdrawConfirmedResource;
use Iamamirsalehi\LaravelBalance\Services\Balance\Contracts\BalanceInterface;
use Iamamirsalehi\LaravelBalance\Services\Balance\Exceptions\ServerException;
use Iamamirsalehi\LaravelBalance\Services\Balance\Exceptions\ThereIsNoRecordException;
use Iamamirsalehi\LaravelBalance\Utilities\CodeGenerator;

class WithdrawConfirmed extends BalanceInterface
{
    /**
     * this method is called whenever we want to confirm an unconfirmed withdraw
     * @return array
     * @throws ServerException
     * @throws ThereIsNoRecordException
     */
    public function handle()
    {
        $unconfirmed_withdraw = $this->getTheLastUnconfirmedUserWithdraw();

        if (is_null($unconfirmed_withdraw))
            throw new ThereIsNoRecordException('There is no unconfirmed record to confirm');

        $action_liability = $unconfirmed_withdraw->liability * -1;

        $action_asset = $action_liability;

        $liability = $unconfirmed_withdraw->liability - $unconfirmed_withdraw->action_liability;

        $asset = $action_asset + $unconfirmed_withdraw->asset;

        $free_balance = $unconfirmed_withdraw->asset - $unconfirmed_withdraw->liability;

        $tracking_code = (int)$unconfirmed_withdraw->tracking_code;

        $data_confirmed = [
            'tracking_code' => $tracking_code,
            'action_asset' => $action_asset,
            'asset' => $asset,
            'action_liability' => $action_liability,
            'liability' => $liability,
            'equity' => $free_balance,
            'is_admin_confirmed' => $this->withdraw_repository::CONFIRMED,
            'admin_confirmation_date_time' => Carbon::now(),
            'user_id' => $unconfirmed_withdraw->user_id,
            'coin_id' => $unconfirmed_withdraw->coin_id,
        ];

        $data_balance = [
            'tracking_code' => $tracking_code,
            'action_asset' => $action_asset,
            'asset' => $asset,
            'action_liability' => $action_liability,
            'liability' => $liability,
            'equity' => $free_balance,
            'user_id' => $unconfirmed_withdraw->user_id,
            'coin_id' => $unconfirmed_withdraw->coin_id,
        ];

        $confirmed_withdraw = $unconfirmed_withdraw->update($data_confirmed);

        if (!$confirmed_withdraw)
            throw new ServerException('Something went wrong, Please try again');
        $result = $unconfirmed_withdraw->balances()->create($data_balance);

        $result->is_admin_confirmed = $this->withdraw_repository::CONFIRMED;

        return (new WithdrawConfirmedResource($result))->toArray();
    }

    /**
     * @return mixed
     * @throws \Iamamirsalehi\LaravelBalance\Services\Balance\Exceptions\MustBeExistedException
     * @throws \Iamamirsalehi\LaravelBalance\Services\Balance\Exceptions\NumberMustBeIntegerException
     */
    private function getTheLastUnconfirmedUserWithdraw()
    {
        return $this->withdraw_repository->where([
            ['user_id', '=', $this->data->getUserId()],
            ['coin_id', '=', $this->data->getCoinId()],
            ['id', '=', $this->data->getWithdrawId()],
            ['is_admin_confirmed', '=', 0]
        ])->first();
    }
}