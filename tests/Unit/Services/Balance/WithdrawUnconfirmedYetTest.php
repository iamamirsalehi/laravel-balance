<?php

use Iamamirsalehi\LaravelBalance\Tests\TestCase;
use Illuminate\Support\Facades\DB;

class WithdrawUnconfirmedYetTest extends TestCase
{
    public function tearDown(): void
    {
        DB::table('balances')->truncate();

        parent::tearDown();
    }
}