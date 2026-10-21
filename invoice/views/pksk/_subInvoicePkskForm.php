<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
?>
<?php Pjax::begin(['id' => 'form-pksk-pjax-' . $formState]); ?>
<?php
$form = ActiveForm::begin([
            'id' => 'invoicePksk-form',
            "enableClientValidation" => false,
            'options' => [
                'class' => 'form-horizontal',
                'data-pjax' => '1'
            ]
        ]);
?>
<script>
    $('.btn-cancel').click(function() {
        $('#pksk-panel').slideUp(1000).html('');
        $('.child-tr').hide(500);
        $('#btn-newInvoicePksk').slideDown(1000);
    });
    $('#form-pksk-pjax-' + '<?= $formState ?>').on('pjax:send', function() {
        ajaxProgressLoader('show', '#form-pksk-pjax-' + '<?= $formState ?>');
    });
    $('#form-pksk-pjax').on('pjax:complete', function() {
        ajaxProgressLoader('hide', '#form-pksk-pjax-' + '<?= $formState ?>');
    });
    handleInputMoneyFormat();
</script>
<?= $form->field($model, "InvoiceIDRef")->hiddenInput()->label(''); ?>

<div class="row">
    <div class="col-lg-6 col-md-6">
        <div>
            <?= $form->field($model, 'CI_No')->begin(); ?>
            <div class="col-lg-3 col-md-3">
                <?= Html::activeLabel($model, 'CI_No'); ?>
            </div>
            <div class="col-lg-9 col-md-9">
                <?= Html::activeTextInput($model, 'CI_No', ['class' => 'form-control input-sm']); ?>
                <?= Html::error($model, 'CI_No', ['class' => 'help-block help-block-error']); ?>
            </div>
            <?= $form->field($model, 'CI_No')->end(); ?>
        </div>

        <div>
            <?= $form->field($model, 'PL_No')->begin(); ?>
            <div class="col-lg-3 col-md-3">
                <?= Html::activeLabel($model, 'PL_No'); ?>
            </div>
            <div class="col-lg-9 col-md-9">
                <?php
                if ($model->invoice->InvoiceType > 5) {
                    echo Html::activeTextInput($model, 'PL_No', ['class' => 'form-control input-sm', 'readonly' => 'readonly']);
                } else {
                    echo Html::activeTextInput($model, 'PL_No', ['class' => 'form-control input-sm',]);
                }
                echo Html::error($model, 'PL_No', ['class' => 'help-block help-block-error',]);
                ?>
            </div>
            <?= $form->field($model, 'PL_No')->end(); ?> 
        </div>

        <div>
            <?= $form->field($model, 'MSR_No')->begin(); ?>
            <div class="col-lg-3 col-md-3">
                <?= Html::activeLabel($model, 'MSR_No'); ?>
            </div>
            <div class="col-lg-9 col-md-9">
                <?php
                if ($model->invoice->InvoiceType > 5) {
                    echo Html::activeTextInput($model, 'MSR_No', ['class' => 'form-control input-sm', 'readonly' => 'readonly']);
                } else {
                    echo Html::activeTextInput($model, 'MSR_No', ['class' => 'form-control input-sm',]);
                    echo Html::error($model, 'MSR_No', ['class' => 'help-block help-block-error']);
                }
                ?>
            </div>
            <?= $form->field($model, 'MSR_No')->end(); ?>
        </div>

        <div>
            <?= $form->field($model, 'MSR_Description')->begin(); ?>
            <div class="col-lg-3 col-md-3">
                <?= Html::activeLabel($model, 'MSR_Description'); ?>
            </div>
            <div class="col-lg-9 col-md-9">
                <?= Html::activeTextInput($model, 'MSR_Description', ['class' => 'form-control input-sm']); ?>
                <?= Html::error($model, 'MSR_Description', ['class' => 'help-block help-block-error']); ?>
            </div>
            <?= $form->field($model, 'MSR_Description')->end(); ?>
        </div>

        <div>
            <?= $form->field($model, 'CI_Amount1')->begin(); ?>
            <div class="col-lg-3 col-md-3">
                <?= Html::activeLabel($model, 'CI_Amount1'); ?>
            </div>
            <div class="col-lg-6 col-md-6">
                <?= Html::activeTextInput($model, 'CI_Amount1', ['class' => 'form-control input-sm money-input']); ?>
                <?= Html::error($model, 'CI_Amount1', ['class' => 'help-block help-block-error']); ?>
            </div>
            <div class="col-lg-3 col-md-3">
                <div class="row">
                    <?php echo Html::activeDropDownList($model, 'CI_Currency1IDRef', $currencyArray, ['class' => 'form-control input-sm', 'style' => 'width:87px;', 'disabled' => 'disabled']); ?>
                </div>
            </div>
            <?= $form->field($model, 'CI_Amount1')->end(); ?>
        </div>
        <?php if (!empty($invoiceViewData->Currency2ID)): ?>
            <div>
                <?= $form->field($model, 'CI_Amount2')->begin(); ?>
                <div class="col-lg-3 col-md-3">
                    <?= Html::activeLabel($model, 'CI_Amount2'); ?>
                </div>
                <div class="col-lg-6 col-md-6">
                    <?= Html::activeTextInput($model, 'CI_Amount2', ['class' => 'form-control input-sm', 'disabled' => "disabled"]); ?>
                    <?= Html::error($model, 'CI_Amount2', ['class' => 'help-block help-block-error']); ?>
                </div>
                <div class="col-lg-3 col-md-3">
                    <div class="row">
                        <?php echo Html::activeDropDownList($model, 'CI_Currency1IDRef', ['prompt' => ''], ['class' => 'form-control input-sm', 'style' => 'width:87px;', 'disabled' => 'disabled']); ?>
                    </div>
                </div>
                <?= $form->field($model, 'CI_Amount2')->end(); ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="col-lg-6 col-md-6">
        <div class="form-group">
            <div class="col-lg-2 col-md-2">
                <label>Invoice Amount1</label>
            </div>
            <div class="col-lg-7 col-md-7">
                <input type="text" class="form-control input-sm money-input" value="<?= $model->invoice->Approved1 ?>" readonly="readonly"> 
            </div>
            <div class="col-lg-3 col-md-3">
                <div class="row">
                    <?php echo Html::activeDropDownList($model, 'CI_Currency1IDRef', $currencyArray, ['class' => 'form-control input-sm', 'readonly' => 'readonly']); ?>
                </div>
            </div>
        </div>
        <?php if (!empty($invoiceViewData->Currency2ID)): ?>
            <div class="form-group">
                <div class="col-lg-2 col-md-2">
                    <label>Invoice Amount2</label>
                </div>
                <div class="col-lg-7 col-md-7">
                    <input type="text" class="form-control input-sm" value="<?= $invoiceViewData['Approved2'] ?>" readonly="readonly"> 
                </div>
                <div class="col-lg-3 col-md-3">
                    <div class="row">
                        <?php echo Html::activeDropDownList($model, 'CI_Currency1IDRef', $currencyData, ['class' => 'form-control input-sm', 'readonly' => 'readonly']); ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

</div>
<div class="row">
    <div class="col-lg-12 col-md-12">
        <?= Html::submitInput($model->isNewRecord ? 'Create' : 'Save', array('class' => 'btn btn-xs btn-success mb10 pull-left')); ?>
        <?= Html::Button('cancel', ['class' => 'btn btn-xs btn-warning btn-cancel pull-left']) ?>
    </div>
</div>


<?php ActiveForm::end(); ?>
<?php Pjax::end(); ?>

