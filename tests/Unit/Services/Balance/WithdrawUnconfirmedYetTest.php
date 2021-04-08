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
        $this->assertArrayHasKey('balance_code', $result);
        $this->assertIsInt($result['balance_code']);
    

        // test in balance table
        $withdraw_unconfirmed_yet_in_balance_table = DB::table('balances')->where('user_id', '=',$user_id)
                                                         ->where('coin_id', '=', $coin_id)
                                                         ->orderBy('id', 'desc')->first();

        $this->assertEquals($withdraw_unconfirmed_yet_price, $withdraw_unconfirmed_yet_in_balance_table->balance_action_liability);
        $this->assertEquals($withdraw_unconfirmed_yet_price, $withdraw_unconfirmed_yet_in_balance_table->balance_liability);
        $this->assertEquals($withdraw_unconfirmed_yet_price, $withdraw_unconfirmed_yet_in_balance_table->balance_equity);
        $this->assertEquals($withdraw_unconfirmed_yet_price, $withdraw_unconfirmed_yet_in_balance_table->balance_equity);
        $this->assertEquals(0, $withdraw_unconfirmed_yet_in_balance_table->balance_action_asset);


        // test in withdraw table
        $withdraw_unconfirmed_yet_in_withdraw_table = DB::table('withdraws')
                                                        ->where('user_id', '=',$user_id)
                                                        ->where('coin_id', '=', $coin_id)
                                                        ->orderBy('id', 'desc')->first();

        $this->assertEquals($withdraw_unconfirmed_yet_price, $withdraw_unconfirmed_yet_in_withdraw_table->balance_action_liability);
        $this->assertEquals($withdraw_unconfirmed_yet_price, $withdraw_unconfirmed_yet_in_withdraw_table->balance_liability);
        $this->assertEquals($withdraw_unconfirmed_yet_price, $withdraw_unconfirmed_yet_in_withdraw_table->balance_equity);
        $this->assertEquals($withdraw_unconfirmed_yet_price, $withdraw_unconfirmed_yet_in_withdraw_table->balance_equity);
        $this->assertEquals(0, $withdraw_unconfirmed_yet_in_withdraw_table->balance_action_asset);
    }

    public function tearDown(): void
    {
        DB::table('balances')->truncate();

        parent::tearDown();
    }
}