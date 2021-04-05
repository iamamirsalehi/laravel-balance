<?php

namespace Iamamirsalehi\LaravelBalance\Services\Balance\Contracts;

abstract class BalanceInterface
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    abstract public function handle();
}
