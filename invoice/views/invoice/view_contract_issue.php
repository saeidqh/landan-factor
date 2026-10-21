<?php

use app\components\PageHeader;
use kartik\grid\GridView;
use app\components\Utility;
use yii\widgets\Pjax;
use yii\helpers\Html;

$this->title = Yii::t('invoice', 'Invoice Detail');
?>
<?=
PageHeader::widget([
    'title' => $this->title,
    'icon' => 'fa-dollar',
]);
?>
<div class="bs-example bs-example-tabs">
    <?=
    $this->render('_view_tab_links', [
        'tab' => '#contract_issue',
        'access' => $access,
        'invoiceModel' => $invoiceModel,
        'peid' => $peid,
        'todoExists' => $todoExists,
    ]);
    ?>
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
                        </tr>
                    </table>
                    </div>
                </div>
                <?php Pjax::begin(['id' => 'conIssuelList']); ?>
                <?=
                GridView::widget([
                    'id' => 'tbl-contracts-grid5',
                    'filterModel' => $contractIssueModel,
                    'dataProvider' => $contractIssueProvider,
                    'tableOptions' => ['class' => 'table table-striped table-hover non-responsive psa-grid table-bordered'],
                    'columns' => array(
                        'Subject',
                        [
                            'label' => Yii::t('invoice', 'User Name'),
                            'attribute' => 'userName',
//                            'value' => 'user.UserName',
                            'value' => 'user.fullName',
                        ],
                        'Category',
                        'IssueDateHijri',
                        'Status',
                        array(
                            'attribute' => 'Description',
                            'format' => 'raw',
                            'value' => function($contractIssueModel) {
                                return Utility::generateTextShorterSpan($contractIssueModel['Description'], 30, 100);
                            },
                            'options' => array(
                                'width' => '150px',
                            )
                        ),
                    ),
                ]);
                ?> 
                <?php Pjax::end(); ?>
            </div>
        </div>
    </div>
</div>
