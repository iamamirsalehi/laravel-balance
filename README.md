You can manage your user ``withdraw`` and ``deposit`` in your website with this package

## How can I install this package?
```shell script
composer require iamamirsalehi/laravel-balance
```

after installation publish needed files:
```shell script
php artisan vendor:publish --provider="Iamamirsalehi\LaravelBalance\LaravelBalanceServiceProvider"
```
and run the migrations 

```shell script
php artisan migrate
```

## note: all methods are called statically

### How to deposit for user?

```php
use Iamamirsalehi\LaravelBalance\Services\Balance\BalanceService;

BalanceService::deposit([
    'user_id' => 1,
    'coin_id' => 1,
    'price' => 300000
]);
```
as you can see you have three parameters to pass, the second one is might be confusing, ``coin_id`` is actually the coin 
to deposit and withdraw.

### How to withdraw?
When actually user wants to withdraw, the user must ask his withdraw then admin can confirm
or reject the request.
So when user wants to have a withdraw, user should send the request like below with ``withdrawUnconfirmedyet`` method.
```php
use Iamamirsalehi\LaravelBalance\Services\Balance\BalanceService;

BalanceService::withdrawUnconfirmedyet([
     'user_id' => 1,
     'coin_id' => 1,
     'price' => 500000
])->handle();
```

### How to confirm the requested withdraw?
You can confirm withdraw with ``withdrawconfirmed`` method
```php
use Iamamirsalehi\LaravelBalance\Services\Balance\BalanceService;

BalanceService::withdrawconfirmed([
    'user_id' => 1,
    'coin_id' => 1,
    'withdraw_id' => 1
])->handle();
```

### What if we wanted to reject the requested withdraw?
Easily with ``rejectedWithdraw`` method

```php
use Iamamirsalehi\LaravelBalance\Services\Balance\BalanceService;

BalanceService::rejectedWithdraw([
     'user_id' => 1,
     'coin_id' => 1,
     'withdraw_id' => 1
])->handle();
```

### What's actually the formula for every action?

``Coin code``    **The coin that is selected to done the action** <br>
``Action code``   **Type of transaction** <br>
``Action 1 (C)``  **Has direct effect on Asset (D)** <br>
``Asset (D)`` **The amount of money that user has, the formula to calculate is *D(n)=C(n)+D(n-1)*** <br>
``Action 2 (E)`` **Has direct effect on Liability (F)** <br>
``Liability (F)`` **The amount of user blocked money, the formula to calculate is *F(n)=E(n)+F(n-1)*** <br>
``Free balance`` **The free balance that user can deposit and withdraw, the formula to calculate *G(n)=D(n)-F(n)***

| Coin code (A)    | Action code (B)  | Action 1 (C)     | Asset (D)        | Action 2 (E)    | Liability (F)    | Free balance (G) |
| ------------- | ------------- | ------------- | ------------- | ------------- | ------------- | ------------- |
| 7(TRX)  | Deposit                   | 10 | 10  |  0 | 0 | 10  |
| 7(TRX)  | Withdraw unconfirmed yet  | 0  | 10  |  5 | 5 | 5  |
| 7(TRX)  | Withdraw confirmed        | -5 | 5   | -5 | 0 | 5  |
| 7(TRX)  | Withdraw unconfirmed yet  | 0  | 5   |  2 | 2 | 3  |
| 7(TRX)  | Rejected withdraw         | 0  | 5   | -2 | 0 | 5  |
| 7(TRX)  | pend order                | 0  | 5   |  2 | 2 | 3  |
| 7(TRX)  | execute order             | -2 | 3   | -2 | 0 | 3  |
| 7(TRX)  | pend order                | 0  | 3   |  2 | 2 | 1  |
| 7(TRX)  | cancel order              | 0  | 3   | -2 | 0 | 3  |

### What happen when we call the actions?

| Action code  | Action 1  | Action 2  |
| ------------- | ------------- | ------------- |
| Deposit                   | (+x)  | null | 
| Withdraw unconfirmed yet  | null  | (+x) | 
| Withdraw confirmed        | (-x)  | (-x) | 
| Rejected withdraw         | null  | (-x) |  
| pend order                | null  | (+x) | 
| execute order             | (-x)  | (-x) |  
| cancel order              | null  | (-x) | 
