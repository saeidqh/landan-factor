<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<script>
//input_AfterUpdate
    $("input[type='text']").blur(function () {
        if ($(this).attr('readonly') == 'readonly')
            return false;
        var money = moneyFormat($(this).val());
        $(this).val(money);
        comulative($(this).val(), $(this).attr('name'));
    });
//loading
    $('#invoiceAmountForm-pjax').on('pjax:send', function () {
        ajaxProgressLoader('show', '#invoiceAmountForm-pjax');
    });

    $('#invoiceAmountForm-pjax').on('pjax:complete', function () {
        ajaxProgressLoader('hide', '#invoiceAmountForm-pjax');
    });
//end loading
    handleInputMoneyFormat();

    if ("<?= !empty($modelAmount->OwnerContractIDRef) ? $modelAmount->OwnerContractIDRef : null ?>" == '' || "<?= !empty($modelAmount->OwnerContractIDRef) ? $modelAmount->OwnerContractIDRef : null ?>" == '0') {
        $('.box-conflict-income').hide();
    }
    $('#invoiceamountedit-amounttype').change(function () {
        if ($(this).val() == 3)
        {
            $('#invoiceamountedit-amountrequested1').val('');
            $('#invoiceamountedit-requested1cumulative').val('');
            $('#invoiceamountedit-amountrequested1').attr('readonly', 'readonly');
            $('#invoiceamountedit-requested1cumulative').attr('readonly', 'readonly');
        }
        else {
            $('#invoiceamountedit-amountrequested1').removeAttr('readonly');
            $('#invoiceamountedit-requested1cumulative').removeAttr('readonly');
        }
    });
//محاسبه فیلد استراتژی
    $('#invoiceamountedit-amountapproved1').focusout(function () {
        if ($('#text-strategy').val() != '' && $('#invoiceamountedit-amountapproved1').val() != '')
        {
            var AmountAppreoved = $(this).val();
            AmountAppreoved = parseInt(AmountAppreoved.replace(/,/g, ''));
            var strategy = $('#text-strategy').val();
            strategy = strategy.replace('%', '');
            strategy = strategy / 100;
            $('#income-strategy1').val(moneyFormat(strategy * AmountAppreoved));
        }
        else {
            $('#income-strategy1').val('0');
        }
    });
//برای رفع اشکال در مخفی شدن دراپ دوان
    $('body').keydown(function (e) {
        if (e.keyCode == 27)
        {
            $('.select2-dropdown.select2-dropdown--below').hide();
        }
    });
