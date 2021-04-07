<?php

namespace Iamamirsalehi\LaravelBalance\Tests\Unit\Services\Balance;

use Illuminate\Support\Facades\DB;
use Iamamirsalehi\LaravelBalance\Tests\TestCase;

class CancelOrderTest extends TestCase
{

    public function test_if_we_can_cancel_an_order()
    {
        list($user_id, $coin_id) = $this->getCoinAndUserId(); 

        
    }

    public function tearDown(): void
    {
        DB::table('balances')->truncate();

        parent::tearDown();
    }
}