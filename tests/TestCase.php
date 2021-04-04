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
    // perform environment setup
  }
}