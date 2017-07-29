<?php

namespace aleksandrzhiliaev\altcoind;

/**
 * This is the main module class for the Yii2-altcoind.
 *
 *
 *
 * @author Aleksandr Zhiliaev <sassoftinc@gmail.com>
 */

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'aleksandrzhiliaev\altcoind\controllers';

    public $wallets = [];

    public $allowedUsers;

    public function init()
    {
        parent::init();
    }
}
