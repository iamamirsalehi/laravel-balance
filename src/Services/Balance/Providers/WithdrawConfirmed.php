<?php

namespace Iamamirsalehi\LaravelBalance\Services\Balance\Providers;

use Carbon\Carbon;
use Iamamirsalehi\LaravelBalance\Models\Withdraw;
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

        if($unconfirmed_withdraw->is_admin_rejected == Withdraw::REJECTED)
            throw new ThereIsNoRecordException('This withdraw is already rejected');

        $action_liability = floatval($unconfirmed_withdraw->liability) * -1;

        $action_asset = floatval($action_liability);

        $liability = floatval($unconfirmed_withdraw->liability - $unconfirmed_withdraw->action_liability);

        $asset = floatval($action_asset + $unconfirmed_withdraw->asset);

        $free_balance = floatval($unconfirmed_withdraw->asset - $unconfirmed_withdraw->liability);

        $tracking_code = (int)$unconfirmed_withdraw->tracking_code;

        $data_confirmed = [
            'tracking_code' => $tracking_code,
            'action_asset' => floatval($action_asset),
            'asset' => floatval($asset),
            'action_liability' => floatval($action_liability),
            'liability' => floatval($liability),
            'equity' => floatval($free_balance),
            'is_admin_confirmed' => $this->withdraw_repository::CONFIRMED,
            'admin_confirmation_date_time' => Carbon::now(),
            'user_id' => $unconfirmed_withdraw->user_id,
            'coin_id' => $unconfirmed_withdraw->coin_id,
        ];

        $data_balance = [
            'tracking_code' => $tracking_code,
            'action_asset' => floatval($action_asset),
            'asset' => floatval($asset),
            'action_liability' => floatval($action_liability),
            'liability' => floatval($liability),
            'equity' => floatval($free_balance),
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