//محاسبه کامولیتیوها بصورت خودکار درلود صفحه
    var amountrequested1Obj = $('#invoiceamountedit-amountrequested1');
    comulative(amountrequested1Obj.val(), amountrequested1Obj.attr('name'));

    var amountapproved1Obj = $('#invoiceamountedit-amountapproved1');
    comulative(amountapproved1Obj.val(), amountapproved1Obj.attr('name'));

    if ('<?= $modelAmount->Currency2 ?>' != '0' && '<?= $modelAmount->Currency2 ?>' != '')//محاسبه درصورت دونرخی بودن
    {
        var amountrequested1Obj = $('#invoiceamountedit-amountrequested2');
        comulative(amountrequested1Obj.val(), amountrequested1Obj.attr('name'));

        var amountapproved1Obj = $('#invoiceamountedit-amountapproved2');
        comulative(amountapproved1Obj.val(), amountapproved1Obj.attr('name'));
    }

    function comulative(enteredValue, name) {
        if (enteredValue == '') {
            return false;
        }
        //invoice id
        var invoiceId = $('#invoiceamountedit-invoiceid').val();

        //amount id
        var amountId = $('#invoiceamountedit-id').val();

        //amount type
        var amountType = $('#invoiceamountedit-amounttype').val();

        //node id or cost code
        var nodeId = $('#invoiceamountedit-costcode').val();

        //form status mean 'edit' or 'new'
        var formState = $('#formState').val();

        //Owner Contract id
        var ownerContractId = $('#input-contractid').val();

        var amountTitle;
        var id;
        if (name == "InvoiceAmountEdit[AmountRequested1]") {
            amountTitle = "requested1";
            id = 'invoiceamountedit-requested1cumulative';
        }
        else if (name == "InvoiceAmountEdit[AmountRequested2]") {
            amountTitle = "requested2";
            id = 'invoiceamountedit-requested2cumulative';
        }
        else if (name == "InvoiceAmountEdit[Approved1cumulative]") {
            amountTitle = "approvedcum1";
            id = 'invoiceamountedit-amountapproved1';
        }
        else if (name == "InvoiceAmountEdit[AmountApproved1]") {
            amountTitle = "approved1";
            id = 'invoiceamountedit-approved1cumulative';
        }
        else if (name == "InvoiceAmountEdit[AmountApproved2]") {
            amountTitle = "approved2";
            id = 'invoiceamountedit-approved2cumulative';
        }
        else if (name == "InvoiceAmountEdit[Requested1cumulative]") {
            amountTitle = "requestedcum1";
            id = 'invoiceamountedit-amountrequested1';
        }
        else if (name == "InvoiceAmountEdit[Requested2cumulative]") {
            amountTitle = "requestedcum2";
            id = 'invoiceamountedit-amountrequested2';
        }
        else if (name == "InvoiceAmountEdit[Approved2cumulative]") {
            amountTitle = "approvedcum2";
            id = 'invoiceamountedit-amountapproved2';
        }
        else if (name == "InvoiceAmountEdit[Income1cumulative]") {
            amountTitle = "incomecum1";
            id = 'invoiceamountedit-income1';
        }
        else if (name == "InvoiceAmountEdit[Income2cumulative]") {
            amountTitle = "incomecum2";
            id = 'invoiceamountedit-income2';
        }
        else if (name == "InvoiceAmountEdit[Income1]") {
            amountTitle = "income1";
            id = 'invoiceamountedit-income1cumulative';
        }
        else if (name == "InvoiceAmountEdit[Income2]") {
            amountTitle = "income2";
            id = 'invoiceamountedit-income2cumulative';
        }

        //enteredValue
        enteredValue = enteredValue.replace(/,/g, '');
        var value = enteredValue;
        if (value != '') {
            $.ajax({
                url: '<?= Yii::$app->urlManager->createAbsoluteUrl(["invoice/ajax/convert-invoice-amount"]); ?>',
                data: {
                    invid: invoiceId,
                    Id: amountId,
                    amountType: amountType,
                    nodeId: nodeId,
                    enteredValue: value,
                    formStatus: formState,
                    ownerContractId: ownerContractId,
                    amountTitle: amountTitle
                },
                success: function (dt) {
                    $('#' + id).val(moneyFormat(dt));
                }
            });
        }
    }
</script> 
<div class="panel panel-default" style="margin-bottom: 10px;">

    <div class="panel-body" style="margin: 0px;border: 1px solid #C7C7C7 !important;padding-bottom: 5px;">
        <?php
        $form = ActiveForm::begin([
                    'id' => 'amountform',
                    'enableClientValidation' => false,
                    'options' => [
                        'class' => 'form-horizontal',
                        'data-pjax' => "1"
                    ]
        ]);
        ?>
        <?=
        Html::hiddenInput("formState", $formState, [
            'id' => 'formState',
        ]);
        ?>
<?= Html::activeHiddenInput($modelAmount, "ID"); ?>
<?= Html::activeHiddenInput($modelAmount, "InvoiceID") ?>
<?= Html::activeHiddenInput($modelAmount, "CostCode") ?>

        <div class="row">
            <div class="col-lg-4 col-md-4">
                <div class="col-lg-4 col-md-4" style="padding:0px;">
                    <label for="invoice-number">Invoice Number</label>
                </div>
                <div class="col-lg-8 col-md-8" style="padding:0px;">
