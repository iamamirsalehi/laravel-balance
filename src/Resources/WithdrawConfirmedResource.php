<?php


namespace Iamamirsalehi\LaravelBalance\src\Resources;


class WithdrawConfirmedResource
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray()
    {
        return [
            'balance_code'               => $this->data->balance_code,
            'balanceable_id'             => $this->data->balanceable_id,
            'balanceable_type'           => $this->data->balanceable_type,
            'balance_action_asset'       => $this->data->balance_action_asset,
            'balance_asset'              => $this->data->balance_asset,
            'balance_action_liability'   => $this->data->balance_action_liability,
            'balance_is_admin_confirmed' => $this->data->balance_is_admin_confirmed,
            'balance_liability'          => $this->data->balance_liability,
            'balance_equity'             => $this->data->balance_equity,
            'user_id'                    => $this->data->user_id,
            'coin_id'                    => $this->data->coin_id,
        ];
    }
}