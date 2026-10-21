<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
?>
<?php
$form = ActiveForm::begin(['id' => 'invoice-pksk-form',
            'enableClientValidation' => true,
            'options' => [
                'data-pjax' => '1',
                'enctype' => 'multipart/form-data'
        ]]);
?>
<?= $form->field($invoicePkskModel, 'CI_No') ?>
<?= $form->field($invoicePkskModel, 'PL_No') ?>

<?php
if ($invoicePkskModel->scenario == 'normal') {
//    echo $form->field($invoicePkskModel, 'PL_No');
    echo $form->field($invoicePkskModel, 'MSR_No');
}
?>
<?= $form->field($invoicePkskModel, 'CI_Amount1')->textInput(['class' => 'form-control money-input']) ?>
<?php
if ($invoicePkskModel->invoice->Currency2IDRef > 0) {
    echo $form->field($invoicePkskModel, 'CI_Amount2')->textInput(['class' => 'form-control money-input']);
}
?>
<?= $form->field($invoicePkskModel, 'MSR_Description')->textarea() ?>

<div class="form-group">
    <?= Html::submitButton($invoicePkskModel->isNewRecord ? '<i class="fa-plus"></i> Create' : '<i class="fa-edit"></i> Update', ['class' => $invoicePkskModel->isNewRecord ? 'btn btn-sm btn-success' : 'btn btn-sm btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>