<?= Html::textInput("Title", $modelAmount->invoice->Title, ['class' => 'form-control input-sm', 'disabled' => 'disabled', 'invoice-number']) ?>
                </div>
            </div>
            <div class="col-lg-4 col-md-4">
                <div class="col-lg-4 col-md-4" style="padding:0px;">
                    <label for="cutofdate">Cut Of Date</label>
                </div>
                <div class="col-lg-8 col-md-8" style="padding:0px;">
                    <input type="text" readonly="readonly" value="<?= $modelAmount->invoice->CutOffDateHijri ?>" class="form-control input-sm" id="cutofdate">
                </div>
            </div>
            <div class="col-lg-4 col-md-4">
<?=
$form->field($modelAmount, 'AmountType', [
    'template' => '<div class="col-lg-4 col-md-4" style="padding:0px;">{label}</div><div class="col-lg-8 col-md-8" style="padding:0px;">{input}</div>'
])->dropDownList($amountTypeArray, ['class' => 'form-control input-sm']);
?>
            </div>
        </div><hr style="padding:0px;margin-top: 5px;">

        <div class="row">
            <div class="col-lg-12 col-md-12">
                <?php
                $js = "
            function formatRepo(repo){
                if(repo.loading)
                {
                    return repo.text;
                }
                var str = repo.text;
                str = str.split('|');
                var markup = '<div class=\"container-fluid\"><div><span class=\"cando-handshake s16\"></span></div><div class=\"row\">'+

                    '<div class=\"col-lg-4 col-md-4\">'+
                        '<div class=\"col-sm-12\">CostCode : '+str[2]+'</div>'+
                        '<div class=\"col-sm-12\">ContractNO : '+str[5]+'</div>'+
                        '<div class=\"col-sm-12\">SubContractor : '+str[6]+'</div>'+
                    '</div>'+
                    '<div class=\"col-lg-4 col-md-4\">'+
                        '<div class=\"col-sm-12\">level1 : <span style=\"color:#fba113;\">'+str[9]+'</span></div>'+
                        '<div class=\"col-sm-12\">level2 : <span style=\"color:#fba113;\">'+str[10]+'</span></div>'+
                        '<div class=\"col-sm-12\">level3 : <span style=\"color:#fba113;\">'+str[11]+'</span></div>'+
                        '<div class=\"col-sm-12\">level4 : <span style=\"color:#fba113;\">'+str[12]+'</span></div>'+
                    '</div>'+
                    '<div class=\"col-lg-4 col-md-4\">'+
                        '<div class=\"col-sm-12\">level5 : <span style=\"color:#fba113;\">'+str[13]+'</span></div>'+
                        '<div class=\"col-sm-12\">level6 : <span style=\"color:#fba113;\">'+str[14]+'</span></div>'+
                        '<div class=\"col-sm-12\">level7 : <span style=\"color:#fba113;\">'+str[15]+'</span></div>'+
                        '<div class=\"col-sm-12\">level8 : <span style=\"color:#fba113;\">'+str[16]+'</span></div>'+
                    '</div>'+
                '</div></div>';
                return '<div style=\"owerflow:hidden\">'+markup+'</div>';
            }
            function formatRepoSelection(repo){
                var str = repo.text;
                if(str == '--Select CostCode --'){
                    return str;
                }
                str = str.split('|');
                if(str[3] != ''){
                    $('#text-strategy').val(parseInt(str[3])*100+'%');
                }
                if($('#text-strategy').val() != '' && $('#invoiceamountedit-amountapproved1').val() != '')
                {
                    var AmountAppreoved = $('#invoiceamountedit-amountapproved1').val();
                    AmountAppreoved = parseInt(AmountAppreoved.replace(/,/g,''));
                    var strategy = $('#text-strategy').val();
                    strategy = strategy.replace('%', '');
                    $('#income-strategy1').val(moneyFormat(((strategy/100)*AmountAppreoved)));
                }
                else{
                    $('#income-strategy1').val('0');
                }
                return str[2];
            }
        ";
                $this->registerJs($js, \yii\web\View::POS_HEAD);
                ?>
                <?=
                $form->field($modelAmount, 'uniqueNodeID')->widget(kartik\widgets\Select2::className(), [
                    'data' => yii\helpers\ArrayHelper::map($costCodeArray, 'NodeID', 'NodeFullCode'),
                    'options' => ['prompt' => '--Select CostCode --'],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'escapeMarkup' => new yii\web\JsExpression('function(markup){return markup;}'),
                        'templateResult' => new yii\web\JsExpression('formatRepo'),
                        'templateSelection' => new yii\web\JsExpression('formatRepoSelection')
                    ],
                    'pluginEvents' => [
                        'select2:select' => "function(e){
                        var text = $('option:selected').text();
                        console.log(text);
                        str = text.split('|');
                        $('#invoiceamountedit-costcode').val(str[1]);
                        //str[8] OwnerContractIDRef
                        if(str[8] != '" . $modelAmount->invoice->contract->ContractID . "')
                        {
                            $('#box-owner-excuter').fadeIn(1000);
                            $('.box-conflict-income').fadeIn(1000);
                            $('#text-contract-no').val(str[5]);//ContractNO
                            $('#text-sub-contractor').val(str[6]);//subcontractor title
                            $('#text-contract-subject').val(str[7]);//contract subject
                            $('#input-contractid').val(str[8]);//OwnerContractIDRef
                            var strg = str[3]*100;
                            $('#text-strategy').val(Math.round(strg)+'%');
                        }else{
                            $('.box-conflict-income').hide();
                            $('#box-owner-excuter').hide();
                            $('#input-contractid').val('');
                        }
                    }",
                        'select2:unselect' => "function(){
                        $('#invoiceamountedit-costcode').val('');
                    }",
                    ]
                ])->label('Costcode');
                ?> 
            </div>

        </div><hr style="padding:0px;margin:0px;">  

        <div class="row">
            <div class="col-lg-12 col-md-12">
                <table class="table table-striped">
                    <tbody>
                        <tr style="border:none;">
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <span style="border:none;text-align: center;color:black;"><h5>Requested</h5></span>
                        </div>

                        <div class="col-lg-6 col-md-6">
                            <span style="border:none;text-align: center;color:black;"><h5>Approved</h5></span>
                        </div>
                        </tr>
                        <tr>
                            <td>
                                periodic
                                <?=
                                $form->field($modelAmount, "AmountRequested1", [
                                    'template' => '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ui-sortable">{input}<i class="s12 right-input-icon">' . Html::encode($modelAmount->currency1->CurrencyTitle) . '</i></div>{error}'
                                ])->textInput(['class' => 'form-control input-sm right-icon money-input', 'tabindex' => '100', 'readonly' => ($modelAmount->AmountType == 3) ? true : false]);
                                ?>

                            </td>
                            <td>
                                cumulative
