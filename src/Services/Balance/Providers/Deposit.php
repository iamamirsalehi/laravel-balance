<?php

namespace Iamamirsalehi\LaravelBalance\Services\Balance\Providers;

use Iamamirsalehi\LaravelBalance\Utilities\CodeGenerator;
use Iamamirsalehi\LaravelBalance\Resources\DepositResource;
use Iamamirsalehi\LaravelBalance\Services\Balance\Contracts\BalanceInterface;

class Deposit extends BalanceInterface
{
    /**
     * When user deposits this method is called
     *
     * @return array
     * @throws \Iamamirsalehi\LaravelBalance\Services\Balance\Exceptions\MustBeExistedException
     * @throws \Iamamirsalehi\LaravelBalance\Services\Balance\Exceptions\NumberMustBeIntegerException
     * @throws \Iamamirsalehi\LaravelBalance\Services\Balance\Exceptions\PriceMustBeValidException
     */
    public function handle()
    {
        $action_asset = $this->data->getDepositPrice();              // C(n)

        $user_last_balance = $this->getTheLastBalanceRecordOfUser(); // D(n-1)

        list($asset, $free_balance) = $this->calculateAssetAndFreeBalance($action_asset, $user_last_balance);

        $deposit_data = [
            'tracking_code' => CodeGenerator::make(),
            'action_asset' => floatval($action_asset),
            'asset' => floatval($asset),
            'action_liability' => !is_null($user_last_balance?->action_liability) ? floatval($user_last_balance->action_liability) : 0,
            'liability' => !is_null($user_last_balance?->liability) ? floatval($user_last_balance->liability) : 0,
            'equity' => !is_null($free_balance) ? floatval($free_balance) : 0,
            'user_id' => $this->data->getUserId(),
            'coin_id' => $this->data->getCoinId(),
        ];

        $user_balance = $this->storeUserDeposit($deposit_data);

        return (new DepositResource($user_balance))->toArray();
    }

    /**
     * this method calculates the asset and free balance amount for deposit
     * @param $asset
     * @return array
     */
    private function calculateAssetAndFreeBalance(int|float $action_asset, $asset)
    {
        $finial_asset = null;
        $free_balance = null;

        if (!is_null($asset)) {
            $finial_asset = floatval($action_asset + $asset->asset);  // D(n)
            $free_balance = $finial_asset - floatval($asset->liability);
        } else {
            $finial_asset = floatval($action_asset) + 0;              // D(n)
            $free_balance = floatval($action_asset);
        }

        return [$finial_asset, $free_balance];
    }
}