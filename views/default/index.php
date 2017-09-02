<?php

use aleksandrzhiliaev\altcoind\models\TransferForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $wallets array */
/* @var $transferForm TransferForm */


$this->title = Yii::t('app', 'Wallets');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="log-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (Yii::$app->session->hasFlash('newAddress')) { ?>
        <div class="alert alert-success">
            New address: <?= Yii::$app->session->getFlash('newAddress') ?>
        </div>
    <?php } ?>

    <?php if (Yii::$app->session->hasFlash('transfer')) { ?>
        <div class="alert alert-success">
            Transaction txid: <?= Yii::$app->session->getFlash('transfer') ?>
        </div>
    <?php } ?>

    <table class="table">
        <tr class="header">
            <td>Wallet</td>
            <td>Balance</td>
            <td>Status</td>
            <td>

            </td>
        </tr>

        <?php foreach ($wallets as $wallet) { ?>
            <tr>
                <td><?= $wallet['name'] ?></td>
                <td><?= $wallet['info']['balance'] ?> </td>
                <td>
                    <?php
                    if ($wallet['info']['balance'] >= 0) {
                        echo '<strong>Working...</strong>';
                    } else {
                        echo 'No connection';
                    }
                    ?>
                </td>
                <td>
                    <?php if ($wallet['info']['balance'] >= 0) { ?>
                        <a href="<?= Url::to(['info', 'currency' => $wallet['name']]) ?>"><button type="button" class="btn btn-info">Info</button></a>
                        <a href="<?= Url::to(['addresses', 'currency' => $wallet['name']]) ?>"><button type="button" class="btn btn-warning">Show addresses</button></a>
                        <a href="<?= Url::to(['newaddress', 'currency' => $wallet['name']]) ?>"><button type="button" class="btn btn-success">Generate new address</button></a>
                    <?php } ?>
                </td>
            </tr>

        <?php } ?>

    </table>

    <h1>Transfer</h1>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($transferForm,
        'address')->textInput(['placeholder' => 'Enter address here, for example: 1JFAxtnU3HVA9btMVDjBuAhC61kgQexP7k']) ?>

    <?= $form->field($transferForm,
        'amount')->textInput(['placeholder' => 'Enter transfer amount here, please pay attention on blockchain comission']) ?>

    <?= $form->field($transferForm, 'currency')->dropDownList([
        'bitcoin' => 'BTC',
        'litecoin' => 'LTC',
        'ethereum' => 'ETH',
        'monero' => 'XMR',
    ]) ?>


    <div class="form-group">
        <?= Html::submitButton('Transfer', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
