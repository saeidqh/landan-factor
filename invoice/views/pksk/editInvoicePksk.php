<div class="row">
    <div class="col-lg-11 col-md-11">
        <div class="panel-body">
        <?= $this->render('_subInvoicePkskForm', [
            'model' => $invoicePkskModel,
            'currencyArray' => $currencyArray,
            'formState' => 'edit'
        ]);?>
        </div>
    </div>
</div>
