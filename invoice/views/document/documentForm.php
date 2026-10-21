<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use kartik\file\FileInput;
?>
<?php
$form = ActiveForm::begin([
            'id' => 'invoice-document-edit-form',
            'options' => [
                'class' => 'form-horizontal',
                'data-pjax' => '1',
                'enctype' => 'multipart/form-data'
            ]
        ]);
?>

<div class="col-md-12">
    <?= $form->field($model, 'InvoiceDocumentTypeIDref')
        ->DropDownList($documentType, array('prompt' => 'Select Document Type', 'class' => 'form-control input-sm')); ?>
</div>



<div class="col-md-12">
    <?php if ($formState == 'new'): ?>
                        <?=
                        $form->field($model, 'file')->widget(kartik\widgets\FileInput::className(), [
                            'pluginOptions' => [
                                'showPreview' => false,
                                'showUpload' => false,
                                'allowedFileExtensions' => ['xlsm','xlsb', 'xls', 'xlsx', 'pdf', 'zip', 'rar'],
                            ]
                        ]);
                        ?>

                    <?php endif; ?>  
</div>
<div class="col-md-12">
    <div class="col-md-6">
        <?= $form->field($model, 'originalNumber')->textInput() ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'copyNumber')->textInput() ?>
    </div>
</div>
<div class="col-md-12">
    <?= $form->field($model, 'Description')->textarea(['rows' => '7']) ?>
</div>


<div>
    <?=
    Html::submitButton($model->isNewRecord ? '<i class="im-upload2"></i> Upload' : '<i class="fa-edit"></i> Update', array(
        'ID' => 'btnSaveInvoiceAmount',
        'class' => ($model->isNewRecord ? 'btn btn-success btn-sm' : 'btn btn-primary btn-sm'),
    ));
    ?>
    <?= Html::a('<i class="br-cancel"></i> Cancel', '#', ['data-dismiss' => "modal", 'aria-hidden' => "true", 'class' => 'btn btn-sm btn-warning']); ?>
</div>
<?php ActiveForm::end(); ?>






