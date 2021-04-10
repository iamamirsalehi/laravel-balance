<?php


namespace Iamamirsalehi\LaravelBalance\tests\Unit\Services\Balance;


use Iamamirsalehi\LaravelBalance\Services\Balance\BalanceService;
use Iamamirsalehi\LaravelBalance\Services\Balance\Exceptions\ActionNotFoundException;
use Iamamirsalehi\LaravelBalance\Services\Balance\Providers\CancelOrder;
use Iamamirsalehi\LaravelBalance\Services\Balance\Providers\Deposit;
use Iamamirsalehi\LaravelBalance\Services\Balance\Providers\ExecuteOrder;
use Iamamirsalehi\LaravelBalance\Services\Balance\Providers\PendingOrder;
use Iamamirsalehi\LaravelBalance\Services\Balance\Providers\RejectedWithdraw;
use Iamamirsalehi\LaravelBalance\Services\Balance\Providers\WithdrawConfirmed;
use Iamamirsalehi\LaravelBalance\Services\Balance\Providers\WithdrawUnconfirmedYet;
use Iamamirsalehi\LaravelBalance\Tests\TestCase;

class BalanceServiceTest extends TestCase
{
    public function test_if_user_can_call_actions_by_static_call()
    {
        $data = [
            'test'
        ];

        $deposit = BalanceService::deposit($data);
        $cancelOrder = BalanceService::cancelOrder($data);
        $executeOrder = BalanceService::executeOrder($data);
        $pendingOrder = BalanceService::pendingOrder($data);
        $rejectedOrder = BalanceService::rejectedWithraw($data);
        $withdrawConfirmed = BalanceService::withdrawConfirmed($data);
        $withdrawUnconfirmedYet = BalanceService::withdrawUnconfirmedYet($data);

        $this->assertInstanceOf(Deposit::class, $deposit);
        $this->assertInstanceOf(CancelOrder::class, $cancelOrder);
        $this->assertInstanceOf(ExecuteOrder::class, $executeOrder);
        $this->assertInstanceOf(PendingOrder::class, $pendingOrder);
        $this->assertInstanceOf(RejectedWithdraw::class, $rejectedOrder);
        $this->assertInstanceOf(WithdrawConfirmed::class, $withdrawConfirmed);
        $this->assertInstanceOf(WithdrawUnconfirmedYet::class, $withdrawUnconfirmedYet);
    }

    public function test_user_get_exception_when_he_calls_a_not_existing_action()
    {
        $this->expectException(ActionNotFoundException::class);

        $dummyAction = BalanceService::dummyAction();

        $error_message = ucfirst($dummyAction . ' action not found, please call an existing action');

        $this->expectExceptionMessage($error_message);
    }
}