<?=
$form->field($modelAmount, "Requested1cumulative", [
    'template' => '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ui-sortable" >{input}<i class="s12 right-input-icon">' . Html::encode($modelAmount->currency1->CurrencyTitle) . '</i></div>{error}'
])->textInput(['class' => 'form-control input-sm right-icon money-input', 'tabindex' => '106', 'readonly' => ($modelAmount->AmountType == 3) ? true : false]);
?>

                            </td>

                            <td>
                                periodic
<?=
$form->field($modelAmount, "AmountApproved1", [
    'template' => '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ui-sortable">{input}<i class="s12 right-input-icon">' . Html::encode($modelAmount->currency1->CurrencyTitle) . '</i></div>{error}'
])->textInput(['class' => 'form-control input-sm right-icon money-input', 'tabindex' => '102']);
?>
                            </td>
                            <td>
                                <br>
                                cumulative
                                <?=
                                $form->field($modelAmount, "Approved1cumulative", [
                                    'template' => '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ui-sortable">{input}<i class="s12 right-input-icon">' . Html::encode($modelAmount->currency1->CurrencyTitle) . '</i></div>{error}'
                                ])->textInput(['class' => 'form-control input-sm right-icon money-input', 'tabindex' => '107']);
                                ?>
                                <?= $form->field($modelAmount, 'Approved1cumulative')->begin(); ?>

                            </td>
                            <td>



                        </tr>

                                <?php if (!empty($modelAmount->Currency2)): ?>
                            <tr>

                                <td>
                                    <?=
                                    $form->field($modelAmount, "AmountRequested2", [
                                        'template' => '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ui-sortable">{input}<i class="s12 right-input-icon">' . Html::encode($modelAmount->currency2->CurrencyTitle) . '</i></div>{error}'
                                    ])->textInput(['class' => 'form-control input-sm right-icon money-input', 'tabindex' => '101']);
                                    ?>   
                                </td>
                                <td>
                            <?=
                            $form->field($modelAmount, "Requested2cumulative", [
                                'template' => '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ui-sortable">{input}<i class="s12 right-input-icon">' . Html::encode($modelAmount->currency2->CurrencyTitle) . '</i></div>{error}'
                            ])->textInput(['class' => 'form-control input-sm right-icon money-input']);
                            ?>  
                                </td>

                                <td>
    <?=
    $form->field($modelAmount, "AmountApproved2", [
        'template' => '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ui-sortable">{input}<i class="s12 right-input-icon">' . Html::encode($modelAmount->currency2->CurrencyTitle) . '</i></div>{error}'
    ])->textInput(['class' => 'form-control input-sm right-icon money-input', 'tabindex' => '104']);
    ?> 
                                </td>
                                <td>
    <?=
    $form->field($modelAmount, "Approved2cumulative", [
        'template' => '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ui-sortable">{input}<i class="s12 right-input-icon">' . Html::encode($modelAmount->currency2->CurrencyTitle) . '</i></div>{error}'
    ])->textInput(['class' => 'form-control input-sm right-icon money-input']);
    ?> 

                                </td>
                                <td>

                            </tr>
