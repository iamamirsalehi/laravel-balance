<?php


namespace Iamamirsalehi\LaravelBalance\tests\Unit\Services\Balance;

use App\Models\User;
use Iamamirsalehi\LaravelBalance\Services\Balance\BalanceService;
use Iamamirsalehi\LaravelBalance\Services\Balance\Exceptions\PriceMustBeValidException;
use Iamamirsalehi\LaravelBalance\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class DepositTest extends TestCase
{
    public function test_if_user_can_deposit()
    {
        $user = User::factory()->create();
        
        $data = [
            'user_id' => $user->id,
            'coin_id' => 1,
            'deposit_price' => 120000
        ];

        $deposit = BalanceService::deposit($data)->handle();

        $this->assertIsInt($deposit);
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

}