<?php

namespace Iamamirsalehi\LaravelBalance\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdraw extends Model
{
    use HasFactory;

    protected $table = 'withdraws';

    protected $guarded = [];

    public const UNCONFIRMED = 0;

    public const CONFIRMED = 1;

    public const REJECTED = 2;

    public function balances()
    {
        return $this->morphMany(Balance::class, 'balanceable');
    }
}
