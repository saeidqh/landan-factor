<?php 
use app\components\DashboardPanel;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\components\Session;
use app\modules\invoice\assets\InvoiceListAsset;
use app\components\Utility;

InvoiceListAsset::register($this);
?>
<?php DashboardPanel::begin([
    'title' => 'Total Amount',
    'icon' => 'im-stack',
    'ajax_url' => '',
    'header' => '',
    'refresf_btn' => FALSE,
    'close_btn' => FALSE,
    'toggle_btn' => TRUE,
    'template' => 'panel-purple',
]);?>
<div class="invoice-total-side" style="position:relative">

<?= GridView::widget([
        'id' => 'po-report-po-total',
        'dataProvider' => $totalInvoiceDataProvider,
        'columns' => [
            'CurrencyTitle',
            [
                'attribute' => 'Requested',
                'value' => function($model){
                    return Utility::moneyFormat(round($model["Requested"]));
                }
            ],
            [
                'attribute' => 'Approved',
                'value' => function($model){
                    return Utility::moneyFormat(round($model["Approved"]));
                }
            ],
            [
                'attribute' => 'Income',
                'value' => function($model){
                    return Utility::moneyFormat(round($model["Income"]));
                }
            ],
            [
                'label' => 'Penalty',
                'value' => function($model){
                    return Utility::moneyFormat(round($model["Penalty"]));
                }
            ],
        ],
    ]);
?>
</div>
<?php DashboardPanel::end();?>