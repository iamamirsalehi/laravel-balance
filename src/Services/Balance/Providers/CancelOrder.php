<?php

namespace Iamamirsalehi\LaravelBalance\Services\Balance\Providers;

use Iamamirsalehi\LaravelBalance\Utilities\CodeGenerator;
use Iamamirsalehi\LaravelBalance\Resources\CancelOrderResource;
use Iamamirsalehi\LaravelBalance\Services\Balance\Contracts\BalanceInterface;
use Iamamirsalehi\LaravelBalance\Services\Balance\Exceptions\PriceMustBeValidException;

class CancelOrder extends BalanceInterface
{

    /**
     * Liability formula is F(n)=E(n)+F(n-1)
     *
     * E(n) -> the action of liability
     *
     * F(n-1) -> the last liability of the user
     *
     * F(n) -> the actual liability
     */
    public function handle()
    {
        $balance_action_liability = $this->data->getCancelOrderPrice();                 // E(n)

        $balance_liability =  $this->getTheLastBalanceRecordOfUser();                   //  F(n-1)

        $this->checkIfActionLiabilityIsLowerThanLiability($balance_action_liability, $balance_liability->balance_liability);

        $liability = $balance_liability->balance_liability + $balance_action_liability; // F(n)

        $free_balance = $balance_liability->balance_liability + $balance_liability->balance_equity;

        $data = [
            'balance_code'             => CodeGenerator::make(),
            'balance_action_asset'     => 0,
            'balance_asset'            => $balance_liability->balance_asset,
            'balance_action_liability' => $balance_action_liability * -1,
            'balance_liability'        => $liability,
            'balance_equity'           => $free_balance,
            'user_id'                  => $this->data->getUserId(),
            'coin_id'                  => $this->data->getCoinId(),
        ];

        $canceled_order = $this->storeUserBalance($data);

        return (new CancelOrderResource($canceled_order))->toArray();
    }

    private function checkIfActionLiabilityIsLowerThanLiability(int $action_liability, int $liability)
    {
        $action_liability = $action_liability * -1;

        if($action_liability > $liability)
            throw new PriceMustBeValidException('The action liability is bigger than liability');
    }
}