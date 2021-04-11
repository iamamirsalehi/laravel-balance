<?php


namespace Iamamirsalehi\LaravelBalance\tests\Unit\Services\Balance;

use Iamamirsalehi\LaravelBalance\Tests\TestCase;
use Iamamirsalehi\LaravelBalance\Services\Balance\Exceptions\PriceMustBeValidException;
use Illuminate\Support\Facades\DB;

class DepositTest extends TestCase
{
    public function test_if_user_can_deposit()
    {
        list($user_id, $coin_id) = $this->getCoinAndUserId();

        $deposit = $this->deposit(120000);

        $submited_deposit = DB::table('deposits')
            ->where('user_id', '=', $user_id)
            ->where('coin_id', '=', $coin_id)
            ->orderBy('id', 'desc')
            ->first();

        $this->assertIsArray($deposit);
        $this->assertArrayHasKey('tracking_code', $deposit);
        $this->assertIsInt($deposit['tracking_code']);
        $this->assertEquals($deposit['tracking_code'], $submited_deposit->tracking_code);
        $this->assertEquals($deposit['action_asset'], $submited_deposit->action_asset);
        $this->assertEquals($deposit['asset'], $submited_deposit->asset);
        $this->assertEquals($deposit['action_liability'], $submited_deposit->action_liability);
        $this->assertEquals($deposit['liability'], $submited_deposit->liability);
        $this->assertEquals($deposit['equity'], $submited_deposit->equity);

        return [
            'user_id' => $user_id,
            'coin_id' => $coin_id
        ];
    }

    public function test_we_get_exception_if_we_enter_a_lower_price_than_minimum_price()
    {
        $this->expectException(PriceMustBeValidException::class);

        $this->expectExceptionMessage('Deposit price must be more than ' . number_format(config('laravelBalance.minimum_deposit')));

        $deposit = $this->deposit(213);
    }

    public function test_we_can_deposit_when_user_balance_has_a_liability()
    {
        list($user_id, $coin_id) = $this->getCoinAndUserId();

        $this->deposit(500000);

        $this->withdrawUnconfirmedYet(200000);

        $this->deposit(100000);

        $user_balance = DB::table('balances')->where('user_id', '=', $user_id)
            ->where('coin_id', '=', $coin_id)
            ->orderByDesc('id')
            ->first();

        $this->assertEquals($user_balance->action_asset, 100000);
        $this->assertEquals($user_balance->asset, 600000);
        $this->assertEquals($user_balance->liability, 200000);
        $this->assertEquals($user_balance->action_liability, 200000);
        $this->assertEquals($user_balance->equity, 400000);
    }

    public function tearDown(): void
    {
        $this->truncate();

        parent::tearDown();
    }
}