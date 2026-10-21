<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\currency\models\Currency;
use yii\widgets\Pjax;
use app\assets\DatepickerAsset;
use app\assets\JdateTimeAsset;
DatepickerAsset::register($this);
JdateTimeAsset::register($this);
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


        <?php echo $form->field($invoiceEditModel, 'RevisionNumber')->begin(); ?>
        <div class="col-md-2 col-lg-2">
            <?= Html::activeLabel($invoiceEditModel, 'RevisionNumber'); ?>
        </div>
        <div class="col-lg-10 col-md-10">
            <?= Html::activeTextInput($invoiceEditModel, 'RevisionNumber', array('class' => 'form-control input-sm', 'id' => 'input-revision-number')); ?>
            <?= Html::error($invoiceEditModel, 'RevisionNumber', ['class'=>'help-block']); ?>               
        </div>
        <?php echo $form->field($invoiceEditModel, 'RevisionNumber')->end(); ?>


        <?php // if($invoiceEditModel->RevisionNumber >0){ ?>
        <?php echo $form->field($invoiceEditModel, 'ParrentID')->begin(); ?>
        <div class="col-lg-2 col-md-2">
            <?= Html::activeLabel($invoiceEditModel, 'ParrentID'); ?>
        </div>
        <div class="col-lg-10 col-md-10">
            <?php echo Html::activeDropDownList($invoiceEditModel, 'ParrentID', $parentInvoiceArray, array('prompt' => 'Select parent invoice', 'class' => 'form-control input-sm', 'disabled'=>'disabled')) ; ?>

           
                    <?= Html::error($invoiceEditModel, 'ParrentID', ['class'=>'help-block help-block-error']); ?>
        </div>
        <?php echo $form->field($invoiceEditModel, 'ParrentID')->end(); ?>
        <?php // } ?>

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
        <div id="currency-fileds" style="display: none;">
            <div class="col-lg-12 col-md-12">
                <?php echo $form->field($invoiceEditModel, 'Currency1IDRef')->begin(); ?>
                <div class="col-lg-3 col-md-3">
                    <?= Html::activeLabel($invoiceEditModel, 'Currency1IDRef'); ?>
                </div>
                <div class="col-lg-9 col-md-9">
                    <?= Html::activeDropDownList($invoiceEditModel, 'Currency1IDRef', $arrayCurrency, array(
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
                    <?= Html::activeDropDownList($invoiceEditModel, 'Currency2IDRef', $arrayCurrency, array('class' => 'form-control input-sm', 'prompt'=>'Select currency2 ...')); ?>
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
<script>
//--begin invoice form
    //click to id="invoiceedit-invoicetype"
    $('#invoiceedit-invoicetype').change(function(){
        var selectedValue= $('#invoiceedit-invoicetype option:selected').val();
        if(selectedValue == '17' || selectedValue == '18' ||
                selectedValue == '19' || selectedValue == '20' || selectedValue == '21')
        {
            $('#currency-fileds').slideDown(1000);
        }else{
            $('#currency-fileds').slideUp(1000);
        }
    });
    
    
    $('#newinvoice-pjax').on('pjax:send', function(){s
        ajaxProgressLoader('show', '.bs-example');
    });
    $('#newinvoice-pjax').on('pjax:complete', function(){
        ajaxProgressLoader('hide', '.bs-example');
        $('.datepicker-fa').each(function (){
            $(this).datepicker({dateFormat:'yy/mm/dd'});
        });
    });
    $('#input-revision-number').change(function(){
        var revisionNumber = $(this).val();
        if(revisionNumber > 0){
            $('#invoiceedit-parrentid').removeAttr('disabled');
        }
        else if(revisionNumber == 0)
        {
             $('#invoiceedit-parrentid').attr('disabled', 'disabled');
        }
    });

    $('#invoiceedit-cutoffdatehijri').blur(function(){
        var value = $(this).val();
        if(value === ''){
            $('#invoiceedit-cutoffdate').val('');
        }
    });
    $('#invoiceedit-cutoffdatehijri').change(function(){
        var date = ShamsiToGregorian($(this).val());
        if(date != ''){
            $('#invoiceedit-cutoffdate-kvdate').kvDatepicker('update', date);
        }
    });
    $('#invoiceedit-cutoffdate').change(function(){
        var date = GregorianToShamsi($(this).val());
        if(date != ''){
             $('#invoiceedit-cutoffdatehijri').val(date);
        }
    });
    
    //end----------------------------------------------invoice form
</script>
<?php Pjax::end(); ?>
