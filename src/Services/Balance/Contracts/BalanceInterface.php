<?php

namespace Iamamirsalehi\LaravelBalance\Services\Balance\Contracts;

use Iamamirsalehi\LaravelBalance\Services\Balance\Validator\Validator;

abstract class BalanceInterface
{
    protected $data;

    protected $balance_repository;

    protected $coin_repository;

    protected $withdraw_repository;

    protected $deposit_repository;

    public function __construct(array $data)
    {
        $this->balance_repository = resolve(config('laravelBalance.repositories.balance'));

        $this->coin_repository = resolve(config('laravelBalance.repositories.coin'));

        $this->withdraw_repository = resolve(config('laravelBalance.repositories.withdraw'));

        $this->deposit_repository = resolve(config('laravelBalance.repositories.deposit'));

        $this->data = new Validator($data);
    }

    public function getTheLastBalanceRecordOfUser()
    {
        $last_balance_record_of_user = $this->balance_repository->where([
            ['user_id', '=', $this->data->getUserId()],
            ['coin_id', '=', $this->data->getCoinId()],
        ])->orderBy('id', 'desc')->first();

        return $last_balance_record_of_user;
    }

    public function storeUserBalance(array $data, $type = null)
    {
        $last_balance_record_of_user = $this->balance_repository->create($data);

        return $last_balance_record_of_user;
    }

    public function storeUserDeposit(array $data)
    {
        $stored_deposit = $this->deposit_repository->create($data);

        $user_balance = $stored_deposit->balances()->create($data);

        return $user_balance;
    }

    public function storeWithdrawUnconfirmedYet(array $data)
    {
        $stored_withdraw = $this->withdraw_repository->create($data);

        $user_withrawl = $stored_withdraw->balances()->create($data);

        return $user_withrawl;
    }

    public function storeWithdrawconfirmed(array $data)
    {
        $stored_withdraw = $this->withdraw_repository->create($data);

        $user_withdraw = $stored_withdraw->balances()->create($data);

        return $user_withdraw;
    }

    public function storeCancelOrder(array $data)
    {
        $stored_withdraw = $this->withdraw_repository->create($data);

        $user_withrawl = $stored_withdraw->balances()->create($data);

        return $user_withrawl;
    }

    abstract public function handle();
}
