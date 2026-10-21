<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use app\components\Utility;
?>

<?=

kartik\grid\GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        [
            'label' => Yii::t('contract','CostCode'),
            'format' => 'raw',
            'value' => function($model) {
                return Utility::generateCostCodeLink($model['NodeID'], $model['NodeFullCode']);
            },
        ],
        [
            'label' => Yii::t('contract','Requested'),
            'value' => function($data) {
                return Utility::moneyFormatByCurrency($data["Requested"],$data['CurrencyID']);
            }
        ],
        [
            'label' => Yii::t('contract','Approved'),
            'value' => function($data) {
                return Utility::moneyFormatByCurrency($data["Approved"],$data['CurrencyID']);
            }
        ],
        [
            'label' => Yii::t('contract','Income'),
            'value' => function($data) {
                return Utility::moneyFormatByCurrency($data["Income"],$data['CurrencyID']);
            }
        ],
        [
            'label' => Yii::t('contract','Adjustment'),
            'value' => function($data) {
                return Utility::moneyFormatByCurrency($data["Adjustment"],$data['CurrencyID']);
            }
        ],
        [
            'label' => Yii::t('contract','Penalty'),
            'value' => function($data) {
                return Utility::moneyFormatByCurrency($data["Penalty"],$data['CurrencyID']);
            }
        ],
    ]
]);
?>
                