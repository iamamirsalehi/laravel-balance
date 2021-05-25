<?php

namespace Iamamirsalehi\LaravelBalance\Services\Balance\Providers;

use Carbon\Carbon;
use Iamamirsalehi\LaravelBalance\Models\Withdraw;
use Iamamirsalehi\LaravelBalance\Services\Balance\Contracts\BalanceInterface;
use Iamamirsalehi\LaravelBalance\Resources\RejectedWithdrawResource;
use Iamamirsalehi\LaravelBalance\Services\Balance\Exceptions\ServerException;
use Iamamirsalehi\LaravelBalance\Services\Balance\Exceptions\ThereIsNoRecordException;

class RejectedWithdraw extends BalanceInterface
{
    /**
     * this method is called When user withdraw request is rejected by action
     * @return array
     * @throws ServerException
     * @throws \Iamamirsalehi\LaravelBalance\Services\Balance\Exceptions\MustBeExistedException
     * @throws \Iamamirsalehi\LaravelBalance\Services\Balance\Exceptions\NumberMustBeIntegerException
     */
    public function handle()
    {
        $unconfirmed_withdraw = $this->getUserUnconfirmedWithdraw();

        $this->checkWithdrawIsRejectedOrConfirmed($unconfirmed_withdraw);

        $action_liability = floatval($unconfirmed_withdraw->action_liability * -1);

        $liability = 0;

        $free_balance = floatval($unconfirmed_withdraw->asset - $liability);

        $rejected_withdraw_data = [
            'tracking_code' => $unconfirmed_withdraw->tracking_code,
            'action_asset' => floatval($unconfirmed_withdraw->action_asset),
            'asset' => floatval($unconfirmed_withdraw->asset),
            'action_liability' => floatval($action_liability),
            'liability' => floatval($liability),
            'equity' => floatval($free_balance),
            'is_admin_confirmed' => Withdraw::UNCONFIRMED,
            'is_admin_rejected' => Withdraw::REJECTED,
            'admin_rejection_date_time' => Carbon::now(),
            'is_admin_rejected_description' => $this->data->getIsAdminRejectedDescription(),
            'user_id' => $this->data->getUserId(),
            'coin_id' => $this->data->getCoinId(),
        ];

        $balance_data = [
            'tracking_code' => $unconfirmed_withdraw->tracking_code,
            'action_asset' => floatval($unconfirmed_withdraw->action_asset),
            'asset' => floatval($unconfirmed_withdraw->asset),
            'action_liability' => floatval($action_liability),
            'liability' => floatval($liability),
            'equity' => floatval($free_balance),
            'user_id' => $this->data->getUserId(),
            'coin_id' => $this->data->getCoinId(),
        ];

        $rejected_withdraw = $unconfirmed_withdraw->update($rejected_withdraw_data);

        if (!$rejected_withdraw)
            throw new ServerException('Something went wrong, Please try again');

        $result = $unconfirmed_withdraw->balances()->create($balance_data);

        $result->is_admin_confirmed = $rejected_withdraw_data['is_admin_confirmed'];
        $result->is_admin_rejected_description = $rejected_withdraw_data['is_admin_rejected_description'];

        return (new RejectedWithdrawResource($result))->toArray();
    }

    /**
     * The unconfirmed user withdraw
     * @return mixed
     * @throws \Iamamirsalehi\LaravelBalance\Services\Balance\Exceptions\MustBeExistedException
     * @throws \Iamamirsalehi\LaravelBalance\Services\Balance\Exceptions\NumberMustBeIntegerException
     */
    private function getUserUnconfirmedWithdraw()
    {
        return $this->withdraw_repository->where([
            ['user_id', '=', $this->data->getUserId()],
            ['coin_id', '=', $this->data->getCoinId()],
            ['id', '=', $this->data->getWithdrawId()]
        ])->first();
    }

    /**
     * @param $unconfirmed_withdraw
     * @throws ThereIsNoRecordException
     */
    private function checkWithdrawIsRejectedOrConfirmed($unconfirmed_withdraw)
    {
        if($unconfirmed_withdraw->is_admin_rejected == Withdraw::REJECTED)
            throw new ThereIsNoRecordException('Requested withdraw is already rejected');

        if($unconfirmed_withdraw->is_admin_rejected == Withdraw::REJECTED and $unconfirmed_withdraw->is_admin_confirmed != Withdraw::CONFIRMED)
            throw new ThereIsNoRecordException('Requested withdraw is already rejected');

        if($unconfirmed_withdraw->is_admin_confirmed == Withdraw::CONFIRMED)
            throw new ThereIsNoRecordException('Requested withdraw is already confirmed');
    }
}