<?php endif; ?>
                        </tbody>

                </table>
                <div class="row" id="box-owner-excuter" style="display:<?= (!empty($modelAmount->OwnerContractIDRef) && $modelAmount->OwnerContractIDRef != 0) ? 'block' : 'none' ?>;"><!--box owner and excuter-->

                    <div class="col-lg-6 col-md-6">
                        <div class="panel panel-default">
                            <div class="panel-heading" style="background-color: #eee;border-color: #ddd;padding: 0px;border: 1px solid #ccc;">
                                Owner
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group field-invoiceamountedit-invoiceid" style="margin-bottom: 0px;">    
                                            <div class="col-lg-4 col-md-4">
                                                <span class="text-lime">Contract NO</span>    
                                            </div>
                                            <div class="col-lg-8 col-md-8">
                                                <input type="text" class="form-control input-sm" id="text-contract-no" value="<?= !empty($modelAmount->owner->ContractNo) ? Html::encode($modelAmount->owner->ContractNo) : null ?>" readonly="readonly">
                                                <div class="help-block help-block-error"></div>    
                                            </div>
                                        </div>
                                    </div>  
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group field-invoiceamountedit-invoiceid" style="margin-bottom: 0px;">    
                                            <div class="col-lg-4 col-md-4">
                                                <span class="text-lime">Sub Contractor</span>    
                                            </div>
                                            <div class="col-lg-8 col-md-8">
                                                <input type="text" class="form-control input-sm" id="text-sub-contractor" disabled="disabled" value="<?= !empty($modelAmount->owner->subContractor) ?  Html::encode($modelAmount->owner->subContractor->PersonTitle) : null ?>">
                                                <div class="help-block help-block-error"></div>    
                                            </div>
                                        </div> 
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group field-invoiceamountedit-invoiceid" style="margin-bottom: 0px;">    
                                            <div class="col-lg-4 col-md-4">
                                                <span class="text-lime">Contract Subject</span>    
                                            </div>
                                            <div class="col-lg-8 col-md-8">
                                                <input type="text" class="form-control input-sm" id="text-contract-subject" disabled="disabled" value="<?= !empty($modelAmount->owner->ContractSubject) ?  Html::encode($modelAmount->owner->ContractSubject) : null ?>">
                                                <div class="help-block help-block-error"></div>    
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="panel panel-default">
                            <div class="panel-heading" style="background-color: #eee;border-color: #ddd;padding: 0px;border: 1px solid #ccc;">
                                Executer
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group field-invoiceamountedit-invoiceid" style="margin-bottom: 0px;">    
                                            <div class="col-lg-4 col-md-4">
                                                <span class="text-lime" for="">Contract NO</span>    
                                            </div>
                                            <div class="col-lg-8 col-md-8">
                                                <input type="text" class="form-control input-sm" value="<?=  Html::encode($modelAmount->invoice->contract->ContractNo) ?>" readonly="readonly">
                                                <div class="help-block help-block-error"></div>    
                                            </div>
                                        </div>
                                    </div>  
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group field-invoiceamountedit-invoiceid" style="margin-bottom: 0px;">    
                                            <div class="col-lg-4 col-md-4">
                                                <span class="text-lime" >Sub Contractor</span>    
                                            </div>
                                            <div class="col-lg-8 col-md-8">
                                                <input type="text" class="form-control input-sm" value="<?=  Html::encode($modelAmount->invoice->contract->subContractor->PersonTitle) ?>" readonly="readonly">
                                                <div class="help-block help-block-error"></div>    
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group field-invoiceamountedit-invoiceid" style="margin-bottom: 0px;">    
                                            <div class="col-lg-4 col-md-4">
                                                <span class="text-lime">Contract Subject</span>    
                                            </div>
                                            <div class="col-lg-8 col-md-8">
                                                <input type="text" class="form-control input-sm" value=" <?=  Html::encode($modelAmount->invoice->contract->ContractSubject) ?> " readonly="readonly">
                                                <div class="help-block help-block-error"></div>    
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!--\end of row-->
                <div class="row box-conflict-income" style="display:<?= (!empty($modelAmount->OwnerContractIDRef) && $modelAmount->OwnerContractIDRef != 0) ? 'block' : 'none' ?>;">
                    <div class="col-lg-12 col-md-12"><h4 class="alert-danger text-center s16">Conflict! Income For Executer and debt for Owner</h4></div>
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="col-lg-3 col-md-3 col-sm-3" style="padding-top: 15px;">
                            <div class="form-group" style="margin-bottom:0px;">    
                                <div class="col-lg-4 col-md-4" col-sm-4>
                                    <span class="text-lime" for="">Strategy</span>    
                                </div>
                                <div class="col-lg-8 col-md-8 col-sm-8">
                                    <input type="text" class="form-control input-sm" id="text-strategy" disabled="disabled" value="">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-1 col-md-1 col-sm-1" style="padding-top: 10px;">
                            <h6>Income</h6>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2" style="padding: 0px;">
                            <div class="form-group">    
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <span class="text-lime" for="">Strategy</span>    
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm12">
                                    <div class="form-group" style="margin-bottom:0px;">
                                        <input type="text" class="form-control input-sm" value="" id="income-strategy1" disabled="disabled">
                                        <div class="help-block help-block-error"></div>
                                    </div>
                                    <?php if (!empty($modelAmount->Currency2)): ?>
                                        <div class="form-group" style="margin-bottom:0px;">
                                            <input type="text" class="money-input form-control input-sm" value="" id="income-strategy2" disabled="disabled">
                                            <div class="help-block help-block-error"></div>
                                        </div>
