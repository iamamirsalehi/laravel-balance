<?php

namespace Iamamirsalehi\LaravelBalance\Services\Balance\Providers;

use Iamamirsalehi\LaravelBalance\Services\Balance\Exceptions\PriceMustBeValidException;
use Iamamirsalehi\LaravelBalance\Utilities\CodeGenerator;
use Iamamirsalehi\LaravelBalance\Services\Balance\Contracts\BalanceInterface;
use Iamamirsalehi\LaravelBalance\Resources\DepositResource as DepositResource;
use Iamamirsalehi\LaravelBalance\Resources\WithdrawUnconfirmedYetResource;

class WithdrawUnconfirmedYet extends BalanceInterface
{
    /**
     * formula is F(n)=E(n)+F(n-1)
     *
     * E(n) -> the action of liability
     *
     * F(n-1) -> the last liability of the user
     *
     * F(n) -> the actual liability
     */
    public function handle()
    {
        $action_liability = $this->data->getWithdrawUnconfirmedYetPrice();  // E(n)

        if ($action_liability < 0)
            throw new PriceMustBeValidException('Price must not be a negative number');
        $asset = $this->getTheLastBalanceRecordOfUser();                   //  F(n-1)


        list($liability, $free_balance) = $this->calculateLiabilityAndFreeBalance($action_liability, $asset);

        $data = [
            'tracking_code' => CodeGenerator::make(),
            'action_asset' => 0,
            'asset' => (int)$asset->asset ?? 0,
            'action_liability' => $action_liability,
            'liability' => $liability,
            'equity' => $free_balance,
            'user_id' => $this->data->getUserId(),
            'coin_id' => $this->data->getCoinId(),
        ];

        $withdraw_unconfirmed_yet = $this->storeWithdrawUnconfirmedYet($data);

        return (new WithdrawUnconfirmedYetResource($withdraw_unconfirmed_yet))->toArray();
    }

    private function calculateLiabilityAndFreeBalance(int $action_liability, $asset)
    {
        $liability = null;

        $free_balance = null;

        if (!is_null($asset)) {
            $liability = $asset->liability + $action_liability;         // F(n)

            $free_balance = $asset->asset - $liability;                 // G(n)=D(n)-F(n)
        } else {
            $liability = 0 + $action_liability;

            $free_balance = 0 - $liability;
        }

        return [$liability, $free_balance];
    }
}