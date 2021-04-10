<?php

namespace Iamamirsalehi\LaravelBalance\Tests\Unit\Services\Balance;

use Iamamirsalehi\LaravelBalance\Services\Balance\BalanceService;
use Illuminate\Support\Facades\DB;
use Iamamirsalehi\LaravelBalance\Tests\TestCase;

class CancelOrderTest extends TestCase
{

    public function test_if_we_can_cancel_an_order()
    {
        list($user_id, $coin_id) = $this->getCoinAndUserId();

        $deposit = $this->deposit(200000);

        $withdraw_unconfirmed_yet = $this->withdrawUnconfirmedYet(100000);

        $cancel_order_price = $withdraw_unconfirmed_yet['action_liability'] * -1;

        $data = [
            'user_id' => $user_id,
            'coin_id' => $coin_id,
            'price' => $cancel_order_price
        ];

        $cancel_order = BalanceService::cancelOrder($data)->handle();

        $this->assertEquals($cancel_order_price * -1, $cancel_order['action_liability']);
        $this->assertEquals($cancel_order['asset'], $cancel_order['equity']);
        $this->assertEquals(0.0, $cancel_order['liability']);
    }

    public function tearDown(): void
    {
        $this->truncate();

        parent::tearDown();
    }
}