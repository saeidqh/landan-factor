<?php

use app\components\PageHeader;
use app\components\Utility;
use yii\widgets\Pjax;
use yii\helpers\Html;

$this->title = Yii::t('invoice', 'Invoice Detail');

$canInvoicePkskCreate = app\access\AccessControl::can("invoice/pksk/create", ['peid' => $processEntityModel->ProcessEntityID]);
$canInvoicePkskUpdate = app\access\AccessControl::can("invoice/pksk/update", ['peid' => $processEntityModel->ProcessEntityID]);
$canInvoicePkskDelete = app\access\AccessControl::can("invoice/pksk/delete", ['peid' => $processEntityModel->ProcessEntityID]);
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
        'tab' => '#invoice_pksk_info',
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
                        <table>
                            <tr>
                                <td><?= Yii::t('contract', 'Subcontractor') ?> </td><td><i class="badge badge-orange"><?= Html::encode($invoiceModel->contract->subContractor->PersonTitle); ?></i>&nbsp;|&nbsp;</td>
                                <td><?= Yii::t('contract', 'Contract No') ?></td><td><i class="badge badge-purple"><?= Html::encode($invoiceModel->contract->ContractNo); ?></i>&nbsp;|&nbsp;</td>
                                <td><?= Yii::t('invoice', 'Invoice Number') ?></td><td><i class="badge badge-brown"><?= Html::encode($invoiceModel->Title); ?> </i>&nbsp;|&nbsp;</td>
                                <td><?= Yii::t('invoice', 'Invoice Type') ?></td><td> <i class="badge badge-info"><?= Html::encode($invoiceModel->type->InvoiceType); ?> </i></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="pull-right">
                                <?php
                                if ($canInvoicePkskCreate) {
                                    echo Html::a('<i class="fa-plus"></i> ' . Yii::t('invoice', 'Add New'), '#', [
                                        'data-url' => Yii::$app->urlManager->createAbsoluteUrl(["invoice/pksk/create", 'peid' => $processEntityModel->ProcessEntityID]),
                                        'onclick' => 'showPkskModal(this)',
                                        'class' => 'btn btn-sm btn-success'
                                    ]);
                                }
                                ?>                
                            </div>     
                        </div>
                    </div>

                </div>

                <?=
                kartik\grid\GridView::widget([
                    'id' => 'invoice-pksk-grid',
                    'options' => [
                        'style' => 'font-family:tahoma; font-size:11px;',
                    ],
                    'hover' => true,
                    'pjax' => true,
                    'pjaxSettings' => [
                        'options' => [
                            'id' => 'invoice-pksk-grid-pjax',
                        ]
                    ],
                    'filterModel' => $invoicePkskModel,
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
                                    $curr2 = (isset($model->currency2)) ? Html::encode($model->currency2->CurrencyTitle) : '';
                                    return '<table class="inner-table">'
                                            . '<tr><td width=\'200\'>'
                                            . Utility::moneyFormat($model->CI_Amount1)
                                            . '</td><td class="label-silver">' . Html::encode($model->currency1->CurrencyTitle) . '</td></tr>'
                                            . '<tr class="top-border"><td >'
                                            . Utility::moneyFormat($model->CI_Amount2)
                                            . ' </td><td class="label-silver">' . $curr2 . '</td></tr>'
                                            . '</table>';
                            }
                        ],
                        [
                            'header' => 'actions',
                            'class' => \yii\grid\ActionColumn::className(),
                            'template' => '{edit}{delete}',
                            'buttons' => [
                                'edit' => function($url, $model, $key)use($canInvoicePkskUpdate, $processEntityModel) {
                                    if ($canInvoicePkskUpdate) {
                                        return Html::a('<i class="im-pencil"></i>', '#', [
                                                    'data-url' => Yii::$app->urlManager->createAbsoluteUrl(['invoice/pksk/update', 'peid' => $processEntityModel->ProcessEntityID, 'pkskid' => $model['ID']]),
                                                    'onclick' => 'showPkskModal(this)',
                                                    'data-pjax' => '0',
                                                    'data-toggle' => 'tooltip',
                                                    'data-placement' => 'top',
                                                    'data-original-title' => 'Edit',
                                                        ]
                                        );
                                    }
                                },
                                        'delete' => function($url, $model, $key)use($canInvoicePkskDelete, $processEntityModel) {
                                    if ($canInvoicePkskDelete) {
                                        return Html::a('&nbsp;<i class="br-trashcan"></i>', '#', [
                                                    'data-pjax' => '0',
                                                    'title' => 'Click to delete file',
                                                    'id' => 'delete-pksk-' . $model['ID'],
                                                    'onclick' => 'confirmBox("Are you sure to delete this item?!", "Delete!", "deleteInvoicePksk(' . $model['ID'] . ')");',
                                                    'data-url' => Yii::$app->urlManager->createAbsoluteUrl(["invoice/pksk/delete", 'peid' => $processEntityModel->ProcessEntityID, 'pkskid' => $model['ID']]),
                                                    'data-update-url' => Yii::$app->urlManager->createAbsoluteUrl(['invoice/invoice/view', 'peid' => $processEntityModel->ProcessEntityID]),
                                        ]);
                                    }
                                },
                                    ],
                                ],
                            ],
                        ]);
                        ?>


                        <?php
                        \yii\bootstrap\Modal::begin([
                            'header' => '<h3 class="p0 m0">Invoice PKSK Info</h3>',
                            'id' => 'invoice-pksk-modal',
                        ]);
                        Pjax::begin(['id' => 'invoice-pksk-form-pjax']);
                        $js = "
                        $('#invoice-pksk-form-pjax').on('pjax:complete', function(e,d){
                            if(d.responseText == '1'){
                                $('#invoice-pksk-modal').modal('hide');
                                $.pjax.reload({
                                    container:'#invoice-pksk-grid-pjax'
                                });
                            }
                        });";
                        $this->registerJs($js, yii\web\View::POS_READY);
                        Pjax::end();
                        \yii\bootstrap\Modal::end();
                        ?>

                        <?php
                        // کد نمایش پیغام های کاربر
                        if (Yii::$app->request->isAjax)
                            echo app\components\Utility::showUserMessages(Yii::$app->session->getAllFlashes());
                        ?>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function () {
    $('#invoice-pksk-modal').on('pjax:complete', function (e, d) {
        if (d.responseText == '1') {
            $('#invoice-pksk-modal').modal('hide');
            $.pjax.reload({
                container: '#invoice-pksk-grid-pjax',
            });
            showMsgBoxInBottomRight('PKSK Info saved successfully!', 'success');
        } else if (d.responseText == '0') {
            showMsgBoxInBottomRight('Error in saving data!', 'Error');
        }
    });
});

