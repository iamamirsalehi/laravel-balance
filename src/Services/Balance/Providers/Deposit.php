<?php

namespace Iamamirsalehi\LaravelBalance\Services\Balance\Providers;

use Iamamirsalehi\LaravelBalance\Utilities\CodeGenerator;
use Iamamirsalehi\LaravelBalance\Resources\DepositResource;
use Iamamirsalehi\LaravelBalance\Services\Balance\Contracts\BalanceInterface;

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

        $asset = null;
        $free_balance = null;

        if(!is_null($balance_asset))
        {
            $asset = $balance_action_asset + $balance_asset->balance_asset;  // D(n)
            $free_balance = $asset - $balance_asset->balance_liability;
        }else{
            $asset = $balance_action_asset + 0;  // D(n)
            $free_balance = $asset - 0;
        }    

        $balance_data = [
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

        $deposit_data = [
            'balance_code'             => CodeGenerator::make(),
            'balance_action_asset'     => $balance_action_asset,
            'balance_asset'            => $asset,
            'balance_action_liability' => 0,
            'balance_liability'        => 0,
            'balance_equity'           => $free_balance,
            'user_id'                  => $this->data->getUserId(),
            'coin_id'                  => $this->data->getCoinId(),
        ];

        $this->storeUserDeposit($deposit_data);

        $updated_asset = $this->storeUserBalance($data);

        return (new DepositResource($updated_asset))->toArray();
    }
}