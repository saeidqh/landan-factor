<?php

use yii\helpers\Html;
use app\components\Utility;
?>
<div style="width: 100%;padding:5px 0px 5px 0px;background: #ccf;">
    <div style="padding:5px;background-color: #e4e9eb;font-size: 12px;">
        Sub Contractor <i class="badge badge-orange"><?= Html::encode($invoiceModel->contract->subContractor->PersonTitle); ?></i> |
        Contract Number <i class="badge badge-purple"><?= Html::encode($invoiceModel->contract->ContractNo); ?></i> |
        Invoice Number <i class="badge badge-brown"><?= Html::encode($invoiceModel->Title); ?> </i> |
        Invoice Type <i class="badge badge-info"><?= Html::encode($invoiceModel->type->InvoiceType); ?> </i> 
    </div>
</div>
<?=
kartik\grid\GridView::widget([
    'id' => 'contract-budget-grid',
    'options' => [
        'style' => 'font-family:tahoma; font-size:11px;',
    ],
    'hover' => true,
    'pjax' => true,
    'pjaxSettings' => [
        'options' => [
            'id' => 'contract-budget-grid-pjax',
        ]
    ],
    'filterModel' => $contractNodeBudgetModel,
    'dataProvider' => $contractNodeBudgetProvider,
    'columns' => [
        [
            'attribute' => 'nodeFullCode',
            'label' => 'CostCode',
            'format' => 'raw',
            'value' => function($model) {
        return isset($model->costcode) ? Utility::generateCostCodeLink($model->costcode->NodeID,$model->costcode->NodeFullCode) : '';
    },
            'options' => [
                'width' => '120px',
            ]
        ],
        [
            'attribute' => 'ownerContractNo',
            'label' => 'Owner',
            'format' => 'raw',
            'value' => function($model) {
        return '<table class="inner-table" width="100%" ><tr><td width="50%"  class="right-border">'
                . ($model->owner->ContractNo)
                . '</td><td  width="50%">'
                . ($model->owner->subContractor->PersonTitle)
                . '</td></tr><tr class="top-border" ><td colspan="2" class="label-silver" >'
                . ($model->owner->ContractSubject)
                . '</td></tr></table>';
    }
        ],
        [
            'header' => 'Owner Price',
            'format' => 'raw',
            'value' => function($model) {
        $curr2 = (isset($model->owner->lastRider->currency2)) ? Html::encode($model->owner->lastRider->currency2->CurrencyTitle) : '';
        return '<table class="inner-table">'
                . '<tr><td width=\'200\'>'
                . Utility::moneyFormat($model->OwnerPrice1) . '&nbsp;&nbsp;' . Html::encode($model->owner->lastRider->currency1->CurrencyTitle)
                . '<tr class="top-border"><td >'
                . Utility::moneyFormat($model->OwnerPrice2) . '&nbsp;&nbsp;' . $curr2
                . '</table>';
    },
            'options' => [
                'width' => '130',
            ]
        ],
        array(
            'attribute' => 'executerContractNo',
            'label' => 'Executer',
            'format' => 'raw',
            'value' => function($model) {
        return '<table class="inner-table" width="100%" ><tr><td width="50%"  class="right-border">'
                . (Html::encode($model->executer->ContractNo))
                . '</td><td  width="50%">'
                . (Html::encode($model->executer->subContractor->PersonTitle))
                . '</td></tr><tr class="top-border" ><td colspan="2" class="label-silver" >'
                . (Html::encode($model->executer->ContractSubject))
                . '</td></tr></table>';
    }
        ),
        array(
            'header' => 'Executer Price',
            'format' => 'raw',
            'value' => function($model) {
        $curr2 = (isset($model->owner->lastRider->currency2)) ? Html::encode($model->owner->lastRider->currency2->CurrencyTitle) : '';
        return '<table class="inner-table">'
                . '<tr><td width=\'200\'>'
                . Utility::moneyFormat($model->ExecuterPrice1) . '&nbsp;&nbsp;' . Html::encode($model->owner->lastRider->currency1->CurrencyTitle)
                . '<tr class="top-border"><td >'
                . Utility::moneyFormat($model->ExecuterPrice2) . '&nbsp;&nbsp;' . $curr2
                . '</table>';
    },
            'options' => array(
                'width' => '130',
            )
        ),
        [
            'attribute' => 'isActive',
            'label' => 'is Active',
            'value' => 'isActive',
        ],
    ],
]);

?>