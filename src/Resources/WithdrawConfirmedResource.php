<?php


namespace Iamamirsalehi\LaravelBalance\Resources;


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
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray()
    {
        return [
            'id' => $this->data->id,
            'tracking_code' => $this->data->tracking_code,
            'balanceable_id' => $this->data->balanceable_id,
            'balanceable_type' => $this->data->balanceable_type,
            'action_asset' => $this->data->action_asset,
            'asset' => $this->data->asset,
            'action_liability' => $this->data->action_liability,
            'liability' => $this->data->liability,
            'equity' => $this->data->equity,
            'is_admin_confirmed' => $this->data->is_admin_confirmed,
            'user_id' => $this->data->user_id,
            'coin_id' => $this->data->coin_id,
        ];
    }
}