<?php
use app\components\PageHeader;
use app\components\Utility;
use yii\helpers\Html;

$this->title = Yii::t('invoice','Invoice Detail');

?>
<?= PageHeader::widget([
    'title' => $this->title,
    'icon' => 'fa-dollar',
]);
?>
<div class="bs-example bs-example-tabs">
    <?= $this->render('_view_tab_links', [
        'tab' => '#contract_revision',
        'access' => $access,
        'invoiceModel' => $invoiceModel,
        'peid' => $peid,
        'todoExists' => $todoExists,
    ]); ?>
    <!--begin-task-->
    <div id="myTabContent" class="tab-content">
        <div class="tab-pane fade active in">
            <div class="row">
                
                <div style="width: 100%;padding:5px 0px 5px 0px;background: #ccf;">
                    <div style="padding:5px;background-color: #e4e9eb;font-size: 12px;">
                    <table>
                        <tr>
                            <td><?= Yii::t('contract', 'Subcontractor') ?> </td><td><i class="badge badge-orange"><?= Html::encode($invoiceModel->contract->subContractor->PersonTitle); ?></i>&nbsp;|&nbsp;</td>
                            <td><?= Yii::t('contract', 'Contract No') ?></td><td><i class="badge badge-purple"><?= Html::encode($invoiceModel->contract->ContractNo); ?></i>&nbsp;|&nbsp;</td>
                            <td><?= Yii::t('invoice', 'Invoice Number') ?></td><td><i class="badge badge-brown"><?= Html::encode($invoiceModel->Title); ?> </i>&nbsp;|&nbsp;</td>
                            <td><?= Yii::t('invoice', 'Invoice Type') ?></td><td><i class="badge badge-info"><?= Html::encode($invoiceModel->type->InvoiceType); ?> </i> </td>
                        </tr>
                    </table>
                    </div>
                </div>


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
//                'label' => 'Revision Number',
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
                'attribute' => 'contract.ContractDateHijri',
//                'label' => Yii::t('contract', 'Start Date'),
            ],
            [
                'attribute' => 'RevisionDate',
//                'label' => 'Issue Date',
                'value' => 'RevisionDateHijri',
            ],
            [
                'attribute' => 'FinishDate',
//                'label' => 'Finish Date',
                'value' => 'FinishDateHijri',
            ],
            [
                'attribute' => 'Duration',
                'value' => function($model) {
                    if (isset($model->durationUnit)) {
                        $dUnit = $model->durationUnit->DurationUnit;
                    } else {
                        $dUnit = '';
                    }
                    return $model->Duration . ' ' . Html::encode($dUnit);
                },
            ],
            [
                'attribute' => 'Price1',
                'label' => Yii::t('invoice','Price'),
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
                //'label' => 'Adjustment Start Date',
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
                //'label' => 'Adjustment Finish Date',
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
                'label' => Yii::t('contract','Adjustment Price'),
                'format' => 'raw',
                'filter' => false,
                'value' => function($model) {
                    if ($model->Price2Unit > 0) {
                        $curr2 = $model->currency2->CurrencyTitle;
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
        </div>
    </div>
</div>
