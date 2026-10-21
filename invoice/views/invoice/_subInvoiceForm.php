<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\currency\models\Currency;
use yii\widgets\Pjax;
use app\assets\DatepickerAsset;
use app\assets\JdateTimeAsset;

DatepickerAsset::register($this);
JdateTimeAsset::register($this);

$obsId = Yii::$app->session->get('ObsPosition');
$isCandoAdmin = \app\access\AccessControl::isCandoAdmin($obsId);
?>
<?php Pjax::begin(['id' => 'newinvoice-pjax']); ?>
<?php
foreach (Yii::$app->session->getAllFlashes() as $key => $value) {
    echo kartik\widgets\Growl::widget([
        'type' => kartik\widgets\Growl::TYPE_WARNING,
        'icon' => ($key == 'warning') ? 'en-warning' : 'en-success',
        'title' => 'Warning',
        'delay' => '1000',
        'body' => $value,
        'pluginOptions' => [
            'placement' => [
                'from' => 'bottom',
                'align' => 'right',
            ]
        ]
    ]);
}
?>
<?php $form = ActiveForm::begin([
    'id' => 'invoice-edit-form',
    'enableClientValidation' => false,
    'options' => [
        'class' => 'form-horizontal',
        'data-pjax' => '1'
    ]
]);
?>
<div class="row">
    <div class="col-lg-6 col-md-6">


        <div class="profile-view">
            <div class="sb-content">

                <?php echo $form->field($invoiceEditModel, 'InvoiceType')->begin(); ?>
                <div class="col-lg-2 col-md-2">
                    <?= Html::activeLabel($invoiceEditModel, 'InvoiceType'); ?>
                </div>
                <div class="col-lg-10 col-md-10">
                    <?php echo Html::activeDropDownList($invoiceEditModel, 'InvoiceType', $invoiceTypes, array('prompt' => 'Select Invoice Type', 'class' => 'form-control input-sm', 'disabled' => !($isCandoAdmin) ? 'disabled' : false)); ?>
                    <?php echo Html::error($invoiceEditModel, 'InvoiceType', ['class' => 'help-block help-block-error']) ?>
                </div>
                <?php echo $form->field($invoiceEditModel, 'InvoiceType')->end(); ?>


                <?php echo $form->field($invoiceEditModel, 'Title')->begin(); ?>
                <div class="col-lg-2 col-md-2">
                    <?= Html::activeLabel($invoiceEditModel, 'Title'); ?>
                </div>
                <div class="col-lg-10 col-md-10">
                    <?= Html::activeTextInput($invoiceEditModel, 'Title', [
                        'class' => 'form-control input-sm',
                        'disabled' => (!empty($fieldEnabled)) ? (Yii::$app->controller->formFieldIsEnable('title', $fieldEnabled)) ? false : true : false,
                    ]); ?>
                    <?= Html::error($invoiceEditModel, 'Title', ['class' => 'help-block help-block-error']); ?>
                </div>
                <?php echo $form->field($invoiceEditModel, 'Title')->end(); ?>


                <?php // if($invoiceEditModel->RevisionNumber >0){ ?>
                <?php echo $form->field($invoiceEditModel, 'ParrentID')->begin(); ?>
                <div class="col-lg-2 col-md-2">
                    <?= Html::activeLabel($invoiceEditModel, 'ParrentID'); ?>
                </div>
                <div class="col-lg-10 col-md-10">
                    <?php echo Html::activeDropDownList($invoiceEditModel, 'ParrentID', $parentInvoiceArray, array('prompt' => 'Select parent invoice', 'class' => 'form-control input-sm', 'disabled' => 'disabled')); ?>


                    <?= Html::error($invoiceEditModel, 'ParrentID', ['class' => 'help-block help-block-error']); ?>
                </div>
                <?php echo $form->field($invoiceEditModel, 'ParrentID')->end(); ?>
                <?php // } ?>

                <?php echo $form->field($invoiceEditModel, 'Description')->begin(); ?>
                <div class="col-lg-2 col-md-2">
                    <?= Html::activeLabel($invoiceEditModel, 'Description'); ?>
                </div>
                <div class="col-lg-10 col-md-10">
                    <?= Html::activeTextarea($invoiceEditModel, 'Description', array(
                        'class' => 'form-control input-sm',
                        'style' => 'direction: rtl;text-align: right;',
                        'disabled' => (!empty($fieldEnabled)) ? (Yii::$app->controller->formFieldIsEnable('Description', $fieldEnabled)) ? false : true : false,
                    )); ?>
                    <?= Html::error($invoiceEditModel, 'Description', ['class' => 'help-block help-block-error']); ?>
                </div>
                <?php echo $form->field($invoiceEditModel, 'Description')->end(); ?>

                <!-- اگر قرارداد از نوع Unit Rate|Call Out بود بتواند تاریخ را درج کند-->
                <?php if ($invoiceEditModel->contract->ContractType == 11 || $invoiceEditModel->contract->ContractType == 18) { ?>
                    <div class="row">

                        <div class="col-lg-6 col-md-6" style="padding-right: 0px;">
                            <?= $form->field($invoiceEditModel, "fromCutOffDateHijri", [
                                'template' => '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">{label}</div><div class="col-lg-8 col-md-8 col-sm-8 col-xs-8" style="padding-right:0px;">{input}<i class="fa-calendar right-input-icon"></i>{error}</div>'
                            ])->textInput(['class' => 'form-control input-sm right-icon datepicker-fa']); ?>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <?php $invoiceEditModel->fromCutOffDate = Yii::$app->utility->date($invoiceEditModel->fromCutOffDate) ?>
                            <?= $form->field($invoiceEditModel, "fromCutOffDate", [
                                'template' => '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-left:0px;">{input}<i class="fa-calendar right-input-icon"></i>{error}</div>'
                            ])->widget(kartik\widgets\DatePicker::className()); ?>
                        </div>
                    </div>
                <?php } ?>


                <div class="row">
                    <div class="col-lg-6 col-md-6" style="padding-right: 0px;">
                        <?= $form->field($invoiceEditModel, "CutOffDateHijri", [
                            'template' => '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">{label}</div><div class="col-lg-8 col-md-8 col-sm-8 col-xs-8" style="padding-right:0px;">{input}<i class="fa-calendar right-input-icon"></i>{error}</div>'
                        ])->textInput(['class' => 'form-control input-sm right-icon datepicker-fa']); ?>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <?php $invoiceEditModel->CutOffDate = Yii::$app->utility->date($invoiceEditModel->CutOffDate) ?>
                        <?= $form->field($invoiceEditModel, "CutOffDate", [
                            'template' => '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-left:0px;">{input}<i class="fa-calendar right-input-icon"></i>{error}</div>'
                        ])->widget(kartik\widgets\DatePicker::className()); ?>
                    </div>
                </div>
                <br>

            </div>
            <br>
            <div class="row">

                <div>
                    <?= Html::submitButton($invoiceEditModel->isNewRecord ? '<i class="fa-plus"></i> Create' : '<i class="fa-edit"></i> Save', array('class' => $invoiceEditModel->isNewRecord ? 'btn btn-sm btn-success mb10 pull-left' : 'btn btn-sm btn-primary mb10 pull-left')); ?>
                    <?= $invoiceEditModel->isNewRecord ? null : Html::Button('<i class="br-cancel"></i> Cancel', ['class' => 'btn btn-sm btn-warning btn-cancel']) ?>

                </div>
            </div>
        </div>

    </div>
    <div class="col-lg-6 col-md-6"><br>
        <div id="currency-fileds" style="display: none;">
            <div class="col-lg-12 col-md-12">
                <?php echo $form->field($invoiceEditModel, 'Currency1IDRef')->begin(); ?>
                <div class="col-lg-3 col-md-3">
                    <?= Html::activeLabel($invoiceEditModel, 'Currency1IDRef'); ?>
                </div>
                <div class="col-lg-9 col-md-9">
                    <?= Html::activeDropDownList($invoiceEditModel, 'Currency1IDRef', $currencyItems, array(
                            'class' => 'form-control input-sm',
                            'prompt' => 'Select currency1 ...',
                        )
                    ); ?>
                    <?= Html::error($invoiceEditModel, 'Currency1IDRef', ['class' => 'help-block help-block-error']); ?>
                </div>
                <?php echo $form->field($invoiceEditModel, 'Currency1IDRef')->end(); ?>
            </div>

            <div class="col-lg-12 col-md-12">
                <?php echo $form->field($invoiceEditModel, 'Currency2IDRef')->begin(); ?>
                <div class="col-lg-3 col-md-3">
                    <?= Html::activeLabel($invoiceEditModel, 'Currency2IDRef'); ?>
                </div>
                <div class="col-lg-9 col-md-9">
                    <?= Html::activeDropDownList($invoiceEditModel, 'Currency2IDRef', $currencyItems, array('class' => 'form-control input-sm', 'prompt' => 'Select currency2 ...')); ?>
                    <?= Html::error($invoiceEditModel, 'Currency2IDRef', ['class' => 'help-block help-block-error']); ?>
                </div>
                <?php echo $form->field($invoiceEditModel, 'Currency2IDRef')->end(); ?>
            </div>
        </div>


        <div class="col-lg-12 col-md-12">
            <?php echo $form->field($invoiceEditModel, 'financialDocNumber')->begin(); ?>
            <div class="col-lg-3 col-md-3">
                <label class="invoiceedit-financialDocDateHijri">Doc Number (financial)</label>
            </div>
            <div class="col-lg-9 col-md-9">
                <?= Html::activeTextInput($invoiceEditModel, 'financialDocNumber', array(
                    'class' => 'form-control input-sm',
                    'disabled' => (!empty($fieldEnabled)) ? (Yii::$app->controller->formFieldIsEnable('financialDocNumber', $fieldEnabled)) ? false : true : false,
                )); ?>
                <?= Html::error($invoiceEditModel, 'financialDocNumber', ['class' => 'help-block help-block-error']); ?>
            </div>
            <?php echo $form->field($invoiceEditModel, 'financialDocNumber')->end(); ?>
        </div>

        <div class="col-lg-12 col-md-12">
            <?php echo $form->field($invoiceEditModel, 'financialDocDateHijri')->begin(); ?>
            <div class="col-lg-2 col-md-2">
                <label class="invoiceedit-financialDocDateHijri">Doc Date (financial)</label>
            </div>
            <div class="col-lg-4 col-md-4">
                <?= Html::activeTextInput($invoiceEditModel, 'financialDocDateHijri', array(
                    'class' => 'form-control input-sm datepicker-fa',
                    'disabled' => (!empty($fieldEnabled)) ? (Yii::$app->controller->formFieldIsEnable('financialDocDateHijri', $fieldEnabled)) ? false : true : false,
                )); ?>
                <?= Html::error($invoiceEditModel, 'financialDocDateHijri', ['class' => 'help-block help-block-error']); ?>
            </div>
            <?php echo $form->field($invoiceEditModel, 'financialDocDateHijri')->widget(kartik\widgets\DatePicker::className())->end(); ?>
        </div>

        <div class="col-lg-12 col-md-12"><br>
            <div class="panel panel-default">
                <div class="panel-heading"><h4>(se. 2)<i class="fa-dollar pull-right"></i></h4></div>
                <div class="panel-body">
                    <div class="col-lg-6 col-md-6">
                        <?php echo $form->field($invoiceEditModel, 'approved1UsdF2')->begin(); ?>

                        <label for="invoiceedit-approved1usdf2">approved1</label><i class="badge badge-info"
                                                                                    style="padding: 3px 3px;">USD</i>
                        <?= Html::activeTextInput($invoiceEditModel, 'approved1UsdF2', array(
                            'class' => 'money-input form-control input-sm',
                            'disabled' => (!empty($fieldEnabled)) ? (Yii::$app->controller->formFieldIsEnable('approved1UsdF2', $fieldEnabled)) ? false : true : false,
                        )); ?>
                        <?= Html::error($invoiceEditModel, 'approved1UsdF2', ['class' => 'help-block help-block-error']); ?>

                        <?php echo $form->field($invoiceEditModel, 'approved1UsdF2')->end(); ?>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <?php echo $form->field($invoiceEditModel, 'approved1IrrF2')->begin(); ?>

                        <label for="invoiceedit-approved1IrrF2">approved1</label><i class="badge badge-info"
                                                                                    style="padding: 3px 3px;">IRR</i>
                        <?= Html::activeTextInput($invoiceEditModel, 'approved1IrrF2', array(
                            'class' => 'money-input form-control input-sm',
                            'disabled' => (!empty($fieldEnabled)) ? (Yii::$app->controller->formFieldIsEnable('approved1IrrF2', $fieldEnabled)) ? false : true : false,
                        )); ?>
                        <?= Html::error($invoiceEditModel, 'approved1IrrF2', ['class' => 'help-block help-block-error']); ?>

                        <?php echo $form->field($invoiceEditModel, 'approved1IrrF2')->end(); ?>
                    </div>

                    <div class="col-lg-6 col-md-6">
                        <?php if (!empty($invoiceEditModel->Currency2IDRef)): ?>
                            <?php echo $form->field($invoiceEditModel, 'approved2UsdF2')->begin(); ?>
                            <label for="invoiceedit-approved2UsdF2">approved2</label><i class="badge badge-info"
                                                                                        style="padding: 3px 3px;">USD</i>
                            <?= Html::activeTextInput($invoiceEditModel, 'approved2UsdF2', array(
                                'class' => 'form-control input-sm',
                                'disabled' => (!empty($fieldEnabled)) ? (Yii::$app->controller->formFieldIsEnable('approved2UsdF2', $fieldEnabled)) ? false : true : false,
                            )); ?>
                            <?= Html::error($invoiceEditModel, 'approved2UsdF2', ['class' => 'help-block help-block-error']); ?>
                            <?php echo $form->field($invoiceEditModel, 'approved2UsdF2')->end(); ?>
                        <?php endif; ?>
                    </div>

                    <div class="col-lg-6 col-md-6">
                        <?php if (!empty($invoiceEditModel->Currency2IDRef)): ?>
                            <?php echo $form->field($invoiceEditModel, 'approved2IrrF2')->begin(); ?>
                            <label for="invoiceedit-approved2IrrF2">approved2</label><i class="badge badge-info"
                                                                                        style="padding: 3px 3px;">IRR</i>
                            <?= Html::activeTextInput($invoiceEditModel, 'approved2IrrF2', array(
                                'class' => 'form-control input-sm',
                                'disabled' => (!empty($fieldEnabled)) ? (Yii::$app->controller->formFieldIsEnable('approved2IrrF2', $fieldEnabled)) ? false : true : false,
                            )); ?>
                            <?= Html::error($invoiceEditModel, 'approved2IrrF2', ['class' => 'help-block help-block-error']); ?>
                            <?php echo $form->field($invoiceEditModel, 'approved2IrrF2')->end(); ?>
                        <?php endif; ?>
                    </div>
                </div><!--end-body-->
            </div><!--end-panel-->


            <div class="panel panel-default">
                <div class="panel-heading"><h4>(se. 3)<i class="fa-dollar pull-right"></i></h4></div>
                <div class="panel-body">
                    <div class="col-lg-6 col-md-6">
                        <?php echo $form->field($invoiceEditModel, 'approved1UsdF3')->begin(); ?>
                        <label for="invoiceedit-approved1UsdF3">approved1</label><i class="badge badge-info"
                                                                                    style="padding: 3px 3px;">USD</i>
                        <?= Html::activeTextInput($invoiceEditModel, 'approved1UsdF3', array(
                            'class' => 'money-input form-control input-sm',
                            'disabled' => (!empty($fieldEnabled)) ? (Yii::$app->controller->formFieldIsEnable('approved1UsdF3', $fieldEnabled)) ? false : true : false,
                        )); ?>
                        <?= Html::error($invoiceEditModel, 'approved1UsdF3', ['class' => 'help-block help-block-error']); ?>
                        <?php echo $form->field($invoiceEditModel, 'approved1UsdF3')->end(); ?>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <?php echo $form->field($invoiceEditModel, 'approved1IrrF3')->begin(); ?>
                        <label for="invoiceedit-approved1IrrF3">approved1</label><i class="badge badge-info"
                                                                                    style="padding: 3px 3px;">IRR</i>
                        <?= Html::activeTextInput($invoiceEditModel, 'approved1IrrF3', array(
                            'class' => 'money-input form-control input-sm',
                            'disabled' => (!empty($fieldEnabled)) ? (Yii::$app->controller->formFieldIsEnable('approved1IrrF3', $fieldEnabled)) ? false : true : false,
                        )); ?>
                        <?= Html::error($invoiceEditModel, 'approved1IrrF3', ['class' => 'help-block help-block-error']); ?>
                        <?php echo $form->field($invoiceEditModel, 'approved1IrrF3')->end(); ?>
                    </div>

                    <div class="col-lg-6 col-md-6">
                        <?php if (!empty($invoiceEditModel->Currency2IDRef)): ?>
                            <?php echo $form->field($invoiceEditModel, 'approved2UsdF3')->begin(); ?>
                            <label for="invoiceedit-approved2UsdF3">approved2</label><i class="badge badge-info"
                                                                                        style="padding: 3px 3px;">USD</i>
                            <?= Html::activeTextInput($invoiceEditModel, 'approved2UsdF3', array(
                                'class' => 'form-control input-sm',
                                'disabled' => (!empty($fieldEnabled)) ? (Yii::$app->controller->formFieldIsEnable('approved2UsdF3', $fieldEnabled)) ? false : true : false,
                            )); ?>
                            <?= Html::error($invoiceEditModel, 'approved2UsdF3', ['class' => 'help-block help-block-error']); ?>
                            <?php echo $form->field($invoiceEditModel, 'approved2UsdF3')->end(); ?>
                        <?php endif; ?>
                    </div>

                    <div class="col-lg-6 col-md-6">
                        <?php if (!empty($invoiceEditModel->Currency2IDRef)): ?>
                            <?php echo $form->field($invoiceEditModel, 'approved2IrrF3')->begin(); ?>
                            <label for="invoiceedit-approved2IrrF3">approved2</label><i class="badge badge-info"
                                                                                        style="padding: 3px 3px;">IRR</i>
                            <?= Html::activeTextInput($invoiceEditModel, 'approved2IrrF3', array(
                                'class' => 'form-control input-sm',
                                'disabled' => (!empty($fieldEnabled)) ? (Yii::$app->controller->formFieldIsEnable('approved2IrrF3', $fieldEnabled)) ? false : true : false,
                            )); ?>
                            <?= Html::error($invoiceEditModel, 'approved2IrrF3', ['class' => 'help-block help-block-error']); ?>
                            <?php echo $form->field($invoiceEditModel, 'approved2IrrF3')->end(); ?>
                        <?php endif; ?>
                    </div>
                </div><!--end-body-->
            </div><!--end-panel-->
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
<?php Pjax::end(); ?>
<script>
    //--begin invoice form

    //click to id="invoiceedit-invoicetype"
    $('#invoiceedit-invoicetype').change(function () {
        var selectedValue = $('#invoiceedit-invoicetype option:selected').val();
        if (selectedValue == '17' || selectedValue == '18' ||
            selectedValue == '19' || selectedValue == '20' || selectedValue == '21') {
            $('#currency-fileds').slideDown(1000);
        } else {
            $('#currency-fileds').slideUp(1000);
        }
    });

    $('#newinvoice-pjax').on('pjax:send', function () {
        s
        ajaxProgressLoader('show', '.bs-example');
    });
    $('#newinvoice-pjax').on('pjax:complete', function () {
        ajaxProgressLoader('hide', '.bs-example');
        $('.datepicker-fa').each(function () {
            $(this).datepicker({dateFormat: 'yy/mm/dd'});
        });
    });
    $('.datepicker-fa').each(function () {
        $(this).datepicker({dateFormat: 'yy/mm/dd'});
    });
    $('#input-revision-number').change(function () {
        var revisionNumber = $(this).val();
        if (revisionNumber > 0) {
            $('#invoiceedit-parrentid').removeAttr('disabled');
        } else if (revisionNumber == 0) {
            $('#invoiceedit-parrentid').attr('disabled', 'disabled');
        }
    });

    $('#invoiceedit-cutoffdatehijri').blur(function () {
        var value = $(this).val();
        if (value === '') {
            $('#invoiceedit-cutoffdate').val('');
        }
    });
    $('#invoiceedit-cutoffdatehijri').change(function () {
        var date = ShamsiToGregorian($(this).val());
        if (date != '') {
            $('#invoiceedit-cutoffdate-kvdate').kvDatepicker('update', date);
        }
    });
    $('#invoiceedit-cutoffdate').change(function () {
        var date = GregorianToShamsi($(this).val());
        if (date != '') {
            $('#invoiceedit-cutoffdatehijri').val(date);
        }
    });


    $('#invoiceedit-financialDocDateHijri').blur(function () {
        var value = $(this).val();
        if (value === '') {
            $('#invoiceedit-cutoffdate').val('');
        }
    });
    $('#invoiceedit-financialDocDateHijri').change(function () {
        var date = ShamsiToGregorian($(this).val());
        if (date != '') {
            $('#invoiceedit-cutoffdate-kvdate').kvDatepicker('update', date);
        }
    });
    $('#invoiceedit-cutoffdate').change(function () {
        var date = GregorianToShamsi($(this).val());
        if (date != '') {
            $('#invoiceedit-financialDocDateHijri').val(date);
        }
    });


    //    from
    $('#invoiceedit-fromcutoffdatehijri').blur(function () {
        var value = $(this).val();
        if (value === '') {
            $('#invoiceedit-fromcutoffdate').val('');
        }
    });
    $('#invoiceedit-fromcutoffdatehijri').change(function () {
        var date = ShamsiToGregorian($(this).val());
        if (date != '') {
            $('#invoiceedit-fromcutoffdate-kvdate').kvDatepicker('update', date);
        }
    });
    $('#invoiceedit-fromcutoffdate').change(function () {
        var date = GregorianToShamsi($(this).val());
        if (date != '') {
            $('#invoiceedit-fromcutoffdatehijri').val(date);
        }
    });
    //end----------------------------------------------invoice form
</script>