<?php

use app\components\PageHeader;
use app\modules\invoice\assets\InvoiceListAsset;
use yii\helpers\Html;
use app\components\Utility;
use app\assets\DatepickerAsset;

DatepickerAsset::register($this);
$this->title = Yii::t('invoice', 'Invoice Detail');


InvoiceListAsset::register($this);
$canInvoiceUpdate = app\access\AccessControl::can("invoice/invoice/update", ['peid' => $processEntityModel->ProcessEntityID]);
?>
<?=
PageHeader::widget([
    'title' => $this->title,
    'icon' => 'fa-dollar',
//    'breadcrumbs' => $this->breadcrumbs,
]);
?>
<div class="bs-example bs-example-tabs">
    <?= $this->render('_view_tab_links', [
        'tab' => '#invoice_info',
        'access' => $access,
        'invoiceModel' => $invoiceModel,
        'peid' => $peid,
        'todoExists' => $todoExists,
    ]); ?>
    <!--begin-task-->
    <div id="myTabContent" class="tab-content">
        <?php if ($access[2] === 2 || $access[2] === 3) { ?>
            <div class="tab-pane fade active in" id="InvoiceInfo" style="padding:10px 0;">
                <div style="width:100%;padding:5px 0px 5px 0px;background: #ccf;">
                    <div style="padding:5px;background-color: #e4e9eb;font-size: 12px;">
                        <table>
                            <tr>
                                <td><?= Yii::t('contract', 'Subcontractor') ?> </td><td><i class="badge badge-orange"><?= $invoiceModel->contract->subContractor->PersonTitle; ?></i>&nbsp;|&nbsp;</td>
                                <td><?= Yii::t('contract', 'Contract No') ?></td><td><i class="badge badge-purple"><?= $invoiceModel->contract->ContractNo; ?></i>&nbsp;|&nbsp;</td>
                                <td><?= Yii::t('contract', 'Contract Subject') ?></td><td><i class="badge badge-brown"><?= $invoiceModel->contract->ContractSubject; ?> </i></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="panel-body" id="invoice-pjax-container">

                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <div class="pull-right">
                            <?php if ($canInvoiceUpdate) { ?>
                                <button class="btn btn-sm btn-success" id="btn-edit-invoice" data-url="<?= Yii::$app->urlManager->createAbsoluteUrl(['invoice/invoice/update', 'peid' => $processEntityModel->ProcessEntityID]); ?>">Edit Invoice <i class="fa-edit"></i></button>
                            <?php } ?>                         
                        </div>

                    </div>
                </div>
                <div id="invoice-info">
                    <?php
                    echo kartik\detail\DetailView::widget([
                        'id' => '',
                        'model' => $invoiceModel,
                        'attributes' => [
                            [
                                'label' => Yii::t('invoice', 'Invoice Type'),
                                'value' => Html::encode($invoiceModel->type->InvoiceType),
                            ],
                            [
                                'attribute' => 'Title',
                            ],
                            [
                                'attribute' => 'fromCutOffDate',
                                'value' => isset($invoiceModel->fromCutOffDateHijri) ? '<div class="form-horizontal">
                                            <div class="form-group">
                                                <div class="col-lg-1 col-md-1"></div>
                                                <div class="col-lg-5 col-md-5" style="padding:0px;">
                                                    ' . Html::textInput('fromCutOffDateHijri', explode(' ', $invoiceModel->fromCutOffDateHijri)[0], ['class' => 'form-control input-sm right-icon', 'disabled' => 'disabled']) . '
                                                        <i class="fa-time s12 right-input-icon" style="right: 15px;"></i>
                                                </div>
                                                <div class="col-lg-1 col-md-1"></div>
                                                <div class="col-lg-5 col-md-5" style="padding:0px;">
                                                    ' . Html::textInput('CutOffDate', explode(' ', $invoiceModel->fromCutOffDate)[0], ['class' => 'form-control input-sm right-icon', 'disabled' => 'disabled']) . '
                                                        <i class="fa-time s12 right-input-icon" style="right: 15px;"></i>
                                                </div>
                                            </div>
                                            </div>' : null,
                                'format' => 'raw',
                                'hideIfEmpty' => true,
                            ],
                            [
                                'attribute' => 'CutOffDate',
                                'value' => '<div class="form-horizontal">
                                            <div class="form-group">
                                                <div class="col-lg-1 col-md-1"></div>
                                                <div class="col-lg-5 col-md-5" style="padding:0px;">
                                                    ' . Html::textInput('CutOffDateHijri', explode(' ', $invoiceModel->CutOffDateHijri)[0], ['class' => 'form-control input-sm right-icon', 'disabled' => 'disabled']) . '
                                                        <i class="fa-time s12 right-input-icon" style="right: 15px;"></i>
                                                </div>
                                                <div class="col-lg-1 col-md-1"></div>
                                                <div class="col-lg-5 col-md-5" style="padding:0px;">
                                                    ' . Html::textInput('CutOffDate', explode(' ', $invoiceModel->CutOffDate)[0], ['class' => 'form-control input-sm right-icon', 'disabled' => 'disabled']) . '
                                                        <i class="fa-time s12 right-input-icon" style="right: 15px;"></i>
                                                </div>
                                            </div>
                                            </div>',
                                'format' => 'raw',
                            ],
                            [
                                'attribute' => 'financialDocNumber',
                            ],
                            [
                                'attribute' => 'financialDocDateHijri',
                            ],
                            [
                                'attribute' => 'approved1UsdF2',
                                'value' => !empty($invoiceModel->approved1UsdF2) ? Utility::moneyFormat($invoiceModel->approved1UsdF2) : ''
                            ],
                            [
                                'attribute' => 'approved1IrrF2',
                                'value' => !empty($invoiceModel->approved1IrrF2) ? Utility::moneyFormat($invoiceModel->approved1IrrF2) : ''
                            ],
                            [
                                'attribute' => 'approved1UsdF3',
                                'value' => !empty($invoiceModel->approved1UsdF3) ? Utility::moneyFormat($invoiceModel->approved1UsdF3) : ''
                            ],
                            [
                                'attribute' => 'approved1IrrF3',
                                'value' => !empty($invoiceModel->approved1IrrF3) ? Utility::moneyFormat($invoiceModel->approved1IrrF3) : ''
                            ],
                            [
                                'attribute' => 'Description',
                            ],
                        ]
                    ]);
                    ?>
                </div>
                <!---------------------------------------------------------------------------->
            </div>
        <?php } ?>
    </div>
</div>

