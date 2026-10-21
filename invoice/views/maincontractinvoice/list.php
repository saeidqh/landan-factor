<?php

use app\modules\invoice\assets\InvoiceListAsset;
use app\components\PageHeader;
use app\components\GridViewBox;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\components\Utility;

$this->title = "Invoice List";

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
                'attribute' => 'contractSubject',
                'label' => 'Contract Subject',
                'value' => 'contract.ContractSubject',
            ],
            [
                'attribute' => 'contractNo',
                'label' => 'Contract No',
                'value' => 'contract.ContractNo',
            ],
            [
                'attribute' => 'subContractorTitle',
                'label' => 'Subcontractor',
                'value' => 'contract.subContractor.PersonTitle',
            ],
            [
                'attribute' => 'clientTitle',
                'label' => 'Client',
                'value' => 'contract.signer.PersonTitle',
            ],
            [
                'attribute' => 'contractTypeTitle',
                'label' => 'Contract Type',
                'value' => 'contract.type.ContractTypeTitle',
            ],
            [
                'attribute' => 'Title',
                'label' => 'Invoice Number',
                'value' => 'Title',
            ],
            'CutOffDateHijri',
            [
                'attribute' => 'invoiceTypeTitle',
                'label' => 'Invoice Type',
                'value' => 'type.InvoiceType',
            ],
//            [
//                'attribute' => 'lastActionTitle',
//                'label' => 'Last Action Title',
//                'value' => function($model) {
//                    return $model->processEntity->lastAction->ActionTitle2;
//                },
//            ],
//            [
//                'attribute' => 'lastActionDateHijri',
//                'label' => 'Last action date',
//                'value' => function($model){
//                    return $model->processEntity->LastActionDateHijri;
//                }
//            ],
            [
                'attribute' => 'subProjectList',
                'label' => 'sub project list',
                'value' => 'contract.subProjectList'
            ],
            [
                'attribute' => 'approved',
                'label' => 'Approved',
                'format' => 'raw',
                'filter' => false,
                'value' => function($data) {
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
                'attribute' => '',
                'format' => 'raw',
                'filter' => false,
                'value' => function($model) {
                    return '<a title="View detail of Invoice" class="btn btn-xs" data-pjax="0" style="color:rgb(162, 41, 161)" href="'
                            . Yii::$app->urlManager->createUrl(["invoice/maincontractinvoice/view", "peid" => $model->processEntity->ProcessEntityID])
                            . '"><i class="fa-search s14"></i></a>';
                    
                },
                'options' => [
                    'width' => '80px',
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
</script>

