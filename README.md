You can manage your user ``withdraw`` and ``deposit`` in your website with this package

## How can I install this package?
```
composer require iamamirsalehi/laravel-balance
```

after installation publish needed files:
```
php artisan vendor:publish --provider="Iamamirsalehi\LaravelBalance\LaravelBalanceServiceProvider"
```
and run the migrations 

```
php artisan migrate
```

### How to deposit for user?

```
use Iamamirsalehi\LaravelBalance\Services\Balance\BalanceService;

BalanceService::deposit([
    'user_id' => 1,
    'coin_id' => 1,
    'price' => 300000
]);
```
as you can see you have three parameters to pass, the second one is might be confusing, ``coin_id``