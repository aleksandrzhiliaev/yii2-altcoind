<?php
/**
 * Created by Yakovlev Vladimir yakovlev.vladimir@hotmail.com
 * Date: 21.08.2017 21:10
 *
 * @var $this yii\web\View
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

$this->title = 'Monero';
$this->params['breadcrumbs'][] = ['label' => 'Wallets', 'url' => ['/cryptowallet/default/index'] ];
$this->params['breadcrumbs'][] = $this->title;


?>

<div class="row">
    <div class="col-md-9">
        <?php $form = ActiveForm::begin() ?>

        <div class="form-group" >
            <?= Html::input('text', 'payment_id', $payment_id, ['class' => 'col-md-12 input-lg', 'placeholder' => 'Payment ID'])?>
        </div>

        <div class="form-group" style="padding-top: 60px;">
            <?= Html::submitButton('Check', ['class' => 'btn-u btn-u-lg btn btn-primary'])?>
        </div>
        <?php ActiveForm::end() ?>

        <?php if ($payment != null) :?>
            <?php if (isset($payment->result->payments)) :?>
                <div class="col-md-12">
                <table class="table">
                    <tr>
                        <td>Amount</td>
                        <td>Block height</td>
                        <td>Tx hash</td>
                        <td>Unlock time</td>
                    </tr>
                    <?php foreach ($payment->result->payments as $paymentRow) : ?>
                        <tr>
                            <td><?=  $paymentRow->amount / 1000000000000   ?></td>
                            <td><?= $paymentRow->block_height?></td>
                            <td><?= $paymentRow->tx_hash?></td>
                            <td><?= $paymentRow->unlock_time?></td>
                        </tr>
                    <?php endforeach;?>
                </table>
            <?php  elseif (isset($payment->error->message)) : ?>

            <?php else : ?>
                <pre><?php var_dump($payment)?></pre>
            <?php endif?>
            </div>
        <?php endif ?>
    </div>
    <div class="col-md-3">
        <?=$this->render('_side')?>
    </div>

</div>