<?php
use app\components\PageHeader;
use app\modules\invoice\assets\InvoiceListAsset;
use yii\widgets\Pjax;
use yii\helpers\Html;
use app\components\Utility;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use app\assets\DatepickerAsset;

DatepickerAsset::register($this);
$this->title = 'Invoice Detail';


InvoiceListAsset::register($this);
?>
<?=
PageHeader::widget([
    'title' => $this->title,
    'icon' => 'fa-dollar',
//    'breadcrumbs' => $this->breadcrumbs,
]);
?>
<div class="bs-example bs-example-tabs">
    <ul id="myTab" class="nav nav-tabs" role="tablist">

        <?php if ($access[2] === 2 || $access[2] === 3 ): ?><li class="<?= (Yii::$app->request->get('_tab') == 'InvoiceInfo' || Yii::$app->request->get('_tab') == '') ? 'active' : null; ?>"><a href="#InvoiceInfo" role="tab" data-toggle="tab"><i class="fa-info"></i> Invoice Info</a></li><?php endif; ?>
        <?php if ($access[1] === 2 || $access[1] === 3 ): ?><li class="<?= (Yii::$app->request->get('_tab') == 'InvoiceAmount') ? 'active' : null; ?>"><a href="#InvoiceAmount" role="tab" data-toggle="tab"><i class="fa-dollar"></i> Invoice Amount</a></li><?php endif; ?>
        <?php if ($access[0] === 2 || $access[0] === 3 ): ?><li class="<?= (Yii::$app->request->get('_tab') == 'InvoiceDocument') ? 'active' : null; ?>"><a href="#InvoiceDocument" role="tab" data-toggle="tab"><i class="en-docs"></i> Invoice Document</a></li><?php endif; ?>
        <?php if ($invoiceModel->processEntity->ProcessEntityID != null): ?><li class="<?= (Yii::$app->request->get('_tab') == 'contractrevision') ? 'active' : null; ?>"><a href="#contractrevision" role="tab" data-toggle="tab"><i class="en-reply-all"></i> Contract Revisions</a></li> <?php endif; ?>
        <?php if ($invoiceModel->processEntity->ProcessEntityID != null): ?><li class="<?= (Yii::$app->request->get('_tab') == 'contractissuet') ? 'active' : null; ?>"><a href="#contractissuet" role="tab" data-toggle="tab"><i class="im-notification"></i> Contract Issue</a></li> <?php endif; ?>
        <?php if ($invoiceModel->processEntity->ProcessEntityID != null): ?><li class="<?= (Yii::$app->request->get('_tab') == 'contractinvoice') ? 'active' : null; ?>"><a href="#contractinvoice" role="tab" data-toggle="tab"><i class="fa-dollar"></i> Contract Invoices</a></li> <?php endif; ?>
            

    </ul>
    <!--begin-task-->
    <div id="myTabContent" class="tab-content">
        <?php if ($access[2] === 2 || $access[2] === 3 ) { ?>
            <div class="tab-pane fade <?= (Yii::$app->request->get('_tab') == 'InvoiceInfo' || Yii::$app->request->get('_tab') == '') ? 'active in' : null; ?>" id="InvoiceInfo" style="padding:10px 0;">


               <div style="width:100%;padding:5px 0px 5px 0px;background: #ccf;">
                    <div style="padding:5px;background-color: #e4e9eb;font-size: 12px;">
                        Sub Contractor <i class="badge badge-orange"><?= Html::encode($invoiceModel->contract->subContractor->PersonTitle); ?></i> |
                        Contract Number <i class="badge badge-purple"><?= Html::encode($invoiceModel->contract->ContractNo); ?></i> |
                        Contract Subject <i class="badge badge-brown"><?= Html::encode($invoiceModel->contract->ContractSubject); ?> </i>
                    </div>
                </div>



                <div class="panel-body" id="invoice-pjax-container">

                </div>
                <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="pull-right">
                                <?php $canEditInvoice = app\access\AccessControl::can('invoice/maincontractinvoice/update', ['peid' => $invoiceModel->processEntity->ProcessEntityID]); ?>
                                <?php if ($canEditInvoice) { ?>
                                    <button class="btn btn-sm btn-success" id="btn-edit-invoice" data-url="<?= Yii::$app->urlManager->createAbsoluteUrl(['invoice/maincontractinvoice/update', 'peid' => $invoiceModel->processEntity->ProcessEntityID]); ?>">Edit Invoice <i class="fa-edit"></i></button>
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
                                'label' => 'Invoice Type',
                                'value' => $invoiceModel->type->InvoiceType,
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
                                'value' =>  !empty($invoiceModel->approved1UsdF3) ? Utility::moneyFormat($invoiceModel->approved1UsdF3) : ''
                            ],
                            [
                                'label' => 'approved1 IRR(F3)',
                                'attribute' => 'approved1IrrF3',
                                'value' => !empty($invoiceModel->approved1IrrF3) ? Utility::moneyFormat($invoiceModel->approved1IrrF3) :''
                            ],
                            [
                                'label' => 'Description',
                                'attribute' => 'Description',
                            ],
                        ]
                    ]);
                    ?>
                </div>
                <!---------------------------------------------------------------------------->
            </div>
        <?php } ?>
        <?php if ($access[1] === 2 || $access[1] === 3 ) { ?>
            <div class="tab-pane fade <?= (Yii::$app->request->get('_tab') == 'InvoiceAmount') ? 'active in' : null; ?>" id="InvoiceAmount">
                <div class="row InvoiceAmount-grid" style="position:relative;">
                    <?=
                    $this->render('_invoiceAmountList', [
                        'invoiceModel' => $invoiceModel,
                        'invoice_amount_model' => $invoice_amount_model,
                        'dataProvider' => $invoiceAmountDataProvider,
                        'access' => $access,
                    ]);
                    ?> 
                </div>
                
                <div class="row mt10" >
                    <?php
                        echo app\components\ContractCumulative::widget(['id'=>'invoice-amount-total','cid'=>$invoiceModel->ContractID,'hdate'=>$invoiceModel->CutOffDateHijri]);
                    ?>
                </div>
            </div>
        <?php } ?>

        <?php if ($access[0] === 2 || $access[0] === 3) { ?>
            <div class="tab-pane fade <?= (Yii::$app->request->get('_tab') == 'InvoiceDocument') ? 'active in' : null; ?>" id="InvoiceDocument">
                <div class="row InvoiceDocument-grid" style="position:relative;">
                    <?=
                    $this->render('_invoiceDocumentList', [
                        'invoiceDocumentModel' => $invoiceDocumentModel,
                        'invoiceModel' => $invoiceModel,
                        'dataProvider' => $invoiceDocumentDataProvider,
                        'access' => $access,
                    ]);
                    ?> 
        
                </div> 
            </div>  

        <?php } ?>
        <?php if ($invoiceModel->contract->ContractCategory == 13): ?>
            <div class="tab-pane fade <?= (Yii::$app->request->get('_tab') == 'PKSKInfo') ? 'active in' : null; ?>" id="PKSKInfo">
                <div class="row PKSKInfo-grid" style="position:relative;">
                    <?=
                    $this->render('_invoicePKSKInfoList', [
                        'invoicePkskModel' => $invoicePkskModel,
                        'invoiceModel' => $invoiceModel,
                        'dataProvider' => $invoicePkskDataProvider,
                        'access' => $access,
                    ]);
                    ?> 
                </div>
            </div>
        <?php endif; ?>
        <!--contractRevisionsGrid-->
        <?php
        //برای اینویس های ورک فلویی نمایش داده می شود
        if (!empty($invoiceModel->processEntity->ProcessEntityID)):
            ?>
            <div class="tab-pane fade <?= (Yii::$app->request->get('_tab') == 'contractrevision') ? 'active in' : null; ?>" id="contractrevision">
                <div class="row PKSKInfo-grid" style="position:relative;">
                    <?php
                    echo $this->render('_contractRevisionList', [
                        'contractRevisionModel' => $contractRevisionModel,
                        'contractRevisionDataProvider' => $contractRevisionDataProvider,
                        'invoiceModel' => $invoiceModel,
                    ]);
                    ?> 
                </div>
            </div>
        <?php endif; ?>

        <?php
