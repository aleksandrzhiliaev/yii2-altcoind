<?php
/**
 * Author yakovlev.vladimir@hotmail.com.
 * Date: 23.08.2017 13:29
 *
 * @var $this yii\web\View
 * @var $transfers yii\data\ArrayDataProvider
 */


use yii\grid\GridView;

$this->title = 'Monero - Transfers';
$this->params['breadcrumbs'][] = ['label' => 'Wallets', 'url' => ['/cryptowallet/default/index'] ];
$this->params['breadcrumbs'][] = $this->title;

$this->registerCss('
body .detailModal {
    /* new custom width  
    width: 750px;*/

}
')
?>
<div class="row">
    <div class="col-md-9">
        <?= GridView::widget([
            'dataProvider' => $transfers,
            'columns' => [
                'height',
                'type',
                'timestamp:datetime',
                [
                    'format' => 'text',
                    'label' => 'Amount',
                    'value' => function ($model) {
                        return $model->amount / 1000000000000;
                    }
                ],
//                'txid',
//                'payment_id',
//                [
//                    'format' => 'text',
//                    'label' => 'Fee',
//                    'value' => function ($model) {
//                        return $model->fee / 1000000000000;
//                    }
//                ],
                [
                    'format' => 'raw',
                    'label' => 'Info',
                    'value' => function($model) {
                        return Yii::$app->view->render('_transfer', ['model' => $model]);

                    }
                ]
            ],
            'rowOptions' => function ($model, $index, $widget, $grid){
                $class = [
                    'in' => 'success',
                    'out' => 'info',
                    'failed' => 'danger',
                    'pending' => 'warning'
                ];

                return ['class' => $class[$model->type]];
            },
        ])?>
    </div>
    <div class="col-md-3">
        <?=$this->render('_side')?>
    </div>
</div>