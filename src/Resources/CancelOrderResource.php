<?php

namespace Iamamirsalehi\LaravelBalance\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CancelOrderResource
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
            'balance_code'             => $this->data->balance_code,
            'actionable_id'            => $this->data->actionable_id,
            'actionable_type'          => $this->data->actionable_type,
            'balance_action_asset'     => $this->data->balance_action_asset,
            'balance_asset'            => $this->data->balance_asset,
            'balance_action_liability' => $this->data->balance_action_liability,
            'balance_liability'        => $this->data->balance_liability,
            'balance_equity'           => $this->data->balance_equity,
            'user_id'                  => $this->data->user_id,
            'coin_id'                  => $this->data->coin_id,
        ];
    }
}