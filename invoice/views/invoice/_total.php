<?php 
use app\components\DashboardPanel;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\components\Session;
use app\modules\invoice\assets\InvoiceListAsset;
use app\components\Utility;
use yii\helpers\Html;

InvoiceListAsset::register($this);
?>
<?php DashboardPanel::begin([
    'title' => Yii::t('invoice','Total Amount'),
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
            [
                'label' => Yii::t('invoice','Currency Title'),
                'value' => 'CurrencyTitle',
            ],
            [
                'label' => Yii::t('invoice','Requested'),
                'value' => function($model){
                    return Utility::moneyFormat(round($model["Requested"]));
                }
            ],
            [
                'label' => Yii::t('invoice','Approved'),
                'value' => function($model){
                    return Utility::moneyFormat(round($model["Approved"]));
                }
            ],
            [
                'label' => Yii::t('invoice','Income'),
                'value' => function($model){
                    return Utility::moneyFormat(round($model["Income"]));
                }
            ],
            [
                'label' => Yii::t('invoice','Penalty'),
                'value' => function($model){
                    return Utility::moneyFormat(round($model["Penalty"]));
                }
            ],
        ],
    ]);
?>
</div>
<?php DashboardPanel::end();?>