<div class="bs-example bs-example-tabs">

    <ul id="myTab" class="nav nav-tabs" role="tablist">
        <li class="active"><a href="#LogInfo" role="tab" data-toggle="tab">Invoice Info</a></li>
        <li class=""><a href="#LogAmount" role="tab" data-toggle="tab">Invoice Amount</a></li>
        <div class="col-md-4 pull-right text-right mb5 ">
    </ul>
    <div id="myTabContent" class="tab-content">
        <div class="tab-pane fade active in" id="LogInfo">
            <div class="row InvoiceAmount-grid" style="position:relative;">
                <?=
                $this->render('_InvoiceLogInfoList', array(
                    'invoiceLogDataProvider' => $invoiceLogDataProvider,
                    'invid' => $invid,
                ));
                ?> 
            </div>
        </div>
        <div class="tab-pane fade" id="LogAmount">
            <div class="row InvoiceAmount-grid" style="position:relative;">
                <?=
                $this->render('_InvoiceLogAmountList', array(
                    'invoiceAmountLogDataProvider' => $invoiceAmountLogDataProvider,
                    'invid' => $invid,
                ));
                ?> 
            </div>
        </div>
    </div>
</div>





