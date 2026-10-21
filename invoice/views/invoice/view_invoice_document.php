<?php
use app\components\PageHeader;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use app\modules\invoice\assets\InvoiceListAsset;
InvoiceListAsset::register($this);
$this->title = Yii::t('invoice','Invoice Detail');

$canInvoiceDocumentCreate = app\access\AccessControl::can("invoice/document/create", ['peid' => $processEntityModel->ProcessEntityID]);
$canInvoiceDocumentUpdate = app\access\AccessControl::can("invoice/document/update", ['peid' => $processEntityModel->ProcessEntityID]);

$canInvoiceDocumentDelete = app\access\AccessControl::can("invoice/document/delete", ['peid' => $processEntityModel->ProcessEntityID]);

$canInvoiceDocumentDownload = app\access\AccessControl::can("invoice/document/download", ['peid' => $processEntityModel->ProcessEntityID]);
?>
<?= PageHeader::widget([
    'title' => $this->title,
    'icon' => 'fa-dollar',
]);
?>
<div class="bs-example bs-example-tabs">
    <?= $this->render('_view_tab_links', [
        'tab' => '#invoice_document',
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
                                <td><?= Yii::t('invoice', 'Invoice Type') ?></td><td> <i class="badge badge-info"><?= Html::encode($invoiceModel->type->InvoiceType); ?> </i></td>
                            </tr>
                        </table>

                    </div>
                </div>
                <div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="col-lg-10 col-md-10">
                                <div id="new-invoiceDocument-container">
                                    <!-- content Ajax Amount-Form-->   
                                </div>
                            </div> 
                            
                        </div>
                    </div>
                    <div class="text-right" style="padding: 8px 0 4px 0">
                        <?php
                            if ($canInvoiceDocumentDownload) {
                                echo Html::a('<i class="fa-arrow-down"></i> ' . Yii::t('invoice', 'Download All Document'), Yii::$app->urlManager->createAbsoluteUrl(["invoice/document/download-all", 'peid' => $processEntityModel->ProcessEntityID]), [
                                    'data-url' => Yii::$app->urlManager->createAbsoluteUrl(["invoice/document/download-all", 'peid' => $processEntityModel->ProcessEntityID]),
                                    'class' => 'btn btn-sm btn-info',
                                ]);
                            }
                        ?>
                        
                        <?php
                            if ($canInvoiceDocumentCreate) {
                                echo Html::a('<i class="im-attachment"></i> ' . Yii::t('invoice', 'New Document'), '#', [
                                    'data-url' => Yii::$app->urlManager->createAbsoluteUrl(["invoice/document/create", 'peid' => $processEntityModel->ProcessEntityID]),
                                    'onclick' => 'showDocumentModal(this)',
                                    'class' => 'btn btn-sm btn-success'
                                ]);
                            }
                        ?>
                    </div>
                </div> 
                <?php
                Pjax::begin([
                    'id' => 'invoice-document-pjax'
                ]);
                ?>
                <?= GridView::widget([
                    'id' => 'tbl-invoice-grid3',
                    'filterModel' => $invoiceDocumentModel,
                    'dataProvider' => $invoiceDocumentDataProvider,
                    'columns' => [
                        [
                            'format' => 'raw',
                            'attribute' => 'InvoiceDocumentID',
                            'options' => ['width' => '50px'],
                        ],
                        [
                            'format' => 'raw',
                            'attribute' => 'documentType',
                            'value' => 'type.InvoiceDocumentTypeTitle',
                            'options' => ['width' => '100px'],
                        ],
                        'FileName',
                        'originalNumber',
                        'copyNumber',
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
                        ],
                        array(
                            'class' => \yii\grid\ActionColumn::className(),
                            'template' => '{download}{edit}{delete}',
                            'buttons' => array(
                                'download' => function($url, $data, $key)use($canInvoiceDocumentDownload, $processEntityModel) {

                                    if ($canInvoiceDocumentDownload) {
                                        return Html::a('<i class="fa-arrow-down"></i>', $url = Yii::$app->urlManager->createUrl(["invoice/document/download", 'peid' => $processEntityModel->ProcessEntityID,"docid" => $data["InvoiceDocumentID"]]), [
                                                    'data-pjax' => '0',
                                                    'title' => 'Click to download file',
                                        ]);
                                    }
                                },
                                'edit' => function($url, $data, $key)use($canInvoiceDocumentUpdate,$processEntityModel) {
                                    if ($canInvoiceDocumentUpdate) {
                                        return Html::a('&nbsp;<i class="im-pencil"></i>', '#', [
                                                    'data-pjax' => '0',
                                                    'title' => 'Click to download file',
                                                    'data-url' => Yii::$app->urlManager->createAbsoluteUrl(["invoice/document/update",'peid' => $processEntityModel->ProcessEntityID, 'invdid' => $data['InvoiceDocumentID']]),
                                                    'onclick' => 'showDocumentModal(this)',
                                        ]);
                                    }
                                },
                                'delete' => function($url, $data, $key)use($canInvoiceDocumentDelete, $processEntityModel) {
                                    if ($canInvoiceDocumentDelete || ($data['UserIDRef'] == Yii::$app->session->get('UserID'))) {
                                        return Html::a('&nbsp;<i class="br-trashcan"></i>', '#', [
                                                    'data-toggle' => 'tooltip',
                                                    'data-placement' => 'left',
                                                    'title' => 'Click to delete file',
                                                    'class' => 'delete-document-' . $data['InvoiceDocumentID'],
                                                    'onclick' => 'confirmBox("Are you sure to delete this item?!", "Delete!", "deleteInvoiceDocument(' . $data['InvoiceDocumentID'] . ')");',
                                                    'data-url' => Yii::$app->urlManager->createUrl(["invoice/document/delete", 'peid' => $processEntityModel->ProcessEntityID,'invdid' => $data['InvoiceDocumentID']]),
                                        ]);
                                    } else {
                                        return Html::a('&nbsp;<i class="br-trashcan"></i>', '#', [
                                                    'data-toggle' => 'tooltip',
                                                    'data-placement' => 'left',
                                                    'title' => 'Limited Access',
                                        ]);
                                    }
                                },
                            ),
                        ),
                    ],
                ]);
                ?>
                <?php Pjax::end(); ?>

                <?php
                Modal::begin([
                    'id' => 'document-modal',
                ]);
                ?>
                <?php Pjax::begin(['id' => 'invoice-document-form-pjax']); ?>
                <?php
                $js = "
                    function showDocumentModal(selector){
                        ajaxProgressLoader('show', '.modal-content');
                         var url = $(selector).attr('data-url');
                         $.ajax({
                             url: url,
                             dataType: 'html'
                         }).done(function(data) {
                             $('#invoice-document-form-pjax').html(data);
                             $('#document-modal').modal('show');
                             ajaxProgressLoader('hide', '.modal-content');
                         }).error(function() {
                             showMsgBoxInBottomRight('Limited Access or error in connecting to server','error');
                             return false;
                         });
                    }
                    function callbackPjax()
                    {
                        ajaxProgressLoader('hide', '#document-form-pjax');
                        $('#document-modal').modal('hide');
                        $.pjax.reload({
                            container:'#invoice-document-pjax',
                            timeout: false,
                            push:false,
                            replace:false,
                        });
                        swal({
                            title:'successfully',
                            type:'success',
                            text:'success',
                            timer:1000,
                            showConfirmButton:false,
                        });
                    }

                    $('#invoice-document-form-pjax').on('pjax:send', function(){
                        $('#btnSaveInvoiceAmount').hide();
                        ajaxProgressLoader('show', '#invoice-document-form-pjax');
                    });
                    $('#invoice-document-form-pjax').on('pjax:complete', function(){
                        ajaxProgressLoader('hide', '#invoice-document-form-pjax');
                    });
                ";
                $this->registerJs($js, \yii\web\View::POS_END);
                ?>
                <?php Pjax::end(); ?>
                <?php Modal::end(); ?>
            </div>
        </div>
    </div>
</div>
