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

    abstract public function handle();
}
