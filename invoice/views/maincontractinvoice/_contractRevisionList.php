<?php

use yii\helpers\Html;
use app\components\Utility;
?>
<div style="width: 100%;padding:5px 0px 5px 0px;background: #ccf;">
    <div style="padding:5px;background-color: #e4e9eb;font-size: 12px;">
        Sub Contractor <i class="badge badge-orange"><?= Html::encode($invoiceModel->contract->subContractor->PersonTitle); ?></i> |
        Contract Number <i class="badge badge-purple"><?= Html::encode($invoiceModel->contract->ContractNo); ?></i> |
        Invoice Number <i class="badge badge-brown"><?= Html::encode($invoiceModel->Title); ?> </i> |
        Invoice Type <i class="badge badge-info"><?= Html::encode($invoiceModel->type->InvoiceType); ?> </i> 
    </div>
</div>

<div class="col-lg-12 col-md-12">
    <?=
    \kartik\grid\GridView::widget([
        'id' => 'contract-rider-grid',
        'options' => [
            'style' => 'font-family:tahoma; font-size:10px;',
        ],
        'dataProvider' => $contractRevisionDataProvider,
        'filterModel' => $contractRevisionModel,
        'pjax' => true,
        'pjaxSettings' => [
            'options' => [
                'id' => 'contract-rider-grid-pjax',
            ]
        ],
        'columns' => [
            [
                'class' => \kartik\grid\ExpandRowColumn::className(),
                'value' => function($url, $model, $key) {
            return \kartik\grid\GridView::ROW_COLLAPSED;
        },
                'detailUrl' => Yii::$app->urlManager->createAbsoluteUrl(['contract/rider/view-only-rider']),
                'headerOptions' => ['class' => 'kartik-sheet-style'],
                'expandOneOnly' => true,
                'expandIcon' => '<span class="fa-plus"></span>',
                'collapseIcon' => '<span class="fa-minus"></span>',
            ],
            [
                'attribute' => 'RevisionNumber',
                'label' => 'Revision Number',
                'options' => [
                    'width' => '100 px',
                ],
            ],
            [
                'attribute' => 'revisionType',
                'value' => 'type.ContractRevisionType',
                'options' => [
                    'width' => '110 px',
                ],
            ],
            [
                'attribute' => 'RevisionDate',
                'label' => 'Issue Date',
                'value' => 'RevisionDateHijri',
            ],
            [
                'attribute' => 'FinishDate',
                'label' => 'Finish Date',
                'value' => 'FinishDateHijri',
            ],
            [
                'attribute' => 'Duration',
                'value' => function($model) {
            if (isset($model->durationUnit)) {
                $dUnit = Html::encode($model->durationUnit->DurationUnit);
            } else {
                $dUnit = '';
            }
            return $model->Duration . ' ' . $dUnit;
        },
            ],
            [
                'attribute' => 'Price1',
                'label' => 'Price',
                'format' => 'raw',
                'value' => function($model) {
            return '<div class="row">'
                    . '<div class="col-lg-12 col-md-12">'
                    . Yii::$app->utility->moneyFormat($model->Price1) . ' ' . Html::encode($model->currency1->CurrencyTitle)
                    . '</div>'
                    . '<div class="col-lg-12 col-md-12">'
                    . ((isset($model->Price2) && isset($model->currency2)) ? Yii::$app->utility->moneyFormat($model->Price2) . ' ' . Html::encode($model->currency2->CurrencyTitle) : '')
                    . '</div>'
                    . '</div>';
        }
            ],
            [
                'attribute' => 'AdjustmentStartDateHijri',
                'label' => 'Adjustment Start Date',
                'format' => 'raw',
                'filter' => false,
                'value' => function($data) {
            return '<table class="inner-table">'
                    . '<tr><td width=\'200\'>'
                    . $data["AdjustmentStartDateHijri"]
                    . '<tr class="top-border"><td >'
                    . $data["AdjustmentStartDate"]
                    . '</table>';
        },
                'options' => [
                    'width' => '100 px',
                ],
            ],
            [
                'attribute' => 'AdjustmentFinishDateHijri',
                'label' => 'Adjustment Finish Date',
                'format' => 'raw',
                'filter' => false,
                'value' => function($data) {
            return '<table class="inner-table">'
                    . '<tr><td width=\'200\'>'
                    . $data["AdjustmentFinishDateHijri"]
                    . '<tr class="top-border"><td >'
                    . $data["AdjustmentFinishDate"]
                    . '</table>';
        },
                'options' => [
                    'width' => '100 px',
                ],
            ],
            [
                'attribute' => 'AdjustmentPrice1',
                'label' => 'Adjustment Price',
                'format' => 'raw',
                'filter' => false,
                'value' => function($model) {
            if ($model->Price2Unit > 0) {
                $curr2 = Html::encode($model->currency2->CurrencyTitle);
            } else {
                $curr2 = '';
            }
            return '<table class="inner-table">'
                    . '<tr><td width=\'200\'>'
                    . Utility::moneyFormat($model->AdjustmentPrice1)
                    . '</td><td class="label-silver">' . Html::encode($model->currency1->CurrencyTitle) . '</td></tr>'
                    . '<tr class="top-border"><td >'
                    . Utility::moneyFormat($model->AdjustmentPrice2)
                    . '</td><td class="label-silver">' . $curr2 . '</td></tr>'
                    . '</table>';
        },
                'options' => [
                    'width' => '120 px',
                ],
            ],
        ]
    ]);
    ?>
</div>



