<?php


namespace Iamamirsalehi\LaravelBalance\Services\Balance\Validator;

use Iamamirsalehi\LaravelBalance\Services\Balance\Exceptions\MustBeExistedException;
use Iamamirsalehi\LaravelBalance\Services\Balance\Exceptions\NumberMustBeIntegerException;
use Iamamirsalehi\LaravelBalance\Services\Balance\Exceptions\PriceMustBeValidException;

class Validator
{
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Whenever is need to get user id, this method check if user id is valid or not
     *
     * @return mixed
     * @throws MustBeExistedException
     * @throws NumberMustBeIntegerException
     */
    public function getUserId()
    {
        $this->checkIfKeyExistsAndIsInteger('user_id');

        return $this->data['user_id'];
    }

    /**
     * Whenever is need to get coin id, this method check if coin id is valid or not
     * @return mixed
     * @throws MustBeExistedException
     * @throws NumberMustBeIntegerException
     */
    public function getCoinId()
    {
        $this->checkIfKeyExistsAndIsInteger('coin_id');

        return $this->data['coin_id'];
    }

    /**
     * Whenever is need to get price for deposit action, this method check if price is valid or not
     * @return mixed
     * @throws MustBeExistedException
     * @throws NumberMustBeIntegerException
     * @throws PriceMustBeValidException
     */
    public function getDepositPrice()
    {
        $this->checkIfKeyExistsAndIsInteger('price');

        if ($this->data['price'] < config('laravelBalance.minimum_deposit'))
            throw new PriceMustBeValidException('Deposit price must be more than ' . number_format(config('laravelBalance.minimum_deposit')));

        if ($this->data['price'] > config('laravelBalance.maximum_deposit'))
            throw new PriceMustBeValidException('Deposit price must be lower than ' . number_format(config('laravelBalance.maximum_deposit')));

        return $this->data['price'];
    }

    /**
     * Whenever is need to get price for cancel order action, this method check if price is valid or not
     *
     * @return mixed
     * @throws MustBeExistedException
     * @throws NumberMustBeIntegerException
     * @throws PriceMustBeValidException
     */
    public function getCancelOrderPrice()
    {
        $this->checkIfKeyExistsAndIsInteger('price');

        if ($this->data['price'] > 0)
            throw new PriceMustBeValidException('The cancel order price must be negative');

        return $this->data['price'];
    }

    /**
     * Whenever is need to get price for withdraw unconfirmed yet action, this method check if price is valid or not
     * @return mixed
     * @throws MustBeExistedException
     * @throws NumberMustBeIntegerException
     */
    public function getWithdrawUnconfirmedYetPrice()
    {
        $this->checkIfKeyExistsAndIsInteger('price');

        return $this->data['price'];
    }

    /**
     * Whenever is need to get withdraw id, this method check if withdraw id is valid or not
     * @return mixed
     * @throws MustBeExistedException
     * @throws NumberMustBeIntegerException
     */
    public function getWithdrawId()
    {
        $this->checkIfKeyExistsAndIsInteger('withdraw_id');

        return $this->data['withdraw_id'];
    }

    /**
     * Whenever is need to get rejected admin description , this method check if is set or is not empty to return
     * @return mixed|null
     */
    public function getIsAdminRejectedDescription()
    {
        if(isset($this->data['is_admin_rejected_description']) and !empty($this->data['is_admin_rejected_description']))
            return $this->data['is_admin_rejected_description'];

        return null;
    }

    /**
     * This method get one key and checks if the key is integer and is existed in the array data
     * @param $key
     * @throws MustBeExistedException
     * @throws NumberMustBeIntegerException
     */
    public function checkIfKeyExistsAndIsInteger($key)
    {
        if (!array_key_exists($key, $this->data))
            throw new MustBeExistedException('[' . $key . '] key must be existed in the data');

        if (!is_int($this->data[$key]))
            throw new NumberMustBeIntegerException($key . ' must be an integer');
    }

}