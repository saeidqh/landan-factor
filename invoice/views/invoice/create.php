<?php

use app\modules\invoice\assets\InvoiceEditAsset;
use app\components\PageHeader;
use app\assets\DatepickerAsset;
use app\assets\JdateTimeAsset;
use yii\helpers\Html;

DatepickerAsset::register($this);
JdateTimeAsset::register($this);

$this->title = 'New Invoice';

InvoiceEditAsset::register($this);
?>

<?=
PageHeader::widget([
    'title' => $this->title,
    'icon' => 'fa-dollar',
//    'breadcrumbs' => $this->breadcrumbs,
]);
?>
<table class='table table-striped psa-grid psa-grid-text-left psa-detail-view' >
    <tbody>
        <tr><th width='25%'>ContractNo:</th><td width='10'></td><td><?php echo  Html::encode($invoiceEditModel->contract->ContractNo); ?></td></tr>    
        <tr><th>Contract Subject:</th><td></td><td><?php echo  Html::encode($invoiceEditModel->contract->ContractSubject); ?></td></tr>    
        <tr><th>Contract Category:</th><td></td><td><?php echo  Html::encode($invoiceEditModel->contract->category->ContractCategory); ?> </td></tr>    
        <tr><th>Client:</th><td></td><td><?php echo  Html::encode($invoiceEditModel->contract->subContractor->PersonTitle); ?></td></tr>    
        <tr><th>Contract Type:</th><td></td><td><?php echo  Html::encode($invoiceEditModel->contract->type->ContractTypeTitle); ?></td></tr>    
        <tr><th>Contract Date:</th><td></td><td><?php echo  Html::encode($invoiceEditModel->contract->ContractDateHijri); ?></td></tr>    
        <tr><th>Finish Date:</th><td></td><td><?php echo  Html::encode($invoiceEditModel->contract->lastRider->FinishDateHijri); ?></td></tr>    

    </tbody>    
</table>
<div class="bs-example bs-example-tabs">
    <ul id="myTab" class="nav nav-tabs" role="tablist">
        <li class="active"><a href="#InvoiceInfo" role="tab" data-toggle="tab"><i class="fa-info"></i> Invoice Info</a></li>
        <li class=""><a href="#contractInvoice" role="tab" data-toggle="tab"><i class="im-contract"></i> Contract Invoices</a></li></li>
    </ul>
    <div id="myTabContent" class="tab-content">
        <div class="tab-pane fade active in" id="InvoiceInfo">

            <?=
            $this->render('_invoiceForm', [
                'invoiceEditModel' => $invoiceEditModel,
                'invoiceTypes' => $invoiceEditModel->getInvoiceType($invoiceEditModel->ContractID),
                'parentInvoiceArray' => $parentInvoiceArray,
                'currencyItems' => $currencyItems,
            ]);
            ?>
        </div>
        <div class="tab-pane fade in" id="contractInvoice">
            <?=
            $this->render('_contractInvoiceList', [
                'invoiceEditModel' => $invoiceEditModel,
                'contractInvoiceDataProvider' => $contractInvoiceDataProvider,
                'contractInvoiceSearchModel' => $contractInvoiceSearchModel,
                'invoiceTypeArray' => $invoiceTypeArray,
            ]);
            ?>        </div>
    </div>

<script>
        
        $(document).ready(function(){
            $('.datepicker-fa').each(function() {
                $(this).datepicker({dateFormat: 'yy/mm/dd'});
            });
        });
</script>

<script>
//--begin invoice form
    //click to id="invoiceedit-invoicetype"
    $('#invoiceedit-invoicetype').change(function(){
        var selectedValue= $('#invoiceedit-invoicetype option:selected').val();
        if(selectedValue == '17' || selectedValue == '18' ||
                selectedValue == '19' || selectedValue == '20' || selectedValue == '21' || selectedValue == '22')
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
    
// from 
    $('#invoiceedit-fromcutoffdatehijri').blur(function(){
        var value = $(this).val();
        if(value === ''){
            $('#invoiceedit-fromcutoffdate').val('');
        }
    });
    $('#invoiceedit-fromcutoffdatehijri').change(function(){
        var date = ShamsiToGregorian($(this).val());
        if(date != ''){
            $('#invoiceedit-fromcutoffdate-kvdate').kvDatepicker('update', date);
        }
    });
    $('#invoiceedit-fromcutoffdate').change(function(){
        var date = GregorianToShamsi($(this).val());
        if(date != ''){
             $('#invoiceedit-fromcutoffdatehijri').val(date);
        }
    });
    
    //end----------------------------------------------invoice form
</script>
