<?php

use kartik\grid\GridView;
use app\components\Utility;
use yii\widgets\Pjax;
use yii\helpers\Html;
use yii\bootstrap\Modal;
$canMaincontractInvoiceAmountEdit = app\access\AccessControl::can('invoice/maincontractamount/update', ['peid' => $invoiceModel->processEntity->ProcessEntityID]);
$canMaincontractInvoiceAmountDelete = app\access\AccessControl::can('invoice/maincontractamount/delete', ['peid' => $invoiceModel->processEntity->ProcessEntityID]);
?>
<div style="width: 100%;padding:5px 0px 5px 0px;background: #ccf;">
    <div style="padding:5px;background-color: #e4e9eb;font-size: 12px;">
        Sub Contractor <i class="badge badge-orange"><?= Html::encode($invoiceModel->contract->subContractor->PersonTitle); ?></i> |
        Contract Number <i class="badge badge-purple"><?= Html::encode($invoiceModel->contract->ContractNo); ?></i> |
        Invoice Number <i class="badge badge-brown"><?= Html::encode($invoiceModel->Title); ?> </i> |
        Invoice Type <i class="badge badge-info"><?= Html::encode($invoiceModel->type->InvoiceType); ?> </i> 
    </div>
</div>
<div>
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="pull-right">
                <?php $canMaincontractInvoiceCreate  =app\access\AccessControl::can('invoice/maincontractamount/create', ['peid' => $invoiceModel->processEntity->ProcessEntityID]); ?>
                <?php if ($canMaincontractInvoiceCreate) { ?>
                    <button class="btn btn-sm btn-success" data-url="<?= Yii::$app->urlManager->createAbsoluteUrl(['invoice/maincontractamount/create', 'peid' => $invoiceModel->processEntity->ProcessEntityID]); ?>" onclick="showInvoiceAmountModel(this)"><i class="fa-plus"></i> New Amount</button>
                <?php } ?>                
            </div>     
            <div class="col-lg-12 col-md-12">
                <div id="new-incoieamount-container">
                    <?php Pjax::begin(['id' => 'invoiceAmountForm-pjax']); ?>

                    <?php Pjax::end(); ?>
                </div>
            </div> 

        </div>
    </div>

