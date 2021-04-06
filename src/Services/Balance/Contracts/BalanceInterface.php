<?php

namespace Iamamirsalehi\LaravelBalance\Services\Balance\Contracts;

use Iamamirsalehi\LaravelBalance\src\Services\Balance\Validator\Validator;

abstract class BalanceInterface
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = new Validator($data);
    }

    public function getTheLastBalanceRecordOfUser()
    {
        $repository = $this->data->getBalanceRepository();

        $last_balance_record_of_user = $repository->where([
            ['user_id', '=', $this->data->getUserId()],
            ['coin_id', '=', $this->data->getCoinId()],
        ])->orderBy('id', 'desc')->first();

        return $last_balance_record_of_user;
    }

    public function increaseUserAsset(array $data)
    {
        $repository = $this->data->getBalanceRepository();

        $last_balance_record_of_user = $repository->store([
            ''
        ]);
    }

    abstract public function handle();
}
