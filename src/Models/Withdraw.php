<?php

namespace Iamamirsalehi\LaravelBalance\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdraw extends Model
{
    use HasFactory;

    protected $table = 'withdraws';

    protected $guarded = [];
}