</div>
<?php $f = Pjax::begin(['id' => 'invoiceAmountPjax']); ?>
<?=
$grd = GridView::widget([
    'id' => 'tbl-invoice-grid2',
    'rowOptions' => function ($model, $key, $index, $grid) {
        $id = $key;
        $class = $index % 2 ? 'odd' : 'even';
        return array('index' => $index, 'class' => $class, 'id' => 'row-' . $id);
    },
    'filterModel' => $invoice_amount_model,
    'dataProvider' => $dataProvider,
    'resizableColumns' => true,
    'columns' => [
        [
            'label' => 'Cost Code',
            'format' => 'raw',
            'attribute' => 'costcode',
            'options' => array('width' => '200px'),
            'value' => function($model) {
                return Utility::generateCostCodeLink($model->costCode->NodeID, $model->costCode->NodeFullCode);
            }
        ],
        [
            'attribute' => 'amountType',
            'label' => 'Amount Type',
            'value' => 'type.InvoiceAmountType',
            'format' => 'raw',
            'options' => array('width' => '70px'),
        ],
        [
            'label' => 'Currency',
            'format' => 'raw',
            'attribute' => 'currency',
            'value' => function($model) {
                return '<table class="inner-table">'
                        . '<tr><td width=\'100\'>'
                        . Html::encode($model->currency1->CurrencyTitle)
                        . '<tr class="top-border"><td >'
                        . (($model->Currency2 > 0) ? Html::encode($model->currency2->CurrencyTitle) : '&nbsp;')
                        . '</table>';
            },
            'options' => [
                'width' => '20px'
            ]
        ],
        [
            'format' => 'raw',
            'attribute' => 'AmountRequested1',
            'label' => 'Requested',
            'value' => function($data) {
                return '<table class="inner-table">'
                        . '<tr><td width=\'200\'>'
                        . Utility::moneyFormat($data["AmountRequested1"], ($data->currency1->CurrencyID != 1) ? 2 : null)
                        . '<tr class="top-border"><td >'
                        . Utility::moneyFormat($data["AmountRequested2"], (isset($data->currency2) && $data->currency2->CurrencyID != 1) ? 2 : null)
                        . '</table>';
            }
        ],
        [
            'label' => 'Approved',
            'format' => 'raw',
            'attribute' => 'AmountApproved1',
            'value' => function($data) {
                return '<table class="inner-table">'
                        . '<tr><td width=\'200\'>'
                        . Utility::moneyFormat($data->AmountApproved1, ($data->currency1->CurrencyID != 1) ? 2 : null)
                        . '<tr class="top-border"><td >'
                        . Utility::moneyFormat($data->AmountApproved2, (isset($data->currency2) && $data->currency2->CurrencyID != 1) ? 2 : null)
                        . '</table>';
            }
        ],
        [
            'label' => 'Income',
            'format' => 'raw',
            'attribute' => 'Income1',
            'value' => function($data) {
                return '<table class="inner-table">'
                        . '<tr><td width=\'200\'>'
                        . Utility::moneyFormat($data["Income1"], ($data->currency1->CurrencyID != 1) ? 2 : null)
                        . '<tr class="top-border"><td >'
                        . Utility::moneyFormat($data["Income2"], (isset($data->currency2) && $data->currency2->CurrencyID != 1) ? 2 : null)
                        . '</table>';
            }
        ],
        [
            'label' => 'Owner ContractNo',
            'format' => 'raw',
            'attribute' => 'ownerContractNo',
            'value' => function($model) {
                if (isset($model->owner)) {
                    return Html::a($model->owner->ContractNo, '#', [
                                'data-original-title' => $model->owner->subContractor->PersonTitle,
                                'data-toggle' => 'tooltip'
                    ]);
                }
            },
            'options' => [
                'width' => '160px',
            ],
        ],
        [
            'label' => 'Last level',
            'value' => function($model) {
                return Html::encode($model->costCode->NodeText);
            }
        ],
        [
            'class' => yii\grid\ActionColumn::className(),
            'header' => 'actions',
            'template' => '{edit}&nbsp{delete}',
            'buttons' => [
                'edit' => function($url, $model, $key) use ($canMaincontractInvoiceAmountEdit) {
                    if ($canMaincontractInvoiceAmountEdit) {
                        return Html::a('<i class="im-pencil"></i>', '#', ['data-key' => $key, 'data-pjax' => '0', 'data-url' => Yii::$app->urlManager->createAbsoluteUrl(['invoice/maincontractamount/update','peid'=>$model->invoice->processEntity->ProcessEntityID ,'invamid' => $model['ID']]), 'onclick' => 'showInvoiceAmountModel(this)',]);
                    }
                },
                'delete' => function($url, $data, $key)use($canMaincontractInvoiceAmountDelete) {
                    if ($canMaincontractInvoiceAmountDelete) {
//                        if ($data['UserIDRef'] == Yii::$app->session->get('UserID') || $mode == 'wowf') {
                        //@TODO: در جاوا این دکمه باید بررسی گردد
                        return Html::a('&nbsp;<i class="br-trashcan"></i>', '#', [
                                    'data-toggle' => 'tooltip',
                                    'data-placement' => 'left',
                                    'title' => 'Click to delete Amount',
                                    'id' => 'delete-amount-' . $data['ID'],
                                    'onclick' => 'confirmBox("Are you sure to delete this item?!", "Delete!", "deleteInvoiceAmount(' . $data['ID'] . ')");',
                                    'delete-url' => Yii::$app->urlManager->createUrl(["invoice/maincontractamount/delete", 'peid' => $data->invoice->processEntity->ProcessEntityID, 'id' => $data['ID']]),
                        ]);
                    }
                }
            ]
        ]
    ],
]);
?>

<!--begin-task-->

