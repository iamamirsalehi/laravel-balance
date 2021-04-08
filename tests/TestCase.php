<?php

namespace Iamamirsalehi\LaravelBalance\Tests;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Iamamirsalehi\LaravelBalance\LaravelBalanceServiceProvider;
use Iamamirsalehi\LaravelBalance\Services\Balance\BalanceService;

class TestCase extends \Orchestra\Testbench\TestCase
{
  public function setUp(): void
  {
    parent::setUp();
    // additional setup
  }

  protected function getPackageProviders($app)
  {
    return [
        LaravelBalanceServiceProvider::class,
    ];
  }

  protected function getEnvironmentSetUp($app)
  {
      $app['config']->set('database.default', 'mysql');
      $app['config']->set('database.connections.mysql', [
          'driver'   => 'mysql',
          'database' => 'laravelBalanceTesting',
          'prefix'   => '',
          'username' => 'root',
          'password' => '',
          'host' => '127.0.0.1'
      ]);
  }

  public function getCoinAndUserId()
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

      return [ $user_id, $coin_id ];
  }

  public function deposit(int $price)
  {

    list($user_id, $coin_id) = $this->getCoinAndUserId();;

    $data = [
      'user_id' => $user_id,
      'coin_id' => $coin_id,
      'price'   => $price
    ];

    $deposit = BalanceService::deposit($data)->handle();

    return $deposit;
  }

  public function withdrawUnconfirmedYet(int $price)
  {
      list($user_id, $coin_id) = $this->getCoinAndUserId();;

      $data = [
        'user_id' => $user_id,
        'coin_id' => $coin_id,
        'price'   => $price
    ];

    $result = BalanceService::withdrawUnconfirmedYet($data)->handle();

    return $result;
  }
}