<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\currency\models\Currency;
use yii\widgets\Pjax;

?>
<?php Pjax::begin(['id' => 'newinvoice-pjax']); ?>
<?php 
 foreach (Yii::$app->session->getAllFlashes() as $key => $value) {
    echo kartik\widgets\Growl::widget([
        'type' => kartik\widgets\Growl::TYPE_WARNING,
        'icon' => ($key == 'warning') ? 'en-warning': 'en-success',
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
            <?php echo Html::activeDropDownList($invoiceEditModel, 'InvoiceType', $invoiceTypes, array('prompt' => 'Select Invoice Type', 'class' => 'form-control input-sm')); ?>
            <?php echo Html::error($invoiceEditModel, 'InvoiceType', ['class'=>'help-block help-block-error']) ?>
        </div>
        <?php echo $form->field($invoiceEditModel, 'InvoiceType')->end(); ?>


        <?php echo $form->field($invoiceEditModel, 'Title')->begin(); ?>
        <div class="col-lg-2 col-md-2">
            <?= Html::activeLabel($invoiceEditModel, 'Title'); ?>
        </div>
        <div class="col-lg-10 col-md-10">
            <?= Html::activeTextInput($invoiceEditModel, 'Title',['class' => 'form-control input-sm']); ?>
            <?= Html::error($invoiceEditModel, 'Title', ['class'=>'help-block help-block-error']); ?>
        </div>       
        <?php echo $form->field($invoiceEditModel, 'Title')->end(); ?>
        
        
        
        <!-- اگر قرارداد از نوع Unit Rate|Call Out||Cost Plus بود بتواند تاریخ را درج کند--> 
      <?php if($invoiceEditModel->contract->ContractType==11 || $invoiceEditModel->contract->ContractType==18  || $invoiceEditModel->contract->ContractType==13){ ?>
            <div class="row">  
                <div class="col-lg-6 col-md-6" style="padding-right: 0px;">
                    <?= $form->field($invoiceEditModel, "fromCutOffDateHijri", [
                    'template'=> '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">{label}</div><div class="col-lg-8 col-md-8 col-sm-8 col-xs-8" style="padding-right:0px;">{input}<i class="fa-calendar right-input-icon"></i>{error}</div>'
                    ])->textInput(['class' => 'form-control input-sm right-icon datepicker-fa']); ?>
                </div>
                <div class="col-lg-6 col-md-6">
                    <?php $invoiceEditModel->fromCutOffDate = Yii::$app->utility->date($invoiceEditModel->fromCutOffDate) ?>
                    <?= $form->field($invoiceEditModel, "fromCutOffDate", [
                    'template'=> '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-left:0px;">{input}<i class="fa-calendar right-input-icon"></i>{error}</div>'
                    ])->widget(kartik\widgets\DatePicker::className()); ?>
                </div>
            </div><br>
      <?php } ?>
            
            
      <div class="row">  
            <div class="col-lg-6 col-md-6" style="padding-right: 0px;">
                <?= $form->field($invoiceEditModel, "CutOffDateHijri", [
                'template'=> '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">{label}</div><div class="col-lg-8 col-md-8 col-sm-8 col-xs-8" style="padding-right:0px;">{input}<i class="fa-calendar right-input-icon"></i>{error}</div>'
                ])->textInput(['class' => 'form-control input-sm right-icon datepicker-fa']); ?>
            </div>
            <div class="col-lg-6 col-md-6">
                <?php $invoiceEditModel->CutOffDate = Yii::$app->utility->date($invoiceEditModel->CutOffDate) ?>
                <?= $form->field($invoiceEditModel, "CutOffDate", [
                'template'=> '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-left:0px;">{input}<i class="fa-calendar right-input-icon"></i>{error}</div>'
                ])->widget(kartik\widgets\DatePicker::className()); ?>
            </div>
        </div><br>
        
        
        <div>
        <?= Html::submitButton($invoiceEditModel->isNewRecord ? '<i class="fa-plus"></i> Create' : '<i class="fa-edit"></i> Save', array( 'class' => $invoiceEditModel->isNewRecord ? 'btn btn-sm btn-success mb10 pull-left': 'btn btn-sm btn-primary mb10 pull-left')); ?>
        <?= $invoiceEditModel->isNewRecord ? null :  Html::Button('cancel',['class' => 'btn btn-xs btn-warning btn-cancel']) ?>

        </div>
    </div>
    </div>

    </div>
    <div class="col-lg-6 col-md-6"><br>
        <div id="currency-fileds" style="display: block;">
            <div class="col-lg-12 col-md-12">
                <?php echo $form->field($invoiceEditModel, 'Currency1IDRef')->begin(); ?>
                <div class="col-lg-3 col-md-3">
                    <?= Html::activeLabel($invoiceEditModel, 'Currency1IDRef'); ?>
                </div>
                <div class="col-lg-9 col-md-9">
                    <?= Html::activeDropDownList($invoiceEditModel, 'Currency1IDRef', $currencyItems, array(
                        'class' => 'form-control input-sm',
                        'prompt'=>'Select currency1 ...',
                        )
                    ); ?>
                    <?= Html::error($invoiceEditModel, 'Currency1IDRef', ['class'=>'help-block help-block-error']); ?>
                </div>         
                <?php echo $form->field($invoiceEditModel, 'Currency1IDRef')->end(); ?> 
            </div>

            <div class="col-lg-12 col-md-12">
                <?php echo $form->field($invoiceEditModel, 'Currency2IDRef')->begin(); ?>
                <div class="col-lg-3 col-md-3">
                    <?= Html::activeLabel($invoiceEditModel, 'Currency2IDRef'); ?>
                </div>
                <div class="col-lg-9 col-md-9">
                    <?= Html::activeDropDownList($invoiceEditModel, 'Currency2IDRef', $currencyItems, array('class' => 'form-control input-sm', 'prompt'=>'Select currency2 ...')); ?>
                    <?= Html::error($invoiceEditModel, 'Currency2IDRef', ['class'=>'help-block help-block-error']); ?>
                </div>         
                <?php echo $form->field($invoiceEditModel, 'Currency2IDRef')->end(); ?> 
            </div>
        </div>
       
        <?php echo $form->field($invoiceEditModel, 'Description')->begin(); ?>
        <div class="col-lg-2 col-md-2">
            <?= Html::activeLabel($invoiceEditModel, 'Description'); ?>
        </div>
        <div class="col-lg-10 col-md-10">
            <?= Html::activeTextarea($invoiceEditModel, 'Description', array('class' => 'form-control input-sm', 'style' => 'direction: rtl;text-align: right;')); ?>
            <?= Html::error($invoiceEditModel, 'Description', ['class'=>'help-block help-block-error']); ?>
        </div>      
        <?php echo $form->field($invoiceEditModel, 'Description')->end(); ?> 
        
    </div>
</div>
<?php ActiveForm::end(); ?>


<?php Pjax::end(); ?>
