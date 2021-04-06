<?php

namespace Iamamirsalehi\LaravelBalance\Tests;

use Iamamirsalehi\LaravelBalance\LaravelBalanceServiceProvider;

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
          'host' => '127.0.0.1'
      ]);
  }
}