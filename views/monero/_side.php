<?php
/**
 * Author yakovlev.vladimir@hotmail.com.
 * Date: 23.08.2017 13:02
 *
 *
 */
use yii\widgets\Menu;

echo Menu::widget(['items' => [
    ['label' => 'Payment', 'url' => ['payment']],
    ['label' => 'Transfers', 'url' => ['transfers']]
]
]);