<?php
use app\components\PageHeader;
use app\components\Utility;
use yii\helpers\Html;

$this->title = Yii::t('invoice','Invoice Detail');

?>
<?= PageHeader::widget([
    'title' => $this->title,
    'icon' => 'fa-dollar',
]);
?>
<div class="bs-example bs-example-tabs">
    <?= $this->render('_view_tab_links', [
        'tab' => '#contract_budget',
        'access' => $access,
        'invoiceModel' => $invoiceModel,
        'peid' => $peid,
        'todoExists' => $todoExists,
    ]); ?>
    <!--begin-task-->
    <div id="myTabContent" class="tab-content">
        <div class="tab-pane fade active in">
            <div class="row">
                <div style="width: 100%;padding:5px 0px 5px 0px;background: #ccf;">
                    <div style="padding:5px;background-color: #e4e9eb;font-size: 12px;">
                        <table>
                            <tr>
                                <td><?= Yii::t('contract', 'Subcontractor') ?> </td><td><i class="badge badge-orange"><?= Html::encode($invoiceModel->contract->subContractor->PersonTitle); ?></i>&nbsp;|&nbsp;</td>
                                <td><?= Yii::t('contract', 'Contract No') ?></td><td><i class="badge badge-purple"><?= Html::encode($invoiceModel->contract->ContractNo); ?></i>&nbsp;|&nbsp;</td>
                                <td><?= Yii::t('invoice', 'Invoice Number') ?></td><td><i class="badge badge-brown"><?= Html::encode($invoiceModel->Title); ?> </i>&nbsp;|&nbsp;</td>
                                <td><?= Yii::t('invoice', 'Invoice Type') ?></td><td><i class="badge badge-info"><?= Html::encode($invoiceModel->type->InvoiceType); ?> </i> </td>
                            </tr>
                        </table>
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
                            'label' => Yii::t('contract', 'CostCode'),
                            'format' => 'raw',
                            'value' => function($model) {
                                return isset($model->costcode) ? Utility::generateCostCodeLink($model->costcode->NodeID, $model->costcode->NodeFullCode) : '';
                            },
                            'options' => [
                                'width' => '120px',
                            ]
                        ],
                        [
                            'attribute' => 'ownerContractNo',
                            'label' => Yii::t('contract', 'Owner'),
                            'format' => 'raw',
                            'value' => function($model) {
                                return '<table class="inner-table" width="100%" ><tr><td width="50%"  class="right-border">'
                                        . Html::encode(($model->owner->ContractNo))
                                        . '</td><td  width="50%">'
                                        . Html::encode(($model->owner->subContractor->PersonTitle))
                                        . '</td></tr><tr class="top-border" ><td colspan="2" class="label-silver" >'
                                        . Html::encode(($model->owner->ContractSubject))
                                        . '</td></tr></table>';
                            }
                        ],
                        [
                            'label' => Yii::t('contract', 'Owner Price'),
                            'format' => 'raw',
                            'value' => function($model) {
                                $curr2 = (isset($model->owner->lastRider->currency2)) ? Html::encode($model->owner->lastRider->currency2->CurrencyTitle) : '';
                                return '<table class="inner-table">'
                                        . '<tr><td width=\'200\'>'
                                        . Utility::moneyFormat($model->OwnerPrice1) . '&nbsp;&nbsp;' . Html::encode($model->owner->lastRider->currency1->CurrencyTitle)
                                        . '<tr class="top-border"><td >'
                                        . Utility::moneyFormat($model->OwnerPrice2) . '&nbsp;&nbsp;' . Html::encode($curr2)
                                        . '</table>';
                            },
                            'options' => [
                                'width' => '130',
                            ]
                        ],
                        array(
                            'attribute' => 'executerContractNo',
                            'label' => Yii::t('contract', 'Executer'),
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
                            'label' => Yii::t('contract', 'Executer Price'),
                            'format' => 'raw',
                            'value' => function($model) {
                                $curr2 = (isset($model->owner->lastRider->currency2)) ? Html::encode($model->owner->lastRider->currency2->CurrencyTitle) : '';
                                return '<table class="inner-table">'
                                        . '<tr><td width=\'200\'>'
                                        . Utility::moneyFormat($model->ExecuterPrice1) . '&nbsp;&nbsp;' . Html::encode($model->owner->lastRider->currency1->CurrencyTitle)
                                        . '<tr class="top-border"><td >'
                                        . Utility::moneyFormat($model->ExecuterPrice2) . '&nbsp;&nbsp;' . Html::encode($curr2)
                                        . '</table>';
                            },
                            'options' => array(
                                'width' => '130',
                            )
                        ),
                        [
                            'attribute' => 'isActive',
                            'value' => 'isActive',
                        ],
                    ],
                ]);
                ?>
            </div>
        </div>
    </div>
</div>
