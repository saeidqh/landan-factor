<?php
use app\components\PageHeader;
use kartik\grid\GridView;

$this->title = Yii::t('invoice','Invoice Detail');

?>
<?= PageHeader::widget([
    'title' => $this->title,
    'icon' => 'fa-dollar',
]);
?>
<div class="bs-example bs-example-tabs">
    <?= $this->render('_view_tab_links', [
        'tab' => '#invoice_pksk_list',
        'access' => $access,
        'invoiceModel' => $invoiceModel,
        'peid' => $peid,
        'todoExists' => $todoExists,
    ]); ?>
    <!--begin-task-->
    <div id="myTabContent" class="tab-content">
        <div class="tab-pane fade active in">
            <div class="row">
            <?= GridView::widget([
                'dataProvider' => $pkskRelateddataProvider,
                'filterModel' => $invoicePkskModel,
                'pjax' => true,
                'columns' => [
                    [
                        'attribute' => 'invoiceNumber',
                        'label' => 'Invoice NO',
                        'value' => 'invoice.Title',
                    ],
                    'CI_No',
                    'PL_No',
                    'MSR_No',
                    'MSR_Description',
                    'CI_Amount1',
                    'CI_Amount2'
                ]
            ]);?>
            </div>
        </div>
    </div>
</div>
