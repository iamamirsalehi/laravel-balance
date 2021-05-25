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
        $action_liability = $this->data->getCancelOrderPrice(); // E(n)

        $liability_record = $this->getTheLastBalanceRecordOfUser();   //  F(n-1)

        $this->checkIfActionLiabilityIsLowerThanLiability($action_liability, $liability_record->liability);

        $liability = $liability_record->liability + $action_liability; // F(n)

        $free_balance = $liability_record->liability + $liability_record->equity;

        $data = [
            'tracking_code' => CodeGenerator::make(),
            'action_asset' => 0,
            'asset' => $liability_record->asset,
            'action_liability' => $action_liability * -1,
            'liability' => $liability,
            'equity' => $free_balance,
            'user_id' => $this->data->getUserId(),
            'coin_id' => $this->data->getCoinId(),
        ];

        $canceled_order = $this->storeUserBalance($data);

        return (new CancelOrderResource($canceled_order))->toArray();
    }

    private function checkIfActionLiabilityIsLowerThanLiability($action_liability, $liability)
    {
        $action_liability = $action_liability * -1;

        if ($action_liability > $liability)
            throw new PriceMustBeValidException('The action liability is bigger than liability');
    }
}