<?php


namespace Iamamirsalehi\LaravelBalance\tests\Unit\Services\Balance;

use Iamamirsalehi\LaravelBalance\Models\Balance;
use Illuminate\Foundation\Testing\WithFaker;
use Iamamirsalehi\LaravelBalance\Models\User;
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
        $data = [
            'user_id' => 1,
            'coin_id' => 1,
            'deposit_price' => 120000
        ];

        $deposit = BalanceService::deposit($data)->handle();

        $this->assertIsArray($deposit);
        $this->assertArrayHasKey('tracking_code', $deposit);
    }

    public function test_we_get_exception_if_we_enter_a_lower_price_than_minimum_price()
    {
        $this->expectException(PriceMustBeValidException::class);

        $this->expectExceptionMessage('Deposit price must be more than ' . number_format(config('laravelBalance.minimum_deposit')));

        $data = [
            'user_id' => 7,
            'coin_id' => 1,
            'deposit_price' => 213
        ];

        BalanceService::deposit($data)->handle();
    }

    public function tearDown(): void
    {
        DB::table('balances')->truncate();

        parent::tearDown();
    }
}