<?php


namespace Iamamirsalehi\LaravelBalance\Services\Balance\Validator;


use Iamamirsalehi\LaravelBalance\Services\Balance\Exceptions\CoinIdMustBeExistedInTheData;
use Iamamirsalehi\LaravelBalance\Services\Balance\Exceptions\DepositPriceMustBeExistedException;
use Iamamirsalehi\LaravelBalance\Services\Balance\Exceptions\PriceMustBeValidException;
use Iamamirsalehi\LaravelBalance\Services\Balance\Exceptions\UserIdMustBeExistedInDataException;
use Iamamirsalehi\LaravelBalance\Services\Balance\Exceptions\IdMustBeIntegerException;

class Validator
{
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getUserId()
    {
        if(!array_key_exists('user_id', $this->data))
            throw new UserIdMustBeExistedInDataException('[user_id] must be existed in the data');

        if(!is_int($this->data['user_id']))
            throw new IdMustBeIntegerException('User id must be an integer');

        return $this->data['user_id'];
    }

    public function getCoinId()
    {
        if(!array_key_exists('coin_id', $this->data))
            throw new CoinIdMustBeExistedInTheData('[coin_id] key must be existed in the data');

        if(!is_int($this->data['coin_id']))
            throw new IdMustBeIntegerException('Coin id must be an integer');

        return $this->data['coin_id'];
    }

    public function getDepositPrice()
    {
        if(!array_key_exists('price', $this->data))
            throw new DepositPriceMustBeExistedException('[price] key must be existed in the data');

        if($this->data['price'] < config('laravelBalance.minimum_deposit'))
            throw new PriceMustBeValidException('Deposit price must be more than ' . number_format(config('laravelBalance.minimum_deposit')));

        if($this->data['price'] > config('laravelBalance.maximum_withdraw'))
            throw new PriceMustBeValidException('Deposit price must be lower than ' . number_format(config('laravelBalance.maximum_deposit')));

        return $this->data['price'];
    }

    public function getCancelOrderPrice()
    {
        if(!array_key_exists('price', $this->data))
            throw new DepositPriceMustBeExistedException('[price] key must be existed in the data');

        if(!is_int($this->data['price']))
            throw new IdMustBeIntegerException('cancel order price must be an integer');

        if($this->data['price'] > 0)
            throw new PriceMustBeValidException('The cancel order price must be negative');
        return $this->data['price'];
    }

    public function getWithdrawUnconfirmedYetPrice()
    {
        if(!array_key_exists('price', $this->data))
            throw new DepositPriceMustBeExistedException('[price] key must be existed in the data');

        if(!is_int($this->data['price']))
            throw new IdMustBeIntegerException('price must be an integer');

        return $this->data['price'];
    }
}