<?php

use Iamamirsalehi\LaravelBalance\Services\Balance\BalanceService;
use Iamamirsalehi\LaravelBalance\Tests\TestCase;
use Illuminate\Support\Facades\DB;

class WithdrawUnconfirmedYetTest extends TestCase
{
    public function test_if_we_can_do_withdraw_unconfirmed_yet()
    {
        list($user_id, $coin_id) = $this->getCoinAndUserId(); 

        $deposit_price = 200000;

        $this->deposit($deposit_price);

        $withdraw_unconfirmed_yet_price = 100000;

        $result = $this->withdrawUnconfirmedYet($withdraw_unconfirmed_yet_price);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('tracking_code', $result);
        $this->assertIsInt($result['tracking_code']);
    

        // test in balance table
        $withdraw_unconfirmed_yet_in_table = DB::table('balances')->where('user_id', '=',$user_id)
                                                         ->where('coin_id', '=', $coin_id)
                                                         ->orderBy('id', 'desc')->first();

        $this->assertEquals($withdraw_unconfirmed_yet_price, $withdraw_unconfirmed_yet_in_table->action_liability);
        $this->assertEquals($withdraw_unconfirmed_yet_price, $withdraw_unconfirmed_yet_in_table->liability);
        $this->assertEquals($withdraw_unconfirmed_yet_price, $withdraw_unconfirmed_yet_in_table->equity);
        $this->assertEquals($withdraw_unconfirmed_yet_price, $withdraw_unconfirmed_yet_in_table->equity);
        $this->assertEquals(0, $withdraw_unconfirmed_yet_in_table->action_asset);


        // test in withdraw table
        $withdraw_unconfirmed_yet_in_withdraw_table = DB::table('withdraws')
                                                        ->where('user_id', '=',$user_id)
                                                        ->where('coin_id', '=', $coin_id)
                                                        ->orderBy('id', 'desc')->first();

        $this->assertEquals($withdraw_unconfirmed_yet_price, $withdraw_unconfirmed_yet_in_withdraw_table->action_liability);
        $this->assertEquals($withdraw_unconfirmed_yet_price, $withdraw_unconfirmed_yet_in_withdraw_table->liability);
        $this->assertEquals($withdraw_unconfirmed_yet_price, $withdraw_unconfirmed_yet_in_withdraw_table->equity);
        $this->assertEquals($withdraw_unconfirmed_yet_price, $withdraw_unconfirmed_yet_in_withdraw_table->equity);
        $this->assertEquals(0, $withdraw_unconfirmed_yet_in_withdraw_table->action_asset);
    }

    public function tearDown(): void
    {
        $this->truncate();

        parent::tearDown();
    }
}