function showPkskModal(selector) {
    ajaxProgressLoader('show', '.modal-content');
    var url = $(selector).attr('data-url');
    $.ajax({
        url: url,
        dataType: 'html'
    }).done(function (data) {
        $('#invoice-pksk-form-pjax').html(data);
        $('#invoice-pksk-modal').modal('show');
        ajaxProgressLoader('hide', '.modal-content');
    }).error(function () {
        showMsgBoxInBottomRight('Limited Access or error in connecting to server', 'error');
        return false;
    });
}

function deleteInvoicePksk(id) {
    var url = $('#delete-pksk-' + id).attr('data-url');
    var updateUrl = $('#delete-pksk-' + id).attr('data-update-url');
    $.ajax({
        url: url,
    }).done(function (data) {
        if (data.result == 1) {
            $.pjax.reload({
                container: '#invoice-pksk-grid-pjax',
                url: updateUrl,
                timeout: false,
                push: false,
                replace: false,
            });
            setTimeout("showMsgBoxInBottomRight('" + data.message + "', 'success')", 300);
        } else {
            setTimeout("showMsgBoxInBottomRight('" + data.message + "', 'success')", 300);
        }
    }).error(function () {
        showMsgBoxInBottomRight("Error on connecting to server.", "error");
        ajaxProgressLoader('hide', '.popup-box');
        return false;
    });
    return false;
}
</script>