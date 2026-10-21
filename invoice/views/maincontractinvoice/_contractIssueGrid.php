<?php
use yii\widgets\Pjax;
use yii\grid\GridView;
use app\components\Utility;
?>
<div style="width: 100%;padding:5px 0px 5px 0px;background: #ccf;">
    <div style="padding:5px;background-color: #e4e9eb;font-size: 12px;">
        Sub Contractor <i class="badge badge-orange"><?= \yii\helpers\Html::encode($invoiceModel->contract->subContractor->PersonTitle); ?></i> |
        Contract Number <i class="badge badge-purple"><?= \yii\helpers\Html::encode($invoiceModel->contract->ContractNo); ?></i>
    </div>
</div>
<?php Pjax::begin(['id' => 'conIssuelList']); ?>
<?= GridView::widget([
    'id' => 'tbl-contracts-grid5',
    'filterModel' => $contractIssueModel,
    'dataProvider' => $contractIssueProvider,
    'tableOptions' => ['class' => 'table table-striped table-hover non-responsive psa-grid table-bordered'],
    'columns' => array(
        'Subject',
        [
            'label'=>'UserName',
            'attribute'=>'userName',
//            'value'=>'user.UserName',
            'value'=>'user.fullName',
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