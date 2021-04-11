<?php

namespace Iamamirsalehi\LaravelBalance\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DepositResource
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray()
    {
        return [
            'tracking_code' => $this->data->tracking_code,
            'balanceable_id' => $this->data->balanceable_id,
            'balanceable_type' => $this->data->balanceable_type,
            'action_asset' => $this->data->action_asset,
            'asset' => $this->data->asset,
            'action_liability' => $this->data->action_liability,
            'liability' => $this->data->liability,
            'equity' => $this->data->equity,
            'user_id' => $this->data->user_id,
            'coin_id' => $this->data->coin_id,
        ];
    }
}