<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="panel panel-brown toggle" style="margin:10px 0px 0px 0px;">
            <div class="panel-heading" >
                <i class="im-stack"></i>
                Periodic
            </div>
            <div class="panel-body" style="padding: 0px;">
                <table class="table table-striped table-hover non-responsive psa-grid table-bordered">
                    <thead>
                        <tr>
                            <th>Currency</th>
                            <th>Requested</th>
                            <th>Approved</th>
                            <th>Income</th>
                            <th>Penalty</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr data-key="1">
                            <td> <?= $invoiceModel->Currency1Title ?></td>
                            <td><?= Utility::moneyFormat($invoiceModel->requested1, ($invoiceModel->Currency1IDRef != 1) ? 2 : null) ?></td>
                            <td><?= Utility::moneyFormat($invoiceModel->approved1, ($invoiceModel->Currency1IDRef != 1) ? 2 : null) ?></td>
                            <td><?= Utility::moneyFormat($invoiceModel->income1, ($invoiceModel->Currency1IDRef != 1) ? 2 : null) ?></td>
                            <td><?= Utility::moneyFormat($invoiceModel->penalty1, ($invoiceModel->Currency1IDRef != 1) ? 2 : null) ?></td>
                        </tr>
                        <?php if ($invoiceModel->Currency2IDRef > 0) { ?>
                            <tr data-key="1">
                                <td> <?= $invoiceModel->Currency2Title ?></td>
                                <td><?= Utility::moneyFormat($invoiceModel->requested2, (isset($data->currency2) && $data->currency2->CurrencyID != 1) ? 2 : null) ?></td>
                                <td><?= Utility::moneyFormat($invoiceModel->approved2, (isset($data->currency2) && $data->currency2->CurrencyID != 1) ? 2 : null) ?></td>
                                <td><?= Utility::moneyFormat($invoiceModel->income2, (isset($data->currency2) && $data->currency2->CurrencyID != 1) ? 2 : null) ?></td>
                                <td><?= Utility::moneyFormat($invoiceModel->penalty2, (isset($data->currency2) && $data->currency2->CurrencyID != 1) ? 2 : null) ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>


            </div>
        </div>
    </div>
</div>
<!--end-task-->
<?php Pjax::end(); ?>

<?php
Modal::begin([
    'header' => 'Invoice Amount',
    'id' => 'invoice-amount-modal',
    'size' => Modal::SIZE_LARGE,
    'options' => [
        'tabindex' => false
    ]
]);
?>
<?php Pjax::begin(['id' => 'invoice-amount-form-pjax']); ?>
<?php
$js = "
    $('#invoice-amount-form-pjax').on('pjax:send', function(){
        ajaxProgressLoader('show', '#invoice-amount-form-pjax');
    });

    $('#invoice-amount-form-pjax').on('pjax:success', function(xhr){
        ajaxProgressLoader('hide', '#invoice-amount-form-pjax');
        if(xhr.currentTarget.innerHTML === '1')
        {
            $.pjax.reload({
               container:'#invoiceAmountPjax',
               timeout: false,

               push:false,
               replace:false,
           });
            setTimeout(function(){
                $.pjax.reload({
                    container:'#contract-cumulative-pjax-invoice-amount-total',
                    url:'".Yii::$app->urlManager->createAbsoluteUrl(['invoice/widget/contract-cumulative', 'cid' => $invoiceModel->contract->ContractID, 'amountStatus' => 'Approved Or Checking', 'wid' => 'invoice-amount-total', 'hdate' => $invoiceModel->CutOffDateHijri])."',
                    timeout: false,
                    push: false,
                    replace: false,
                });
            }, 3000);
            
           $('#invoice-amount-modal').modal('hide');
           showMsgBoxInBottomRight('data successfully save!!', 'success');
        }
    });
    $('#invoiceAmountPjax').on('pjax:send', function(){
        ajaxProgressLoader('show', '#invoiceAmountPjax');
    });
    $('#invoiceAmountPjax').on('pjax:complete', function(){
        ajaxProgressLoader('hide', '#invoiceAmountPjax');
    });
";
$this->registerJs($js, \yii\web\View::POS_READY);
?>
<?php Pjax::end(); ?>
<?php Modal::end(); ?>
<script>
    function showInvoiceAmountModel(obj) {
        ajaxProgressLoader('show', '.modal-content');
        var url = $(obj).attr('data-url');
        $.ajax({
            url: url,
            dataType: 'html'
        }).done(function (data) {
            $('#invoice-amount-form-pjax').html(data);
            $('#invoice-amount-modal').modal('show');
            ajaxProgressLoader('hide', '.modal-content');
        }).error(function () {
            showMsgBoxInBottomRight('Limited Access or error in connecting to server', 'error');
            return false;
        });
    }

    $('#invoiceAmountPjax').on('pjax:complete', function () {
//        $.ajax({
//            url: $('#amount-status-drop-down').attr('data-url'),
//            data: {
//                amountStatus: $('#amount-status-drop-down').val(),
//            }
//        }).done(function (data) {
//            $('#contract-cumulative-pjax').html(data);
//        }).error(function () {
//            return false;
//        });
    });
    function deleteInvoiceAmount(id) {
        var url = $('#delete-amount-' + id).attr('delete-url');
        $.ajax({
            url: url,
        }).done(function (data) {
            if (data.result == 1) {
                location.reload();
//                $.pjax.reload({
//                    container: '#contract-budget-grid-pjax',
//                    timeout: false,
//                    push: false,
//                    replace: false,
//                });
//                setTimeout("showMsgBoxInBottomRight('" + data.message + "', 'success')", 300);
            } else {
                setTimeout("showMsgBoxInBottomRight('" + data.message + "', 'error')", 300);
            }
        }).error(function () {
            showMsgBoxInBottomRight("Error on connecting to server.", "error");
            ajaxProgressLoader('hide', '.popup-box');
            return false;
        });
        return false;
    }
</script>

