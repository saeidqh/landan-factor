<?php

use yii\bootstrap\Modal;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Html;
use omnilight\assets\SweetAlertAsset;
SweetAlertAsset::register($this);

$invoiceDocumentTable= \app\modules\invoice\models\InvoiceDocument::tableName();
$invoiceDocumentModelForCheckAccess = \app\components\ActiveModel::find('\app\modules\invoice\models\InvoiceDocument')
        ->where(['InvoiceIDRef' => $invoiceModel->InvoiceID,])
        ->andWhere("$invoiceDocumentTable.isDeleted is null or $invoiceDocumentTable.isDeleted<>1")
        ->one();
$invoiceDocumentId = (isset($invoiceDocumentModelForCheckAccess)) ? $invoiceDocumentModelForCheckAccess->InvoiceDocumentID : 0;
$canInvoiceDocumentEdit = app\access\AccessControl::can("invoice/maincontractdocument/update", ['peid' => $invoiceModel->processEntity->ProcessEntityID,'invdid' => $invoiceDocumentId]);
$canInvoiceDocumentDelete = app\access\AccessControl::can("invoice/maincontractdocument/delete", ['peid' => $invoiceModel->processEntity->ProcessEntityID,'invdid' => $invoiceDocumentId]);
$canInvoiceDocumentDownload =app\access\AccessControl::can("invoice/maincontractdocument/download", ['peid' => $invoiceModel->processEntity->ProcessEntityID,'docid' => $invoiceDocumentId]);
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
            <div class="col-lg-10 col-md-10">
                <div id="new-invoiceDocument-container">
                    <!-- content Ajax Amount-Form-->   
                </div>
            </div> 
            <div class="col-lg-2 col-md-2">
                <div class="pull-right">
                    <?php
                    $canCreateMaincontractInvoiceDocument = \app\access\AccessControl::can('invoice/maincontractdocument/create',['peid' => $invoiceModel->processEntity->ProcessEntityID]);
                    if ($canCreateMaincontractInvoiceDocument) {
                        echo Html::a('<i class="im-attachment"></i> New Document', '#', [
                            'data-url' => Yii::$app->urlManager->createAbsoluteUrl(["invoice/maincontractdocument/create", 'peid' => $invoiceModel->processEntity->ProcessEntityID]),
                            'onclick' => 'showDocumentModal(this)',
                            'class' => 'btn btn-sm btn-success'
                        ]);
                    }
                    ?>
                </div>          
            </div>
        </div>
    </div>
</div> 
<?php
Pjax::begin([
    'id' => 'invoice-document-pjax'
]);
?>
<?=
GridView::widget([
    'id' => 'tbl-invoice-grid3',
    'filterModel' => $invoiceDocumentModel,
    'dataProvider' => $dataProvider,
    'columns' => [
        [
            'format' => 'raw',
            'attribute' => 'InvoiceDocumentID',
            'label' => 'ID',
            'options' => ['width' => '50px'],
        ],
        [
            'format' => 'raw',
            'attribute' => 'documentType',
            'value' => 'type.InvoiceDocumentTypeTitle',
            'label' => 'Document Type',
            'options' => ['width' => '100px'],
        ],
        'FileName',
        'Description',
        [
            'format' => 'raw',
            'attribute' => 'userName',
//            'value' => 'user.UserName',
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
                'download' => function($url, $data, $key)use($canInvoiceDocumentDownload) {

            if ($canInvoiceDocumentDownload) {
                return Html::a('<i class="fa-arrow-down"></i>', $url = Yii::$app->urlManager->createUrl(["invoice/maincontractdocument/download",  'peid'=>$data->invoice->processEntity->ProcessEntityID,"docid" => $data["InvoiceDocumentID"]]), [
                            'data-pjax' => '0',
                            'title' => 'Click to download file',
                ]);
            }
        },  
                'edit' => function($url, $data, $key)use($canInvoiceDocumentEdit) {
                    if ($canInvoiceDocumentEdit) {
                        return Html::a('&nbsp;<i class="im-pencil"></i>', '#', [
                                    'data-pjax' => '0',
                                    'title' => 'Click to download file',
                                    'data-url' => Yii::$app->urlManager->createAbsoluteUrl(["invoice/maincontractdocument/update", 'peid'=>$data->invoice->processEntity->ProcessEntityID, 'invdid' => $data['InvoiceDocumentID']]),
                                    'onclick' => 'showDocumentModal(this)',
                        ]);
                    }
                },
                'delete' => function($url, $data, $key)use($canInvoiceDocumentDelete) {
                    if ($canInvoiceDocumentDelete || ($data['UserIDRef'] == Yii::$app->session->get('UserID'))) {
                            return Html::a('&nbsp;<i class="br-trashcan"></i>', '#', [
                                        'data-toggle' => 'tooltip',
                                        'data-placement' => 'left',
                                        'title' => 'Click to delete file',
                                        'class' => 'delete-document-' . $data['InvoiceDocumentID'],
                                        'onclick' => 'confirmBox("Are you sure to delete this item?!", "Delete!", "deleteInvoiceDocument(' . $data['InvoiceDocumentID'] . ')");',
                                        'data-url' => Yii::$app->urlManager->createUrl(["invoice/maincontractdocument/delete", 'peid'=>$data->invoice->processEntity->ProcessEntityID,'invdid' => $data['InvoiceDocumentID']]),
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


