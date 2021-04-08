<?php


namespace Iamamirsalehi\LaravelBalance\tests\Unit\Services\Balance;


use Iamamirsalehi\LaravelBalance\Models\Withdraw;
use Iamamirsalehi\LaravelBalance\Services\Balance\BalanceService;
use Iamamirsalehi\LaravelBalance\Tests\TestCase;

class WithdrawConfirmedTest extends TestCase
{
    public function test_we_can_confirm_a_withdraw()
    {
        list($user_id, $coin_id) = $this->getCoinAndUserId();

        $deposit = $this->deposit(200000);

        $unconfirmed_withdraw = $this->withdrawUnconfirmedYet(-100000);

        $data = [
            'user_id' => $user_id,
            'coin_id' => $coin_id,
        ];

        $confirmed_withdraw = BalanceService::withdrawConfirmed($data);

        $this->assertIsArray($confirmed_withdraw);
        $this->assertEquals(Withdraw::CONFIRMED, $confirmed_withdraw['balance_is_admin_confirmed']);
    }

    public function tearDown(): void
    {
        $this->truncate();

        parent::tearDown(); // TODO: Change the autogenerated stub
    }
}