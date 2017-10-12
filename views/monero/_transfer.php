<?php
/**
 * Author yakovlev.vladimir@hotmail.com.
 * Date: 23.08.2017 14:14
 *
 *
 */
use yii\widgets\DetailView;

?>

<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#detailModal-<?=md5($model->txid)?>">
    Detail
</button>

<!-- Modal -->
<div class="modal fade detailModal" id="detailModal-<?=md5($model->txid)?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?=$model->height?></h4>
            </div>
            <div class="modal-body">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'type',
                        'txid',
                        'payment_id',
                        'height',
                        [
                            'label' => 'timestamp',
                            'value' => function ($model) {
                                return \Yii::$app->formatter->asDatetime($model->timestamp, "php:d-m-Y H:i:s");
                            }
                        ],
                        [
                            'label' => 'amount',
                            'value' => function ($model) {
                                return $model->amount / 1000000000000;
                            }
                        ],
                        'fee',
                        'note',
                    ]
                ])?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>