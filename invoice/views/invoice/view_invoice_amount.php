<?php
use app\components\PageHeader;
use yii\helpers\Url;
use kartik\grid\GridView;
use app\components\Utility;
use app\assets\ThemeAsset;
use yii\widgets\Pjax;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use omnilight\assets\SweetAlertAsset;
SweetAlertAsset::register($this);
ThemeAsset::register($this);

$this->title = Yii::t('invoice','Invoice Detail');

$canInvoiceAmountCreate = app\access\AccessControl::can("invoice/amount/create", ['peid' => $processEntityModel->ProcessEntityID]);
$canInvoiceAmountUpdate = app\access\AccessControl::can("invoice/amount/update", ['peid' => $processEntityModel->ProcessEntityID]);
$canInvoiceAmountDelete = app\access\AccessControl::can("invoice/amount/delete", ['peid' => $processEntityModel->ProcessEntityID]);
?>
<?= PageHeader::widget([
    'title' => $this->title,
    'icon' => 'fa-dollar',
]);
?>
<div class="bs-example bs-example-tabs">
    <?= $this->render('_view_tab_links', [
        'tab' => '#invoice_amount',
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
                <td><?= Yii::t('contract','Subcontractor') ?> </td><td><i class="badge badge-orange"><?= Html::encode($invoiceModel->contract->subContractor->PersonTitle); ?></i>&nbsp;|&nbsp;</td>
                <td><?= Yii::t('contract','Contract No') ?></td><td><i class="badge badge-purple"><?= Html::encode($invoiceModel->contract->ContractNo); ?></i>&nbsp;|&nbsp;</td>
                <td><?= Yii::t('invoice','Invoice Number') ?></td><td><i class="badge badge-brown"><?= Html::encode($invoiceModel->Title); ?> </i>&nbsp;|&nbsp;</td>
                <td><?= Yii::t('invoice','Invoice Type') ?></td><td><i class="badge badge-info"><?= Html::encode($invoiceModel->type->InvoiceType); ?> </i> </td>
            </tr>
        </table>
    </div>
</div>
                <div>
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="pull-right">
                <?php if ($canInvoiceAmountCreate) { ?>
                    <button class="btn btn-sm btn-success" data-url="<?= Yii::$app->urlManager->createAbsoluteUrl(['invoice/amount/create', 'peid' => $processEntityModel->ProcessEntityID]); ?>" onclick="showInvoiceAmountModel(this)"><i class="fa-plus"></i> New Amount</button>
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
                    'dataProvider' => $invoiceAmountDataProvider,
                    'resizableColumns' => true,
                    'columns' => [
//                        [
//                            'attribute' => 'costcode',
//                            'format' => 'raw',
//                            'options' => array('width' => '200px'),
//                            'value' => function($model) {
//                                return Utility::generateCostCodeLink($model->costCode->NodeID, $model->costCode->NodeFullCode);
//                            }
//                        ],
                        [
                            'attribute' => 'costcode',
                            'format' => 'raw',
                            'options' => array('width' => '200px'),
                            'value' => function($model) {
                            // right top
                                return kartik\popover\PopoverX::widget([
                                'header' => '',
                                'closeButton' => ['label'=>''],
                                'options' => [
                                    "style"=>"background-color: rgb(146, 146, 146);",
                                ],

                                'placement' => kartik\popover\PopoverX::ALIGN_RIGHT_BOTTOM,
                                'content' => "<div class='row' ><table class='psa-grid'><tbody><tr><th width='55'  style='color:white;'>Level 1</th><td>Phase 22-24</td></tr><tr><th style='color:white;'>Level 2</th><td>Onshore Facilities</td></tr><tr><th style='color:white;'>Level 3</th><td>Plant Construction</td></tr><tr><th style='color:white;'>Level 4</th><td>Safety and Security</td></tr><tr><th style='color:white;'>Level 5</th><td> -- </td></tr><tr><th style='color:white;'>Level 6</th><td> -- </td></tr><tr><th style='color:white;'>Level 7</th><td> -- </td></tr><tr><th style='color:white;'>Level 8</th><td> -- </td></tr></tbody></table></div>",
                                'toggleButton' => [
                                    'label'=>$model->costCode->NodeFullCode,
                                    'id'=>"ss-".$model->costCode->NodeID,
                                    "onmouseover" => 'return showCostCodeLevelsNew(this);',
                                    "nodeid"=>$model->costCode->NodeID,
                                    'style' =>"background:none;color:#3165fd;border:none;padding:0!important;font:inherit;border-bottom:1px solid #3165fd;curser:pointer;",
                                    ],
                            ]);
                                return Utility::generateCostCodeLink($model->costCode->NodeID, $model->costCode->NodeFullCode);
                            }
                        ],
                        [
                            'attribute' => 'amountType',
                            'value' => 'type.InvoiceAmountType',
                            'format' => 'raw',
                            'options' => array('width' => '70px'),
                        ],
                        [
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
                            'label' => Yii::t('invoice','Requested'),
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
                            'format' => 'raw',
                            'attribute' => 'AmountApproved1',
                            'label' => Yii::t('invoice','Approved'),
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
                            'format' => 'raw',
                            'attribute' => 'Income1',
                            'label' => Yii::t('invoice','Income'),
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
                            'format' => 'raw',
                            'attribute' => 'ownerContractNo',
                            'label' => Yii::t('invoice','Owner ContractNo'),
                            'value' => function($model) {
                                if (isset($model->owner)) {
                                    return Html::a($model->owner->ContractNo, '#', [
                                                'data-original-title' => Html::encode($model->owner->subContractor->PersonTitle),
                                                'data-toggle' => 'tooltip'
                                    ]);
                                }
                            },
                            'options' => [
                                'width' => '160px',
                            ],
                        ],
                        [
                            'label' => Yii::t('invoice','Last level'),
                            'value' => function($model) {
                                return $model->costCode->NodeText;
                            }
                        ],
                        [
                            'class' => yii\grid\ActionColumn::className(),
                            'header' => Yii::t('invoice' , 'actions'),
                            'template' => '{edit}&nbsp{delete}',
                            'buttons' => [
                                'edit' => function($url, $model, $key) use ($canInvoiceAmountUpdate,$processEntityModel) {
                                    if ($canInvoiceAmountUpdate) {
                                        return Html::a('<i class="im-pencil"></i>', '#', ['data-key' => $key, 'data-pjax' => '0', 'data-url' => Yii::$app->urlManager->createAbsoluteUrl(['invoice/amount/update','peid'=>$processEntityModel->ProcessEntityID ,'invamid' => $model['ID']]), 'onclick' => 'showInvoiceAmountModel(this)',]);
                                    }
                                },
                                'delete' => function($url, $data, $key)use($canInvoiceAmountDelete,$processEntityModel) {
                                    if ($canInvoiceAmountDelete) {
                                        return Html::a('&nbsp;<i class="br-trashcan"></i>', '#', [
                                                    'data-toggle' => 'tooltip',
                                                    'data-placement' => 'left',
                                                    'title' => 'Click to delete Amount',
                                                    'id' => 'delete-amount-' . $data['ID'],
                                                    'onclick' => 'confirmBox("Are you sure to delete this item?!", "Delete!", "deleteInvoiceAmount(' . $data['ID'] . ')");',
                                                    'delete-url' => Yii::$app->urlManager->createUrl(["invoice/amount/delete", 'peid' => $processEntityModel->ProcessEntityID, 'aid' => $data['ID']]),
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
                <?= Yii::t('invoice','Periodic') ?>
            </div>
            <div class="panel-body" style="padding: 0px;">
                <table class="table table-striped table-hover non-responsive psa-grid table-bordered">
                    <thead>
                        <tr>
                            <th><?= Yii::t('invoice','Currency') ?></th>
                            <th><?= Yii::t('invoice','Requested') ?></th>
                            <th><?= Yii::t('invoice','Approved') ?></th>
                            <th><?= Yii::t('invoice','Income') ?></th>
                            <th><?= Yii::t('invoice','Penalty') ?></th>
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
                <div class="row mt10" >
                    <div class="col-lg-12 col-md-12">
                    <?php
                        echo app\components\ContractCumulative::widget(['id'=>'invoice-amount-total','cid'=>$invoiceModel->ContractID,'hdate'=>$invoiceModel->CutOffDateHijri]);
                    ?>
                    </div>
                </div>

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
            
            <?php Pjax::end(); ?>
            <?php Modal::end(); ?>
            

            </div>
            </div>
    </div>
</div>


<script>
    

    function invoiceAmountCallbackPjax(data){
        if (data.result)
        {
            var dt = data.data;
            swal({
                title: 'Success',
                type: 'success',
                text: data.message,
                timer: 1000,
                showConfirmButton: false,
            });
            $('#invoice-amount-modal').modal('hide');
            location.reload();

        } else
        {
            swal('Error', data.message, 'error');
        }
    }
    
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

    function deleteInvoiceAmount(id) {
        var url = $('#delete-amount-' + id).attr('delete-url');
        $.ajax({
            url: url,
        }).done(function (data) {
            if (data.result == 1) {
                location.reload();
                showMsgBoxInBottomRight(data.message, 'success');
//                $.pjax.reload({
//                    container: '#contract-budget-grid-pjax',
//                    timeout: false,
//                    push: false,
//                    replace: false,
//                });
//                setTimeout("showMsgBoxInBottomRight('" + data.message + "', 'success')", 300);
            } else {
                showMsgBoxInBottomRight(data.message, 'error');
            }
        }).error(function () {
            showMsgBoxInBottomRight("Error on connecting to server.", "error");
            ajaxProgressLoader('hide', '.popup-box');
            return false;
        });
        return false;
    }
</script>
