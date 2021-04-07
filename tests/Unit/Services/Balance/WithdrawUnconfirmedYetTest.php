<?php

use Iamamirsalehi\LaravelBalance\Services\Balance\BalanceService;
use Iamamirsalehi\LaravelBalance\Tests\TestCase;
use Illuminate\Support\Facades\DB;

class WithdrawUnconfirmedYetTest extends TestCase
{
    public function test_if_we_can_do_withdraw_unconfirmed_yet()
    {
        $user_id =  (int) DB::table('users')->updateOrInsert([
            'mobile' => '09392126508',
            'password' => 'password',
            'mobile_verified' => 1,
        ]);

        $coin_id =  (int) DB::table('coins')->updateOrInsert([
            'coin_persian_name' => 'ریال',
            'coin_english_name' => 'IRR',
        ]);

        $deposit_price = 200000;

        BalanceService::deposit([
            'user_id' => $user_id,
            'coin_id' => $coin_id,
            'price'   => $deposit_price,
        ])->handle();

        $withrawl_unconfirmed_yet_price = 100000;

        $data = [
            'user_id' => $user_id,
            'coin_id' => $coin_id,
            'price'   => $withrawl_unconfirmed_yet_price
        ];

        $result = BalanceService::withdrawUnconfirmedYet($data)->handle();

        $this->assertIsArray($result);
        $this->assertArrayHasKey('balance_code', $result);
        $this->assertIsInt($result['balance_code']);
    

        $withrawl_unconfirmed_yet = DB::table('balances')->where('user_id', '=',$user_id)
                                                         ->where('coin_id', '=', $coin_id)
                                                         ->orderBy('id', 'desc')->first();

        $this->assertEquals($withrawl_unconfirmed_yet_price, $withrawl_unconfirmed_yet->balance_action_liability);
        $this->assertEquals($withrawl_unconfirmed_yet_price, $withrawl_unconfirmed_yet->balance_liability);
        $this->assertEquals($withrawl_unconfirmed_yet_price, $withrawl_unconfirmed_yet->balance_equity);
        $this->assertEquals($withrawl_unconfirmed_yet_price, $withrawl_unconfirmed_yet->balance_equity);
        $this->assertEquals(0, $withrawl_unconfirmed_yet->balance_action_asset);
    }

    public function tearDown(): void
    {
        DB::table('balances')->truncate();

        parent::tearDown();
    }
}