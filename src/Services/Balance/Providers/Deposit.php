<?php

namespace Iamamirsalehi\LaravelBalance\Services\Balance\Providers;

use Iamamirsalehi\LaravelBalance\Services\Balance\Contracts\BalanceInterface;
use Iamamirsalehi\LaravelBalance\src\Utilities\CodeGenerator;

class Deposit extends BalanceInterface
{
    /**
     * asset formula is D(n)=C(n)+D(n-1)
     *
     * C(n) -> the input (price) that increase the value of asset straightly
     *
     * D(n-1) -> the last user asset that had
     *
     * D(n) -> the current asset of user
     *
     */
    public function handle()
    {
        $balance_action_asset = $this->data->getDepositPrice();          // C(n)

        $balance_asset        =  $this->getTheLastBalanceRecordOfUser(); // D(n-1)

        $asset = $balance_action_asset + $balance_asset->balance_asset;  // D(n)

        $free_balance = $asset - $balance_asset->balance_liability;

        $data = [
            'balance_code'             => CodeGenerator::make(),
            'actionable_id'            => 1,
            'actionable_type'          => 'deposit',
            'balance_action_asset'     => $balance_action_asset,
            'balance_asset'            => $asset,
            'balance_action_liability' => 0,
            'balance_liability'        => 0,
            'balance_equity'           => $free_balance,
            'user_id'                  => $this->data->getUserId(),
            'coin_id'                  => $this->data->getCoinId(),
        ];

        $updated_asset = $this->storeUserBalance($data);

        return $updated_asset ? ['tracking_code' => $updated_asset->balance_code] : false;
    }
}