<?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3" style="padding: 0px;">
                            <div class="form-group" style="margin-bottom:0px;">    
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <span class="text-lime" for="">Periodic</span>    
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12">

                                    <?=
                                    $form->field($modelAmount, "Income1", [
                                        'template' => '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ui-sortable">{input}<i class="s12 right-input-icon">' .  Html::encode($modelAmount->currency1->CurrencyTitle) . '</i></div>{error}'
                                    ])->textInput(['class' => 'form-control input-sm right-icon money-input', 'tabindex' => '105']);
                                    ?>

<?php if (!empty($modelAmount->Currency2)): ?>
    <?= $form->field($modelAmount, 'Income2')->begin(); ?>
                    <?= Html::activeTextInput($modelAmount, 'Income2', ['class' => 'form-control input-sm', 'convert_url' => Yii::$app->urlManager->createAbsoluteUrl(["invoice/ajax/ConvertInvoiceAmount", 'invid' => $modelAmount->InvoiceID, 'amountTitle' => 'approved2', 'Id' => $modelAmount->ID, 'formStatus' => $modelAmount->isNewRecord ? 'new' : 'edit'])]); ?>
                    <?= Html::error($modelAmount, 'Income2', ['class' => 'help-block help-block-error']); ?>
    <?= $form->field($modelAmount, 'Income2')->end(); ?>
                        <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3" style="padding: 0px;">
                            <div class="form-group">    
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <span class="text-lime" for="">Comulative</span>    
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12">
<?=
$form->field($modelAmount, "Income1cumulative", [
    'template' => '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ui-sortable">{input}<i class="s12 right-input-icon">' .  Html::encode($modelAmount->currency1->CurrencyTitle) . '</i>{error}</div>'
])->textInput(['class' => 'form-control input-sm right-icon money-input', 'tabindex' => '108']);
?>

