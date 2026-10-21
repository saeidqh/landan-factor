<?php

use app\components\PageHeader;
use kartik\grid\GridView;
use app\components\Utility;
use yii\widgets\Pjax;
use yii\helpers\Html;
use app\components\PopupBox;

$this->title = Yii::t('invoice', 'Invoice Detail');
?>
<?=
PageHeader::widget([
    'title' => $this->title,
    'icon' => 'fa-dollar',
]);
?>
<div class="bs-example bs-example-tabs">
    <?=
    $this->render('_view_tab_links', [
        'tab' => '#contract_invoices',
        'access' => $access,
        'invoiceModel' => $invoiceModel,
        'peid' => $peid,
        'todoExists' => $todoExists,
    ]);
    ?>
    <!--begin-task-->
    <div id="myTabContent" class="tab-content">
        <div class="tab-pane fade active in">
            <div class="row">
                <div style="width: 100%;padding:5px 0px 5px 0px;background: #ccf;">
                    <div style="padding:5px;background-color: #e4e9eb;font-size: 12px;">
                        Sub Contractor <i class="badge badge-orange"><?= Html::encode($invoiceModel->contract->subContractor->PersonTitle); ?></i> |
                        Contract Number <i class="badge badge-purple"><?= Html::encode($invoiceModel->contract->ContractNo); ?></i>
                    </div>
                </div>
                <?php Pjax::begin(['id' => 'conInvoiceList']); ?>

                <?=
                GridView::widget([
                    'id' => 'tbl-contracts-grid6',
                    'filterModel' => $contractInvoiceSearchModel,
                    'dataProvider' => $contractInvoiceDataProvider,
                    'columns' => [
                        [
                            'attribute' => 'Title',
                            'value' => 'Title',
                        ],
                        [
                            'attribute' => 'invoiceType',
                            'value' => 'type.InvoiceType',
                            'filter' => Html::activeDropDownList($contractInvoiceSearchModel, 'invoiceType', $invoiceTypeArray, ['class' => 'form-control', 'prompt' => 'Select Type']), /* 'multiple'=>'true' */
                            'options' => [
                                'width' => '130px',
                            ]
                        ],
                        [
                            'attribute' => 'CutOffDateHijri',
                            'label' => 'CutOffDate',
                            'format' => 'raw',
                            'value' => function($data) {
                                return '<table class="inner-table">'
                                        . '<tr><td width=\'200\'>'
                                        . $data["CutOffDateHijri"]
                                        . '<tr class="top-border"><td >'
                                        . Date("Y-m-d", strtotime($data["CutOffDate"]))
                                        . '</table>';
                            },
                        ],
                        [
                            'attribute' => 'Currency',
                            'label' => 'Currency',
                            'format' => 'raw',
                            'filter' => false,
                            'value' => function($data) {
                                return '<table class="inner-table">'
                                        . '<tr><td width=\'100\'>'
                                        . (($data["Currency1Title"] != null) ? $data["Currency1Title"] : '&nbsp;')
                                        . '<tr class="top-border"><td >'
                                        . (($data["Currency2Title"] != null) ? $data["Currency2Title"] : '&nbsp;')
                                        . '</table>';
                            }
                        ],
                        [
                            'attribute' => 'requested',
                            'label' => 'Requested',
                            'format' => 'raw',
                            'filter' => false,
                            'value' => function($data) {
                                $color = 'black';
                                if ($data->AmountStatus == 0) {
                                    $color = '#f68484';
                                } else if ($data->AmountStatus == 1) {
                                    $color = '#259BE9';
                                } else if ($data->AmountStatus == 5) {
                                    $color = '#4ac77c';
                                }
                                $back = 'black';
                                if ($data->AmountStatus == 0) {
                                    $back = '#F7C5C5';
                                } else if ($data->AmountStatus == 1) {
                                    $back = '#B9DBF1';
                                } else if ($data->AmountStatus == 5) {
                                    $back = '#C1F0D3';
                                }
                                return '<table class="inner-table" style="background:' . $back . '">'
                                        . '<tr><td width=\'180\' style="color:' . $color . ';">'
                                        . '<b>' . Utility::moneyFormat($data["Requested1"]) . '</b>'
                                        . '<tr class="top-border"><td style="color:' . $color . ';">'
                                        . '<b>' . Utility::moneyFormat($data["Requested2"]) . '</b>'
                                        . '</table>';
                            }
                        ],
                        [
                            'attribute' => 'approved',
                            'label' => 'Approved',
                            'format' => 'raw',
                            'filter' => false,
                            'value' => function($data) {
                                $color = 'black';
                                if ($data->AmountStatus == 0) {
                                    $color = '#f68484';
                                } else if ($data->AmountStatus == 1) {
                                    $color = '#259BE9';
                                } else if ($data->AmountStatus == 5) {
                                    $color = '#4ac77c';
                                }
                                $back = 'black';
                                if ($data->AmountStatus == 0) {
                                    $back = '#F7C5C5';
                                } else if ($data->AmountStatus == 1) {
                                    $back = '#B9DBF1';
                                } else if ($data->AmountStatus == 5) {
                                    $back = '#C1F0D3';
                                }
                                return '<table class="inner-table" style="background:' . $back . '">'
                                        . '<tr>'
                                        . '<td width=\'180\' style="color:' . $color . ';">'
                                        . '<b>' . Utility::moneyFormat($data["Approved1"], ($data->currency1->CurrencyID != 1) ? 2 : null) . '</b>'
                                        . '</td>'
                                        . '<td>'
                                        . $data["Currency1Title"]
                                        . '</td>'
                                        . '</tr>'
                                        . '<tr class="top-border">'
                                        . '<td style="background:' . $back . '">'
                                        . '<b style="color:' . $color . '">' . Utility::moneyFormat($data["Approved2"]) . '</b>'
                                        . '</td>'
                                        . '<td>'
                                        . $data["Currency2Title"]
                                        . '</td>'
                                        . '</tr>'
                                        . '</table>';
                            }
                        ],
                        [
                            'attribute' => 'Income',
                            'label' => 'Income',
                            'format' => 'raw',
                            'filter' => false,
                            'value' => function($data) {
                                return '<table class="inner-table">'
                                        . '<tr><td width=\'180\'>'
                                        . Utility::moneyFormat($data["Income1"], ($data->currency1->CurrencyID != 1) ? 2 : null)
                                        . '<tr class="top-border"><td >'
                                        . Utility::moneyFormat($data["Income2"], ($data->currency1->CurrencyID != 1) ? 2 : null)
                                        . '</table>';
                            }
                        ],
                        [
                            'attribute' => 'Penalty',
                            'label' => 'Penalty',
                            'format' => 'raw',
                            'filter' => false,
                            'value' => function($data) {
                                return '<table class="inner-table">'
                                        . '<tr><td width=\'180\'>'
                                        . Utility::moneyFormat($data["Penalty1"])
                                        . '<tr class="top-border"><td >'
                                        . Utility::moneyFormat($data["Penalty2"])
                                        . '</table>';
                            }
                        ],
                        [
                            'attribute' => 'LastActionTitle',
                            'label' => 'Last Action',
                            'format' => 'raw',
                            'value' => function($model) {
                                $text = isset($model->processEntity->lastAction->ActionTitle2) ? Html::encode($model->processEntity->lastAction->ActionTitle2) : '';
                                return Utility::generateTextShorterSpan($text, 30, 70);
                            },
                            'filter' => false,
                            'options' => [
                                'width' => '150px'
                            ]
                        ],
                        [
                            'attribute' => 'Description',
                            'format' => 'raw',
                            'filter' => false,
                            'value' => function($data) {
                                return Utility::generateTextShorterSpan($data['Description'], 30, 100);
                            },
                            'options' => [
                                'width' => '150px',
                            ]
                        ],
                        [
                            'attribute' => '',
                            'format' => 'raw',
                            'filter' => false,
                            'value' => function($data) {
                                $resault = '<a title="View detail of Invoice" data-pjax="0" class="btn-invoice-detail" style="color:rgb(162, 41, 161); cursor:pointer" '
                                        . 'ajax-url="' . Yii::$app->urlManager->createAbsoluteUrl(["invoice/ajax/invoice-view-read-only", 'invid' => $data["InvoiceID"]]) . '" onclick="invoicedetail(this)"><i class="fa-search s14"></i></a>';
                                return $resault;
                            },
                                    'options' => [
                                        'width' => '80px',
                                    ]
                                ],
                            ]
                        ]);
                        ?>
                        <?php Pjax::end(); ?> 
                        <div class="row mt10" >
                            <div class="col-lg-12 col-md-12">
                            <?=  app\components\ContractCumulative::widget(['id'=>'contract-invoice-total','cid'=>$invoiceModel->ContractID]);?>
                            </div>
                        </div>

                    <?php PopupBox::begin([]) ?>
                    <?php PopupBox::end([]) ?>
            </div>
        </div>
    </div>
</div>
<script>
                    function invoicedetail(obj) {
                        var url = $(obj).attr('ajax-url');
                        $.ajax({
                            url: url,
                            dataType: "text",
                            type: 'GET',
                        }).done(function (data) {
                            $('.pb-content').html(data);

                            return false;
                        }).error(function () {
                            showMessageBox(
                                    "Error on connecting to server. <br />Plase login again and try later!",
                                    "Error",
                                    "err"
                                    );
                            ajaxProgressLoader('hide', '.grid-box-silver');
                            return false;
                        });
                        showPopupBox('', 'Loading...', '', false);

                        return false;
                    }
                    ;
                    $('#conInvoiceList').on('pjax:send', function () {
                        ajaxProgressLoader('show', '#conInvoiceList')
                    });
                    $('#conInvoiceList').on('pjax:complete', function () {
                        ajaxProgressLoader('hide', '#conInvoiceList')
                    });
                </script>