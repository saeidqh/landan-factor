<?php

use app\modules\invoice\assets\InvoiceListAsset;
use app\components\PageHeader;
use app\components\GridViewBox;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\components\Utility;
use yii\helpers\Html;

$this->title = Yii::t('invoice', 'Invoice List');

InvoiceListAsset::register($this);
$this->params['breadcrumbs'] = [
    'lable' => 'Invoice'
];
?>


<?=
PageHeader::widget([
    'title' => $this->title,
    'icon' => 'fa-dollar',
]);
?>
<?php Pjax::begin(); ?>    
<?php GridViewBox::begin(); ?>
<?php
$canExportInvoice = app\access\AccessControl::can('invoice/export/invoice-list');
if ($canExportInvoice) {
    ?>
    <div class="row">
        <div class="col-md-4 pull-right text-right mb5 ">
            <?=
            yii\helpers\Html::a('<i class="im-file-excel"></i>&nbsp;&nbsp;Excel', '#', [
                'data-pjax' => '0',
                'class' => "btn btn-sm btn-default btn-export-invoice-list",
                'export-url' => Yii::$app->urlManager->createAbsoluteUrl(['invoice/export/invoice-list']),
                'onclick' => 'return exportExcelInvoiceList(this)',
            ]);
            ?>

        </div>  
    </div>
    <?php
}
?>
<div class="row">

    <?=
    GridView::widget([
        'id' => 'invoice-list-grid',
        'filterModel' => $invoiceSearchModel,
        'dataProvider' => $invoiceDataProvider,
        'columns' => [
            [
                'attribute' => 'contractNo',
                'label' => Yii::t('contract', 'Contract No'),
                'value' => 'contract.ContractNo',
            ],
            [
                'attribute' => 'contractSubject',
                'label' => Yii::t('contract', 'Contract Subject'),
                'value' => 'contract.ContractSubject',
            ],
            [
                'attribute' => 'subContractorTitle',
                'label' => Yii::t('contract', 'Subcontractor'),
                'value' => 'contract.subContractor.PersonTitle',
            ],
            [
                'attribute' => 'clientTitle',
                'label' => Yii::t('contract', 'Client'),
                'value' => 'contract.signer.PersonTitle',
            ],
            [
                'attribute' => 'contractTypeTitle',
                'label' => Yii::t('contract', 'Contract Type'),
                'value' => 'contract.type.ContractTypeTitle',
            ],
            [
                'attribute' => 'Title',
            ],
            'CutOffDateHijri',
            [
                'attribute' => 'invoiceTypeTitle',
                'value' => 'type.InvoiceType',
            ],
            [
                'attribute' => 'lastActionTitle',
                'value' => function ($model) {
                    return Html::encode($model->processEntity->lastAction->ActionTitle2);
                },
            ],
            [
                'attribute' => 'lastActionDateHijri',
                'value' => function ($model) {
                    return $model->processEntity->LastActionDateHijri;
                }
            ],
            [
                'attribute' => 'hasDocument',
                'label' => Yii::t('invoice', 'Invoice Document'),
                'options' => [
                    'width' => '70px'
                ],
                'filter' => [0 => 'ندارد', 1 => 'دارد'],
                'format' => 'raw',
                'value' => function ($model) {
                    $hasDocument = $model->hasDocument;
                    return "<span class='" . (($hasDocument == 1) ? 'text-success' : 'text-danger') . "'><strong>" . (($hasDocument == 1) ? 'دارد' : 'ندارد') . "</strong></span>";
                }
            ],
            [
                'attribute' => 'subProjectList',
                'label' => Yii::t('contract', 'Sub Project List'),
                'value' => 'contract.subProjectList'
            ],
            [
                'attribute' => 'approved',
                'format' => 'raw',
                'filter' => false,
                'value' => function ($data) {
                    return '<table class="inner-table">'
                    . '<tr><td width=\'200\'>'
                    . Utility::moneyFormat($data["Approved1"])
                    . '</td><td class="label-silver">' . $data["Currency1Title"] . '</td></tr>'
                    . '<tr class="top-border"><td >'
                    . Utility::moneyFormat($data["Approved2"])
                    . ' </td><td class="label-silver">' . $data["Currency2Title"] . '</td></tr>'
                    . '</table>';
                }
            ],
            [
                'class' => yii\grid\ActionColumn::className(),
                'header' => 'Actions',
                'template' => '{view}{action}{createform}',
                'options' => [
                    'width' => '100px;',
                ],
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return '<a title="View detail of Invoice" class="btn btn-xs" data-pjax="0" style="color:rgb(162, 41, 161)" href="'
                        . Yii::$app->urlManager->createUrl(["invoice/invoice/view", "peid" => $model->processEntity->ProcessEntityID])
                        . '"><i class="fa-search s14"></i></a>';
                    },
                    'createform' => function ($url, $model, $key) {
                        if ($model->processEntity->invoice->contract->SignerPersonIDRef == 387) {
                            ($model->processEntity->ProcessIDRef == 57 || $model->processEntity->ProcessIDRef == 58) ?
                                            \kartik\helpers\Html::a('<i class="glyphicon glyphicon-equalizer s16"></i>', "#", [
                                                'onclick' => "return showModalCreateForm(this); ",
                                                'data-pjax' => "0",
                                                'target' => "_blank",
                                                'data-original-title' => 'Create Form',
                                                'ajax_url' => Yii::$app->urlManager->createAbsoluteUrl(["invoice/invoice/createform", "peid" => $model->processEntity->ProcessEntityID, "type" => "na"]),
                                                'data-toggle' => 'tooltip',
                                            ]) : '';
                        } elseif ($model->processEntity->invoice->contract->SignerPersonIDRef == 258) {
                            return ($model->processEntity->ProcessIDRef == 5) ?
                                    kartik\helpers\Html::a('<i class="glyphicon glyphicon-equalizer s16"></i>', "#", [
                                        'onclick' => "return showModalCreateForm(this); ",
                                        'data-pjax' => "0",
                                        'target' => "_blank",
                                        'data-original-title' => 'Create Form',
                                        'ajax_url' => Yii::$app->urlManager->createAbsoluteUrl(["workflow/letter/create-form", "peid" => $model->processEntity->ProcessEntityID, "lid" => "19", "type" => "na"]),
                                        'data-toggle' => 'tooltip',
                                    ]) : '';
                        } else {
                            return '';
                        }
//                                    return ($model->processEntity->invoice->contract->SignerPersonIDRef==387) ?
//                                    ($model->processEntity->ProcessIDRef==57 || $model->processEntity->ProcessIDRef==58)?
//                                            \kartik\helpers\Html::a('<i class="glyphicon glyphicon-equalizer s16"></i>', "#", [
//                                                'onclick' => "return showModalCreateForm(this); ",
//                                                'data-pjax' => "0",
//                                                'target' => "_blank",
//                                                'data-original-title' => 'Create Form',
//                                                'ajax_url' => Yii::$app->urlManager->createAbsoluteUrl(["invoice/invoice/createform", "peid" => $model->processEntity->ProcessEntityID, "type" => "na"]),
//                                                'data-toggle' => 'tooltip',
//                                            ]) : '' : '';
                    },
                    'action' => function ($url, $model, $key) {
                        return isset($model->processEntity) ? \kartik\helpers\Html::a('<i class="en-flow-branch s16"></i>', Yii::$app->urlManager->createAbsoluteUrl(["workflow/workflow/graph", 'peid' => $model->processEntity->ProcessEntityID]), [
                            'data-original-title' => 'Action Diagram',
                            'class' => 'btn btn-xs tip',
                            'data-pjax' => '0',
                            'style' => 'padding:1px 2px;color:#46ADFF;',
                            'target' => '_blank'
                        ]) : '';
                    },
                ]
            ],
        ],
    ]);
    ?>


</div>

<?php GridViewBox::end(); ?> 

<div class="total-box">
    <?php
    echo $this->render('_total', [
        'totalInvoiceDataProvider' => $totalInvoiceDataProvider,
    ]);
    ?>
</div>


<?php Pjax::end(); ?>
<script>
    function exportExcelInvoiceList(obj) {
        var filter = $('#invoice-list-grid input').serialize();
        var exportUrl = $(obj).attr('export-url') + '&' + filter;
        $('body').append('<iframe src="' + exportUrl + '" style="display:none" ></iframe>')
    }

    function showModalCreateForm(obj) {
        var url = $(obj).attr('ajax_url');
        var pwin = window.open(url, "_blank", "width=700,height=660,scrollbars=yes,resizable=no");
        if (window.focus)
            pwin.focus();
        return false;
    }

</script>

