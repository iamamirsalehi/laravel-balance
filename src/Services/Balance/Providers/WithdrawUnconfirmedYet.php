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
     * this method is called When user wants to withdraw from his balance
     * @return array
     * @throws PriceMustBeValidException
     * @throws \Iamamirsalehi\LaravelBalance\Services\Balance\Exceptions\MustBeExistedException
     * @throws \Iamamirsalehi\LaravelBalance\Services\Balance\Exceptions\NumberMustBeIntegerException
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
            'asset' => !is_null($asset?->asset) ? floatval($asset->asset) : 0,
            'action_liability' => floatval($action_liability),
            'liability' => floatval($liability),
            'equity' => floatval($free_balance),
            'user_id' => $this->data->getUserId(),
            'coin_id' => $this->data->getCoinId(),
        ];

        $withdraw_unconfirmed_yet = $this->storeWithdrawUnconfirmedYet($data);

        return (new WithdrawUnconfirmedYetResource($withdraw_unconfirmed_yet))->toArray();
    }

    /**
     * this method calculates the liability and free balance of user
     * @param $asset
     * @return array
     */
    private function calculateLiabilityAndFreeBalance(int|float $action_liability, $asset)
    {
        $liability = null;

        $free_balance = null;

        if (!is_null($asset)) {
            $liability = floatval($asset->liability + $action_liability);         // F(n)

            $free_balance = floatval($asset->asset) - $liability;                 // G(n)=D(n)-F(n)
        } else {
            $liability = 0 + floatval($action_liability);

            $free_balance = 0 - floatval($liability);
        }

        return [$liability, $free_balance];
    }
}