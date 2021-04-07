<?php

namespace Iamamirsalehi\LaravelBalance\Services\Balance\Providers;

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
        $balance_action_liability = $this->data->getWithdrawUnconfirmedYetPrice();  // E(n)

        $asset =  $this->getTheLastBalanceRecordOfUser();                           //  F(n-1)

        $liability = null;

        $free_balance = null;

        if(!is_null($asset))
        {
            $liability = $asset->balance_liability + $balance_action_liability;         // F(n)

            $free_balance = $asset->balance_asset - $liability;                         // G(n)=D(n)-F(n)
        }else{
            $liability = 0 + $balance_action_liability;                                

            $free_balance = 0 - $liability;                                            
        }

        $data = [
            'balance_code'             => CodeGenerator::make(),
            'actionable_id'            => 2,
            'actionable_type'          => 'withdrawl_unconfirmed_yet',
            'balance_action_asset'     => 0,
            'balance_asset'            => $asset->balance_asset ?? 0,
            'balance_action_liability' => $balance_action_liability,
            'balance_liability'        => $liability,
            'balance_equity'           => $free_balance,
            'user_id'                  => $this->data->getUserId(),
            'coin_id'                  => $this->data->getCoinId(),
        ];

        $withdrawl_unconfirmed_yet = $this->storeUserBalance($data);

        return (new WithdrawUnconfirmedYetResource($withdrawl_unconfirmed_yet))->toArray();
    }
}