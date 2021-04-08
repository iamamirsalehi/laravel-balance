<?php

namespace Iamamirsalehi\LaravelBalance\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    use HasFactory;

    protected $table = 'deposits';

    protected $guarded = [];

    public function balances()
    {
        return $this->morphMany(Balance::class, 'balanceable');
    }
}
