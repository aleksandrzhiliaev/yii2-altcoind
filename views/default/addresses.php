<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $addresses array */
/* @var $keys array */
/* @var $currency string */


$this->title = Yii::t('app', 'Addresses');
$this->params['breadcrumbs'][] = $this->title;
$i = 0;
?>
<div class="log-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <table class="table">
        <tr class="header">
            <td>Address</td>
        </tr>

        <?php foreach ($addresses as $address) { ?>
            <tr>
                <td>
                    <?php if ($currency == 'bitcoin') { ?>
                        <a href="https://blockchain.info/address/<?= $address ?>" target="_blank"><?= $address ?></a>
                    <?php } ?>
                    <?php if ($currency == 'litecoin') { ?>
                        <a href="https://chainz.cryptoid.info/ltc/address.dws?<?= $address ?>"
                           target="_blank"><?= $address ?></a>
                    <?php } ?>
                    => <?= $keys[$i] ?>
                </td>

            </tr>
            <?php $i++;
        } ?>

    </table>
</div>