//برای اینویس های ورک فلویی نمایش داده می شود
        if (!empty($invoiceModel->processEntity->ProcessEntityID)):
            ?>
            <div class="tab-pane fade <?= (Yii::$app->request->get('_tab') == 'contractissuet') ? 'active in' : null; ?>" id="contractissuet">
                <div class="row PKSKInfo-grid" style="position:relative;">
                    <?=
                    $this->render('_contractIssueGrid', [
                        'contractIssueModel' => $contractIssueModel,
                        'contractIssueProvider' => $contractIssueProvider,
                        'invoiceModel' => $invoiceModel,
                    ]);
                    ?> 
                </div>
            </div>
        <?php endif; ?>


    </div>
    

</div>

<?php 
//next action
Modal::begin([
    'id' => 'invoice-nextaction-modal',
    'size' => Modal::SIZE_LARGE,
    'header' => '<h4 class="modal-title"><i class="im-arrow-right2"></i> Next action</h4>'
]); 
Pjax::begin(['id' => 'pjax-modal']);
    $js = "
        function showModal(obj){
            var url = $(obj).attr('href');
            $.ajax({
                url: url
            })
            .done(function(data){
                $('#pjax-modal').html(data);
                 $('#invoice-nextaction-modal').modal('show');
            });
            return false;
        }
        $('#pjax-modal').on('pjax:send', function(){
            ajaxProgressLoader('show', '.modal-content');
        });
        
        $('#pjax-modal').on('pjax:complete', function(){
            ajaxProgressLoader('hide', '.modal-content');
        });
        
    ";
    $this->registerJs($js, yii\web\View::POS_END);
Pjax::end();
Modal::end();

?>  
<?php if($todoExists){ ?>
<?= Html::a('<i class="fa-circle-arrow-right rotate-360" style="font-size:48px"></i>', 
    Url::to(['/workflow/next-action/next-action', 'peid' => $invoiceModel->processEntity->ProcessEntityID])
    , ['class' => 'pull-right mb5 animated bounceInLeft next-action',
    'onclick' => 'showModal(this); return false;',
    'data-pjax' => 0,
    'style' => 'color:#24cc42;',
    "data-original-title"=> "Go to next action",
    "data-toggle" => "tooltip",]);
?>
<?php } ?>
<?php
$this->registerCss("
    .next-action:hover{
        opacity:1;
    }
    .next-action{
        position: fixed;
        z-index: 10000000;
        right: 10px;
        bottom: 10px;
        opacity:0.3;
    }
    ");


//letter modal
Modal::begin([
    'id' => 'letter-modal',
    'header' => '<h4 class="modal-title"><span class="fa-envelope"></span> Create letter</h4>',
]);
echo '<div id="leter-content"></div>';
$js = "
     //show CreateLetter modal
    function showModalCreateLetter(obj){
        var url = $(obj).attr('href');
        $.ajax({
            url:url,
            data:{lid:$('#drop-letter').val()}
        })
        .done(function(data){
            $('#leter-content').html(data);
        });
    }
    
";
$this->registerJs($js, yii\web\View::POS_END);
Modal::end();
?>

