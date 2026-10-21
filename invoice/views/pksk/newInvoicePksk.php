<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="panel panel-default" id="pksk-panel">
            <div class="panel-heading">
                <i class="im-pencil"></i> New Invoice PKSK
            </div>
            <div class="panel-body">
                <?= $this->render('_subInvoicePkskForm',[
                    'model' => $invoicePkskModel,
                    'currencyArray' => $currencyArray,
                    'formState' => 'new'
                ]);?>
            </div>
        </div>
    </div>
</div>

