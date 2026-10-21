<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<br /><br />
<style>
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
</style>

<div class="panel" style="margin-bottom: 10px;">

    <div class="panel-body" style="margin: 0px;padding-bottom: 5px;">
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
            <div class="col-md-6">
                <div class="col-md-4" style="padding:0px;">
                    <label for="invoice-number">Invoice Number</label>
                </div>
                <div class="col-lg-8 col-md-8" style="padding:0px;">
<?= Html::textInput("Title", $modelAmount->invoice->Title, ['class' => 'form-control input-sm', 'disabled' => 'disabled', 'invoice-number']) ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="col-lg-4 col-md-4" style="padding:0px;">
                    <label for="cutofdate">Cut Of Date</label>
                </div>
                <div class="col-lg-8 col-md-8" style="padding:0px;">
                    <input type="text" readonly="readonly" value="<?= $modelAmount->invoice->CutOffDateHijri ?>" class="form-control input-sm" id="cutofdate">
                </div>
            </div>
        </div><hr style="padding:0px;margin-top: 5px;">
        <div class="row">
            <div class="col-lg-12 col-md-12">

<?=
$form->field($modelAmount, 'CostCode', [])->widget(app\components\CostCodePickerPro::className(), [
    'projectId' => $projectId
])->label('CostCode')
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

                                <?php if (!empty($modelAmount->Currency2)) { ?>
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
<?php } ?>
                        </tbody>

                </table>
                <hr>
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
//        var nodeId = $('#invoiceamountedit-costcode').val();
        var nodeId = $("#amountform input[name='InvoiceAmountEdit[CostCode]']").last().val();

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
                    amountType: 1,
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
