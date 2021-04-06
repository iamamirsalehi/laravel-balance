<?php

namespace Iamamirsalehi\LaravelBalance\Services\Balance\Providers;

use Iamamirsalehi\LaravelBalance\Services\Balance\Contracts\BalanceInterface;
use Iamamirsalehi\LaravelBalance\Services\Balance\Exceptions\PriceMustBeValidException;
use Iamamirsalehi\LaravelBalance\src\Utilities\CodeGenerator;

class CancelOrder extends BalanceInterface
{

    /**
     * asset formula is F(n)=E(n)+F(n-1)
     *
     * E(n) -> the action of liability
     *
     * F(n-1) -> the last liability of the user
     *
     * F(n) -> the actual liability
     */
    public function handle()
    {
        $cancel_order =  $this->getTheLastBalanceRecordOfUser(); //  F(n-1)

        $balance_action_liability = $this->data->getCancelOrderPrice();

        $this->checkIfBalanceHasActionLiability($balance_action_liability, $cancel_order->balance_liability);

        $liability = $cancel_order->balance_liability + $balance_action_liability;

        $data = [
            'balance_code'             => CodeGenerator::make(),
            'actionable_id'            => 9,
            'actionable_type'          => 'cancel_order',
            'balance_action_asset'     => 0,
            'balance_asset'            => $cancel_order->balance_asset,
            'balance_action_liability' => $balance_action_liability,
            'balance_liability'        => $liability,
            'balance_equity'           => $cancel_order->balance_equity,
            'user_id'                  => $this->data->getUserId(),
            'coin_id'                  => $this->data->getCoinId(),
        ];

        $canceled_order = $this->storeUserBalance($data);

        return $canceled_order ? true : false;
    }

    private function checkIfBalanceHasActionLiability(int $action_liability, int $liability)
    {
        $action_liability = $action_liability * -1;

        if($action_liability > $liability)
            throw new PriceMustBeValidException('The action liability is bigger than liability');
    }
}