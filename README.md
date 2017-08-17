# yii2-altcoind
[![Total Downloads](https://poser.pugx.org/aleksandrzhiliaev/yii2-altcoind/downloads)](https://packagist.org/packages/aleksandrzhiliaev/yii2-altcoind)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/881ec73e286449b1a419db243510a648)](https://www.codacy.com/app/sassoftinc/yii2-altcoind?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=aleksandrzhiliaev/yii2-altcoind&amp;utm_campaign=Badge_Grade)

Yii2 Alticoind module+extension. Supports connection between yii2 and coin daemons (Bitcoind, Litecoind etc..)

You can use only altcoind components in your application to make calls to your bitcoind,litecoind servers or also use module which provides you web interface (show balance, make new transfer, show generated addresses, generate new address, show private keys of your addresses).


Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist aleksandrzhiliaev/yii2-altcoind
```

or add

```
"aleksandrzhiliaev/yii2-altcoind": "~0.0.1"
```

to the require section of your `composer.json` file.


Basic Usage
-----------

After installation you need to define your altcoind components in your yii container:
```php
...
'components' => [
        'bitcoin' => [
            'class' => 'aleksandrzhiliaev\altcoind\components\Altcoin',
            'username' => 'rpc_username',
            'password' => 'rpc_password',
            'host' => 'rpc_host',
            'port' => 'rpc_port',
        ],
        'litecoin' => [
            'class' => 'aleksandrzhiliaev\altcoind\components\Altcoin',
            'username' => 'rpc_username',
            'password' => 'rpc_password',
            'host' => 'rpc_host',
            'port' => 'rpc_port',
        ],
        ...
]
...
```

You can install other clients which provide you RPC interface.

Now you can make transfers, generate new addresses and do other stuff like this:
```php
$txid = Yii::$app->bitcoin->send('address', 0.0001);

$address = Yii::$app->bitcoin->generateAddress('account_name');

$validInfo = Yii::$app->bitcoin->validateAddress('address_to_validate');

$walletInfo = Yii::$app->bitcoin->getInfo();

$transactions = Yii::$app->bitcoin->showTransactions('account_name');

$generatedAddresses = Yii::$app->bitcoin->showAddresses('account_name');

$addressPrivateKey = Yii::$app->bitcoin->dumpPrivateKey('address');
```

If something goes wrong these methods will throw standard `ErrorException`.


To use web interface you need to add module in `modules` section:

```php
'modules' => [
        ...
        'altcoind' => [
            'class' => 'aleksandrzhiliaev\altcoind\Module',
            'layout' => '@app/views/layouts/admin',
            'allowedUsers' => ['admin'],
            'wallets' => ['bitcoin', 'litecoin'],
        ],
        ...
]
...
```

You need to define user logins, who have rights to view that pages.
Also you need to add a list of wallets which will be used in web interface.