<?php if (!empty($modelAmount->Currency2)): ?>
    <?=
    $form->field($modelAmount, "Income2cumulative", [
        'template' => '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ui-sortable">{input}<i class="s12 right-input-icon">' .  Html::encode($modelAmount->currency2->CurrencyTitle) . '</i>{error}</div>'
    ])->textInput(['class' => 'form-control input-sm right-icon money-input']);
    ?>
<?php endif; ?>
                                </div>
                            </div>
                        </div>

                    </div>
                </div><hr>
<?= Html::activeHiddenInput($modelAmount, 'OwnerContractIDRef', ['id' => 'input-contractid']); ?>
                <div class="row text-left">
                    <div class="col-lg-12 col-md-12 col-sm-12">
<?= Html::submitButton($modelAmount->isNewRecord ? '<i class="fa-plus"></i> Create' : '<i class="fa-edit"></i> Save', ['class' => $modelAmount->isNewRecord ? 'btn btn-sm btn-success' : 'btn btn-sm btn-primary']); ?>
<?=
Html::button('<i class="br-cancel"></i> Cancel', [
    'class' => 'btn btn-warning btn-sm btn-cancel',
    'data-dismiss' => "modal",
    'aria-hidden' => "true"
]);
?>
                    </div>
                </div>
            </div> 

        </div>
    </div></div>

<?php ActiveForm::end(); ?>
<?php
$this->registerCss("
    hr{
        margin-top: 10px;
        margin-bottom: 10px;
        border: 0;
        border-top: 1px solid #ccc;
    }

    .panel-body{
        padding:5px 5px 0px 7px;
    }
    .panel{
        margin-bottom:0px;
    }
    .modal-body{
        position: relative;
        padding: 0px;
    }
    .table{
        margin-bottom: 0px;
    }
    ");


