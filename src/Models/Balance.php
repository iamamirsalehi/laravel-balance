<?php

namespace Iamamirsalehi\LaravelBalance\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Balance extends Model
{
    use HasFactory;

    protected $table = 'balances';

    protected $guarded = [];

    public function balanceable()
    {
        return $this->morphTo();
    }
}