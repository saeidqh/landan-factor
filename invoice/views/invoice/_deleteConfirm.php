<?php
use app\components\Utility;
use yii\widgets\DetailView;
use yii\helpers\Html;
use omnilight\assets\SweetAlertAsset;
SweetAlertAsset::register($this);
$deleteUrl = ['invoice/invoice/delete', "invid" => $invoiceModel->InvoiceID];
?>
<?php
echo DetailView::widget([
    'id' => '',
    'model' => $invoiceModel,
    'attributes' => [
        [
            'label' => 'Invoice Type',
            'value' =>  Html::encode($invoiceModel->type->InvoiceType),
        ],
        [
            'label' => 'Invoice Number',
            'attribute' => 'Title',
        ],
        [
            'label' => 'Revision Number',
            'attribute' => 'RevisionNumber',
        ],
        [
            'label' => 'CutOffDate',
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
            'label' => 'Financial Doc. Number',
            'attribute' => 'financialDocNumber',
        ],
        [
            'label' => 'Financial Doc. Date',
            'attribute' => 'financialDocDateHijri',
        ],
        [
            'label' => 'approved1 USD(F2)',
            'attribute' => 'approved1UsdF2',
            'value' => !empty($invoiceModel->approved1UsdF2) ? Utility::moneyFormat($invoiceModel->approved1UsdF2) : ''
        ],
        [
            'label' => 'approved1 IRR(F2)',
            'attribute' => 'approved1IrrF2',
            'value' => !empty($invoiceModel->approved1IrrF2) ? Utility::moneyFormat($invoiceModel->approved1IrrF2) : ''
        ],
        [
            'label' => 'approved1 USD(F3)',
            'attribute' => 'approved1UsdF3',
            'value' => !empty($invoiceModel->approved1UsdF3) ? Utility::moneyFormat($invoiceModel->approved1UsdF3) : ''
        ],
        [
            'label' => 'approved1 IRR(F3)',
            'attribute' => 'approved1IrrF3',
            'value' => !empty($invoiceModel->approved1IrrF3) ? Utility::moneyFormat($invoiceModel->approved1IrrF3) : ''
        ],
        [
            'label' => 'Description',
            'attribute' => 'Description',
        ],
    ]
]);
echo Html::a('<span class="br-cancel"></span> Cancel', '#', [
    'class' => 'btn btn-sm btn-warning mr10',
    'data-dismiss' => 'modal'
]);
echo Html::a('<span class="fa-trash s16"></span> Delete', '#', [
    'class' => 'btn btn-sm btn-danger',
    'onclick' => 'invoiceDelete(this)',
]);
$js = "
    function invoiceDelete(obj)
    {
        var href = $(obj).attr('href');
        swal({
            title:'Are you sure?',
            text:'Are you sure delete item?!',
            type:'warning',
            showCancelButton:true,
            colseOnConfrim:false,
            showLoaderOnConfrim:true,
        },
        function(){
            $.ajax({
                url:'".Yii::$app->urlManager->createAbsoluteUrl($deleteUrl)."',
            })
            .done(function(data){
                if(data.result){
                    var dt = data.data;
                    swal('delete', data.message, 'success');
                    $('#invoice-delete-modal').modal('hide');
                    $.pjax.reload({
                        container:'#conInvoiceList'
                    });
                }
                else{
                    swal('delete', data.message, 'error');
                }
            });
        });
    }
    ";
$this->registerJs($js, yii\web\View::POS_END);
?>