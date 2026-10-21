<?php

use app\modules\invoice\assets\InvoiceEditAsset;
use app\components\PageHeader;
use yii\helpers\Html;

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
        <tr><th width='25%'>ContractNo:</th><td width='10'></td><td><?php echo Html::encode($invoiceEditModel->contract->ContractNo); ?></td></tr>    
        <tr><th>Contract Subject:</th><td></td><td><?php echo Html::encode($invoiceEditModel->contract->ContractSubject); ?></td></tr>    
        <tr><th>Contract Category:</th><td></td><td><?php echo Html::encode($invoiceEditModel->contract->category->ContractCategory); ?> </td></tr>    
        <tr><th>Client:</th><td></td><td><?php echo Html::encode($invoiceEditModel->contract->signer->PersonTitle); ?></td></tr>    
        <tr><th>SubContractor:</th><td></td><td><?php echo Html::encode($invoiceEditModel->contract->subContractor->PersonTitle); ?></td></tr>    
        <tr><th>Contract Type:</th><td></td><td><?php echo Html::encode($invoiceEditModel->contract->type->ContractTypeTitle); ?></td></tr>    
        <tr><th>Contract Date:</th><td></td><td><?php echo $invoiceEditModel->contract->ContractDateHijri; ?></td></tr>    
        <tr><th>Finish Date:</th><td></td><td><?php echo $invoiceEditModel->contract->lastRider->FinishDateHijri; ?></td></tr>    

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
            $this->render('_subInvoiceForm', [
                'invoiceEditModel' => $invoiceEditModel,
                'invoiceTypes' => $invoiceEditModel->getInvoiceType($invoiceEditModel->ContractID),
                'parentInvoiceArray' => $parentInvoiceArray,
                'arrayCurrency' => $arrayCurrency,
//                'mode' => $mode,
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
            ?>       
        </div>
    </div>

