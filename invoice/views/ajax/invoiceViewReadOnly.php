<?php

use yii\widgets\Pjax;
use yii\helpers\Html;
use app\components\Utility;
use yii\grid\GridView;
?>
<div class="bs-example bs-example-tabs">
    <ul id="myTab" class="nav nav-tabs" role="tablist">
        <li class="active"><a href="#InvoiceInfo_detail" role="tab" data-toggle="tab"><?= Yii::t('invoice','Invoice Info') ?></a></li>
        <li class=""><a href="#InvoiceAmount_detail" role="tab" data-toggle="tab"><?= Yii::t('invoice','Invoice Amount') ?></a></li>
        <li class=""><a href="#InvoiceDocument_detail" role="tab" data-toggle="tab"><?= Yii::t('invoice','Invoice Document') ?></a></li>
        <?php if ($invoiceModel->contract->ContractCategory == 13): ?><li class=""><a href="#PKSKInfo_detail" role="tab" data-toggle="tab"><?= Yii::t('invoice','PSKS Info') ?></a></li><?php endif; ?>
    </ul>
    <div id="myTabContent" class="tab-content">
        <div class="tab-pane fade active in" id="InvoiceInfo_detail" style="padding:10px 0;">
            
            <div style="width:100%;padding:5px 0px 5px 0px;background: #ccf;">
                <div style="padding:5px;background-color: #e4e9eb;font-size: 12px;">
                    <table>
                        <tr>
                            <td><?= Yii::t('contract','Subcontractor') ?> </td><td><i class="badge badge-orange"><?= Html::encode($invoiceModel->contract->subContractor->PersonTitle); ?></i>&nbsp;|&nbsp;</td>
                            <td><?= Yii::t('contract','Contract No') ?></td><td><i class="badge badge-purple"><?= Html::encode($invoiceModel->contract->ContractNo); ?></i>&nbsp;|&nbsp;</td>
                            <td><?= Yii::t('contract','Contract Subject') ?></td><td><i class="badge badge-purple"><?= Html::encode($invoiceModel->contract->ContractSubject); ?></i></td>
                            
                        </tr>
                    </table>
                </div>
            </div>
            <div class="panel-body" id="invoice-info">
                <form class="psa-form form-horizontal " role="form">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12" >
                            <!---------------------->
                            <div class="form-group">
                                <label class="col-lg-3 col-md-3 col-sm-12 control-label"><?= Yii::t('contract','Invoice No') ?></label>
                                <div class="col-lg-5 col-md-5">
                                    <input class="form-control"  id="InvoiceID" value="<?= Html::encode($invoiceModel->contract->ContractNo); ?>" readonly>
                                </div>
                            </div>

                            <!---------------------->
                            <div class="form-group">
                                <label class="col-lg-3 col-md-3 col-sm-12 control-label"><?= Yii::t('contract','Contract Subject') ?></label>
                                <div class="col-lg-5 col-md-5">
                                    <textarea class="form-control"  id="ContractNo" readonly style="height:100px;resize:none;">
                                        <?= trim(Html::encode($invoiceModel->contract->ContractSubject)); ?>
                                    </textarea>
                                </div>
                            </div>
                            <!---------------------->
                            <div class="form-group">
                                <label class="col-lg-3 col-md-3 col-sm-12 control-label"><?= Yii::t('contract','Subcontractor') ?></label>
                                <div class="col-lg-5 col-md-5">
                                    <input class="form-control"  id="InvoiceID" value="<?= Html::encode($invoiceModel->contract->subContractor->PersonTitle); ?>" readonly>
                                </div>
                            </div>
                            <!---------------------->
                            <div class="form-group">
                                <label class="col-lg-3 col-md-3 col-sm-12 control-label"><?= Yii::t('invoice','Invoice Type') ?></label>
                                <div class="col-lg-5 col-md-5">
                                    <input class="form-control"  id="InvoiceID" value="<?= Html::encode($invoiceModel->type->InvoiceType); ?>" readonly>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-lg-3 col-md-3 col-sm-12 control-label"><?= Yii::t('invoice','Description') ?></label>
                                <div class="col-lg-5 col-md-5">

                                    <textarea class="form-control"  id="ContractNo" readonly style="height:100px;resize:none;">
                                        <?= trim(Html::encode($invoiceModel->Description)); ?>
                                    </textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12" >
                            <!---------------------->
                            <div class="form-group">
                                <label class="col-lg-3 col-md-3 col-sm-12 control-label"><?= Yii::t('invoice','Invoice No') ?></label>
                                <div class="col-lg-5 col-md-5">
                                    <input class="form-control"  id="InvoiceID" value="<?= Html::encode($invoiceModel->InvoiceID); ?>" readonly>
                                </div>
                            </div>
                            <!---------------------->
                            <div class="form-group">
                                <label class="col-lg-3 col-md-3 col-sm-12 control-label"><?= Yii::t('invoice','Revision Number') ?></label>
                                <div class="col-lg-5 col-md-5">
                                    <input class="form-control"  id="InvoiceID" value="<?= Html::encode($invoiceModel->RevisionNumber); ?>" readonly>
                                </div>
                            </div>



                            <!---------------------->
                            <div class="form-group">
                                <label class="col-lg-3 col-md-3 col-sm-12 control-label"><?= Yii::t('invoice','Cut Off Date') ?></label>
                                <div class="col-lg-5 col-md-5">
                                    <div class="input-group">
                                        <input class="form-control"  id="ContractNo" value="<?= Yii::$app->formatter->asDate($invoiceModel->CutOffDate); ?>" readonly>
                                        <span class="input-group-addon">G</span></div>
                                    <div class="input-group">
                                        <input class="form-control"  id="ContractNo" value="<?= $invoiceModel->CutOffDateHijri; ?>" readonly>
                                        <span class="input-group-addon ">H</span></div>
                                </div>
                            </div>

                            <!---------------------->
                            <div class="form-group">
                                <label class="col-lg-3 col-md-3 col-sm-12 control-label"><?= Yii::t('invoice','Approved1 USD(F2)') ?></label>
                                <div class="col-lg-5 col-md-5">
                                    <input class="form-control"  id="InvoiceID" value="<?= Html::encode($invoiceModel->approved1UsdF2); ?>" readonly>
                                </div>
                            </div>
                            <?php if (!empty($invoiceModel->Currency2ID)): ?>
                                <div class="form-group">
                                    <label class="col-lg-3 col-md-3 col-sm-12 control-label"><?= Yii::t('invoice','Approved2 USD(F2)') ?></label>
                                    <div class="col-lg-5 col-md-5">
                                        <input class="form-control"  id="InvoiceID" value="<?= Html::encode($invoiceModel->approved2UsdF2); ?>" readonly>
                                    </div>
                                </div>
                            <?php endif; ?>


                            <div class="form-group">
                                <label class="col-lg-3 col-md-3 col-sm-12 control-label"><?= Yii::t('invoice','Approved1 USD(F3)') ?></label>
                                <div class="col-lg-5 col-md-5">
                                    <input class="form-control"  id="InvoiceID" value="<?= Html::encode($invoiceModel->approved1UsdF3); ?>" readonly>
                                </div>
                            </div>

                            <?php if (!empty($invoiceModel->Currency2ID)): ?>
                                <div class="form-group">
                                    <label class="col-lg-3 col-md-3 col-sm-12 control-label"><?= Yii::t('invoice','Approved2 USD(F3)') ?></label>
                                    <div class="col-lg-5 col-md-5">
                                        <input class="form-control"  id="InvoiceID" value="<?= Html::encode($invoiceModel->approved2UsdF3); ?>" readonly>
                                    </div>
                                </div>
                            <?php endif; ?>




                            <div class="form-group">
                                <label class="col-lg-3 col-md-3 col-sm-12 control-label"><?= Yii::t('invoice','Approved1 IRR(F2)') ?></label>
                                <div class="col-lg-5 col-md-5">
                                    <input class="form-control"  id="InvoiceID" value="<?= Html::encode($invoiceModel->approved1IrrF2); ?>" readonly>
                                </div>
                            </div>



                            <?php if (!empty($invoiceModel->Currency2ID)): ?>
                                <div class="form-group">
                                    <label class="col-lg-3 col-md-3 col-sm-12 control-label"><?= Yii::t('invoice','Approved2 IRR(F2)') ?></label>
                                    <div class="col-lg-5 col-md-5">
                                        <input class="form-control"  id="InvoiceID" value="<?= Html::encode($invoiceModel->approved2IrrF2); ?>" readonly>
                                    </div>
                                </div>
                            <?php endif; ?>




                            <div class="form-group">
                                <label class="col-lg-3 col-md-3 col-sm-12 control-label"><?= Yii::t('invoice','Approved1 IRR(F3)') ?></label>
                                <div class="col-lg-5 col-md-5">
                                    <input class="form-control"  id="InvoiceID" value="<?= Html::encode($invoiceModel->approved1IrrF3); ?>" readonly>
                                </div>
                            </div>

                            <?php if (!empty($invoiceModel->Currency2ID)): ?>
                                <div class="form-group">
                                    <label class="col-lg-3 col-md-3 col-sm-12 control-label"><?= Yii::t('invoice','Approved2 IRR(F3)') ?></label>
                                    <div class="col-lg-5 col-md-5">
                                        <input class="form-control"  id="InvoiceID" value="<?= Html::encode($invoiceModel->approved2IrrF3); ?>" readonly>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>


                    </div>
            </div>
            <!---------------------------------------------------------------------------->
        </div>

        <!------------------------------InvoiceAmount_detail---------------------------------------------->
        <div class="tab-pane fade" id="InvoiceAmount_detail">
            <div class="row InvoiceAmount-grid" style="position:relative;">

                <div style="width: 100%;padding:5px 0px 5px 0px;background: #ccf;">
                    <div style="padding:5px;background-color: #e4e9eb;font-size: 12px;">
                        <table>
                            <tr>
                                <td><?= Yii::t('contract','Subcontractor') ?> </td><td><i class="badge badge-orange"><?= Html::encode($invoiceModel->contract->subContractor->PersonTitle); ?></i>&nbsp;|&nbsp;</td>
                                <td><?= Yii::t('contract','Contract No') ?></td><td><i class="badge badge-purple"><?= Html::encode($invoiceModel->contract->ContractNo); ?></i>&nbsp;|&nbsp;</td>
                                <td><?= Yii::t('invoice','Invoice Number') ?></td><td><i class="badge badge-brown"><?= Html::encode($invoiceModel->Title); ?> </i>&nbsp;|&nbsp;</td>
                                <td><?= Yii::t('invoice','Invoice Type') ?></td><td> <i class="badge badge-info"><?= Html::encode($invoiceModel->type->InvoiceType); ?> </i></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <?php Pjax::begin(['id' => 'invoiceAmountPjax-detail']); ?>
                <?=
                GridView::widget([
                    'id' => 'tbl-invoice-grid-detail',
                    'rowOptions' => function ($model, $key, $index, $grid) {
                $id = $key;
                $class = $index % 2 ? 'odd' : 'even';
                return array('index' => $index, 'class' => $class, 'id' => 'row-' . $id);
            },
                    'filterModel' => $invoiceAmountModel,
                    'dataProvider' => $invoiceAmountDataProvider,
                    'columns' => [
                        [
                            'format' => 'raw',
                            'attribute' => 'costcode',
                            'value' => 'costCode.NodeFullCode',
                            'options' => array('width' => '200px'),
                        ],
                        [
                            'format' => 'raw',
                            'attribute' => 'amountType',
                            'value' => 'type.InvoiceAmountType',
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
                                        . Utility::moneyFormat($data["AmountRequested1"])
                                        . '<tr class="top-border"><td >'
                                        . Utility::moneyFormat($data["AmountRequested2"])
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
                                        . Utility::moneyFormat($data["AmountApproved1"])
                                        . '<tr class="top-border"><td >'
                                        . Utility::moneyFormat($data["AmountApproved2"])
                                        . '</table>';
                            }
                        ],
                        [
                            'label' => Yii::t('invoice','Income'),
                            'format' => 'raw',
                            'attribute' => 'Income1',
                            'value' => function($data) {
                                return '<table class="inner-table">'
                                        . '<tr><td width=\'200\'>'
                                        . Utility::moneyFormat($data["Income1"])
                                        . '<tr class="top-border"><td >'
                                        . Utility::moneyFormat($data["Income2"])
                                        . '</table>';
                            }
                        ],
                        [
                            'label' => Yii::t('invoice','Owner ContractNo'),
                            'format' => 'raw',
                            'attribute' => 'ownerContractNo',
                            'value' => function($model){
                                if(isset($model->owner)){
                                    return Html::a($model->owner->ContractNo, '#', [
                                        'data-original-title' => $model->owner->subContractor->PersonTitle,
                                        'data-toggle' => 'tooltip'
                                    ]);
                                }
                            },
                            'options' => [
                                'width' => '160px'
                            ],
                        ],
                    ],
                ]);
                ?>
                <?php Pjax::end(); ?>  
            </div>
        </div>
        <!---------------------------------InvoiceAmount_detail-------------------------------------------->


        <!---------------------------------InvoiceDocument_detail-------------------------------------------->
        <div class="tab-pane fade" id="InvoiceDocument_detail">
            <div class="row InvoiceDocument-grid" style="position:relative;">
                <div style="width: 100%;padding:5px 0px 5px 0px;background: #ccf;">
                    <div style="padding:5px;background-color: #e4e9eb;font-size: 12px;">
                        <table>
                            <tr>
                                <td><?= Yii::t('contract','Subcontractor') ?> </td><td><i class="badge badge-orange"><?= Html::encode($invoiceModel->contract->subContractor->PersonTitle); ?></i>&nbsp;|&nbsp;</td>
                                <td><?= Yii::t('contract','Contract No') ?></td><td><i class="badge badge-purple"><?= Html::encode($invoiceModel->contract->ContractNo); ?></i>&nbsp;|&nbsp;</td>
                                <td><?= Yii::t('invoice','Invoice Number') ?></td><td><i class="badge badge-brown"><?= Html::encode($invoiceModel->Title); ?> </i>&nbsp;|&nbsp;</td>
                                <td><?= Yii::t('invoice','Invoice Type') ?></td><td> <i class="badge badge-info"><?= Html::encode($invoiceModel->type->InvoiceType); ?> </i></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <?php Pjax::begin(['id' => 'doclist-pjax-detail']); ?>
                <?=
                GridView::widget([
                    'id' => 'tbl-invoice-grid3-detail',
                    'filterModel' => $invoiceDocumentModel,
                    'dataProvider' => $invoiceDocumentDataProvider,
                    'columns' => [
                        [
                            'format' => 'raw',
                            'attribute' => 'documentType',
                            'value' => 'type.InvoiceDocumentTypeTitle',
                            'options' => ['width' => '100px'],
                        ],
                        'FileName',
                        'Description',
                        [
                            'format' => 'raw',
                            'attribute' => 'userName',
//                            'value' => 'user.UserName',
                            'value' => 'user.fullName',
                            'options' => ['width' => '120px'],
                        ],
                        [
                            'format' => 'raw',
                            'attribute' => 'InsertDateHijri',
                            'options' => ['width' => '120px'],
                        ],
                        [
                            'format' => 'raw',
                            'attribute' => 'actionTitle',
                            'value' => 'action.ActionTitle2',
                        //'options' => ['width' => '120px'],
                        ],
                        [
                            'class' => yii\grid\ActionColumn::className(),
                            'template' => '{download}',
                            'buttons' => [
                                'download' => function($url, $model, $key) {
                            return Html::a('<i class="fa-arrow-down"></i>', $url = Yii::$app->urlManager->createUrl(["invoice/document/download", "docid" => $model["InvoiceDocumentID"],'peid'=>$model->invoice->processEntity->ProcessEntityID]), [
                                        'data-pjax' => '0',
                                        ////'visible'=>'$data["FileID"]!= null',
                                        //'click'=>'function(){return getRevisionFile(this);}',
                                        'title' => 'Click to download file',
                                            //'params'=>'cid='.$contractID,
                            ]);
                        }
                            ]
                        ]
                    ],
                ]);
                ?>
                <?php Pjax::end(); ?>

            </div> 
        </div>
        <!---------------------------------InvoiceDocument_detail-------------------------------------------->


        <!---------------------------------InvoicePksk_detail-------------------------------------------->
        <?php if ($invoiceModel->contract->ContractCategory == 13): ?>
            <div class="tab-pane fade" id="PKSKInfo_detail">
                <div class="row PKSKInfo-grid" style="position:relative;">
                    <div style="width: 100%;padding:5px 0px 5px 0px;background: #ccf;">
                        <div style="padding:5px;background-color: #e4e9eb;font-size: 12px;">
                            Sub Contractor <i class="badge badge-orange"><?= Html::encode($invoiceModel->contract->subContractor->PersonTitle); ?></i> |
                            Contract Number <i class="badge badge-purple"><?= Html::encode($invoiceModel->contract->ContractNo); ?></i> |
                            Invoice Number <i class="badge badge-brown"><?= Html::encode($invoiceModel->Title); ?> </i> |
                            Invoice Type <i class="badge badge-info"><?= Html::encode($invoiceModel->type->InvoiceType); ?> </i> 
                        </div>
                    </div>
                    <?php Pjax::begin(['id' => 'pksk-pjax-detail']); ?>
                    <?=
                    GridView::widget([
                        'id' => 'tbl-invoice-grid4-detail',
                        'filterModel' => $invoicePkskModel,
                        'rowOptions' => function ($model, $key, $index, $grid) {
                    $id = $key;
                    $class = $index % 2 ? 'odd' : 'even';
                    return array('index' => $index, 'class' => $class, 'id' => 'rowpksk-' . $key);
                },
                        'dataProvider' => $invoicePkskDataProvider,
                        'columns' => [
                            'CI_No',
                            'PL_No',
                            'MSR_No',
                            'MSR_Description',
                            [
                                'attribute' => 'CI_Amount1',
                                'label' => 'Amount',
                                'format' => 'raw',
                                'filter' => false,
                                'options' => ['width' => '200px'],
                                'value' => function($model) {
                            if ($model->CI_Currency1IDRef > 0) {
                                $cur1 = $model->currency1->CurrencyTitle;
                            } else {
                                $cur1 = '';
                            }
                            if ($model->CI_Currency2IDRef > 0) {
                                $cur2 = $model->currency2->CurrencyTitle;
                            } else {
                                $cur2 = '';
                            }
                            return '<table class="inner-table">'
                                    . '<tr><td width=\'200\'>'
                                    . Utility::moneyFormat($model->CI_Amount1)
                                    . '</td><td class="label-silver">' . $cur1 . '</td></tr>'
                                    . '<tr class="top-border"><td >'
                                    . Utility::moneyFormat($model->CI_Amount2)
                                    . ' </td><td class="label-silver">' . $cur2 . '</td></tr>'
                                    . '</table>';
                        }
                            ],
                        ],
                    ]);
                    ?>
    <?php Pjax::end(); ?>
                </div>
            </div>
<?php endif; ?>
        <!---------------------------------InvoicePksk_detail-------------------------------------------->
    </div>    
</div>