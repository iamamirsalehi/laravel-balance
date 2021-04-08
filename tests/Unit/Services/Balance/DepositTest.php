<?php


namespace Iamamirsalehi\LaravelBalance\tests\Unit\Services\Balance;

use Illuminate\Foundation\Testing\WithFaker;
use Iamamirsalehi\LaravelBalance\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Iamamirsalehi\LaravelBalance\Services\Balance\BalanceService;
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
        $this->assertArrayHasKey('balance_code', $deposit);
        $this->assertIsInt($deposit['balance_code']);
        $this->assertEquals($deposit['balance_code'], $submited_deposit->balance_code);
        $this->assertEquals($deposit['balance_action_asset'], $submited_deposit->balance_action_asset);
        $this->assertEquals($deposit['balance_asset'], $submited_deposit->balance_asset);
        $this->assertEquals($deposit['balance_action_liability'], $submited_deposit->balance_action_liability);
        $this->assertEquals($deposit['balance_liability'], $submited_deposit->balance_liability);
        $this->assertEquals($deposit['balance_equity'], $submited_deposit->balance_equity);

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

    public function tearDown(): void
    {
        DB::table('balances')->truncate();

        parent::tearDown();
    }
}