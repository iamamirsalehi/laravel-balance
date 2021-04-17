<?php

namespace Iamamirsalehi\LaravelBalance\Models;

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

    public function user()
    {
        return $this->belongsTo(config('repositories.user'));
    }
}