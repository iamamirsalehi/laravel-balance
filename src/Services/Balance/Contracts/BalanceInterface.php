<?php

namespace Iamamirsalehi\LaravelBalance\Services\Balance\Contracts;

use Iamamirsalehi\LaravelBalance\src\Services\Balance\Validator\Validator;

abstract class BalanceInterface
{
    protected $data;

    protected $balance_repository;

    protected $coin_repository;

    public function __construct(array $data)
    {
        $this->balance_repository = resolve(config('laravelBalance.repositories.balance'));

        $this->coin_repository    = resolve(config('laravelBalance.repositories.coin'));

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

    public function increaseUserAsset(array $data)
    {
        $last_balance_record_of_user = $this->balance_repository->store($data);


    }

    abstract public function handle();
}
