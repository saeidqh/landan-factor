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

<div class="profile-view">
    <div class="sb-content">
        <table class="table table-striped">
            <tbody>

                <tr>
                    <?= $form->field($model, 'InvoiceDocumentTypeIDref')->DropDownList($documentType, array('prompt' => 'Select Document Type', 'class' => 'form-control input-sm')); ?>
                </tr>
                <tr>

                    <?php if ($formState == 'new'): ?>
                        <?=
                        $form->field($model, 'file')->widget(kartik\widgets\FileInput::className(), [
                            'pluginOptions' => [
                                'showPreview' => false,
                                'showUpload' => false,
                                'allowedFileExtensions' => ['xlsb', 'xls', 'xlsx', 'pdf', 'zip', 'rar'],
                            ]
                        ]);
                        ?>

                    <?php endif; ?>                                 
                </tr>

                <tr>
                    <?= $form->field($model, 'Description')->textarea(['rows' => '7']) ?>
                </tr>
            </tbody>
        </table>
    </div>
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






