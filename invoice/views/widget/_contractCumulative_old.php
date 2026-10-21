<?php

//use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use app\components\Utility;
?>

<?php
$amountStatus = Yii::$app->request->get('amountStatus');
$arraypksk = [
    [
        'class' => \kartik\grid\ExpandRowColumn::className(),
        'value' => function($url, $model, $key) {
    return \kartik\grid\GridView::ROW_COLLAPSED;
},
        'detailUrl' => Yii::$app->urlManager->createAbsoluteUrl(['invoice/widget/contract-cumulative-currency']),
        'headerOptions' => ['class' => 'kartik-sheet-style'],
        'expandOneOnly' => true,
        'expandIcon' => '<span class="fa-plus"></span>',
        'collapseIcon' => '<span class="fa-minus"></span>',
    ],
    [
        'label' => 'Currency',
        'value' => 'CurrencyTitle',
    ],
    [
        'attribute' => 'Price',
        'value' => function($model) {
            return Utility::moneyFormat(($model['CurrencyID'] == 1) ? round($model["Price"]) : $model["Price"], ($model['CurrencyID'] != 1) ? 2:null);
        }
   
    ],
    [
        'attribute' => 'Approved',
        'label' => !empty($amountStatus) ? $amountStatus : 'Approved',
        'value' => function($model) {
            return Utility::moneyFormat(($model['CurrencyID'] == 1) ? round($model["Approved"]) : $model["Approved"], ($model['CurrencyID'] != 1) ? 2:null);
        }
    ],
    [
        'attribute' => 'Income',
        'value' => function($model) {
            return Utility::moneyFormat(($model['CurrencyID'] == 1) ? round($model["Income"]) : $model["Income"], ($model['CurrencyID'] != 1) ? 2:null);
        }
    ],
    [
        'attribute' => 'Adjustment',
        'value' => function($model) {
            return Utility::moneyFormat(($model['CurrencyID'] == 1) ? round($model["Adjustment"]) : $model["Adjustment"], ($model['CurrencyID'] != 1) ? 2:null);
        }
    ],
    [
        'attribute' => 'LC',
        'value' => function($model) {
        return Utility::moneyFormat(($model['CurrencyID'] == 1) ? round($model["LC"]) : $model["LC"], ($model['CurrencyID'] != 1) ? 2:null);
        }
    ],
    [
        'attribute' => 'Logistic',
        'value' => function($model) {
        return Utility::moneyFormat(($model['CurrencyID'] == 1) ? round($model["Logistic"]) : $model["Logistic"], ($model['CurrencyID'] != 1) ? 2:null);
        }
    ],
    [
        'attribute' => 'Insurance',
        'value' => function($model) {
            return Utility::moneyFormat(($model['CurrencyID'] == 1) ? round($model["Insurance"]) : $model["Insurance"], ($model['CurrencyID'] != 1) ? 2:null);
        }
    ],
    [
        'attribute' => 'ServiceFee',
        'value' => function($model) {
        return Utility::moneyFormat(($model['CurrencyID'] == 1) ? round($model["ServiceFee"]) : $model["ServiceFee"], ($model['CurrencyID'] != 1) ? 2:null);
        }
    ],
    [
        'attribute' => 'Advanced',
        'value' => function($model) {
    return Utility::moneyFormat(round($model["Advanced"]), ($model['CurrencyID'] != 1) ? 2:null);
}
    ],
    [
        'attribute' => 'Remain',
        'value' => function($model) {
    return Utility::moneyFormat(round($model["Price"] - $model["Approved"]), ($model['CurrencyID'] != 1) ? 2:null);
}
    ],
    [
        'attribute' => 'Progress',
        'value' => function($model) {
    if ($model["Price"] > 0) {
        return Utility::moneyFormat(round(100 * $model["Approved"] / $model["Price"])) . '%';
    } else {
        return '';
    }
}
    ],
];
?>

<?php

$array = [
    [
        'class' => \kartik\grid\ExpandRowColumn::className(),
        'value' => function($url, $model, $key) {
    return \kartik\grid\GridView::ROW_COLLAPSED;
},
        'detailUrl' => Yii::$app->urlManager->createAbsoluteUrl(['invoice/widget/contract-cumulative-currency']),
        'headerOptions' => ['class' => 'kartik-sheet-style'],
        'expandOneOnly' => true,
        'expandIcon' => '<span class="fa-plus"></span>',
        'collapseIcon' => '<span class="fa-minus"></span>',
    ],
    [
        'label' => 'Currency',
        'value' => 'CurrencyTitle',
    ],
    [
        'attribute' => 'Price',
        'value' => function($model) {
    return Utility::moneyFormat(round($model["Price"]), ($model['CurrencyID'] != 1) ? 2:null);
}
    ],
    [
        'attribute' => 'Requested',
        'value' => function($model) {
    return Utility::moneyFormat(round($model["Requested"]), ($model['CurrencyID'] != 1) ? 2:null);
}
    ],
    [
        'attribute' => 'Approved',
        'label' => !empty($amountStatus) ? $amountStatus : 'Approved',
        'value' => function($model) {
    return Utility::moneyFormat(round($model["Approved"]), ($model['CurrencyID'] != 1) ? 2:null);
}
    ],
    [
        'attribute' => 'Income',
        'value' => function($model) {
    return Utility::moneyFormat(round($model["Income"]), ($model['CurrencyID'] != 1) ? 2:null);
}
    ],
    [
        'attribute' => 'Adjustment',
        'value' => function($model) {
    return Utility::moneyFormat(round($model["Adjustment"]), ($model['CurrencyID'] != 1) ? 2:null);
}
    ],
    [
        'attribute' => 'Penalty',
        'value' => function($model) {
    return Utility::moneyFormat(round($model["Penalty"]), ($model['CurrencyID'] != 1) ? 2:null);
}
    ],
    [
        'attribute' => 'Remain',
        'value' => function($model) {
    return Utility::moneyFormat(round($model["Price"] - $model["Approved"]), ($model['CurrencyID'] != 1) ? 2:null);
}
    ],
    [
        'attribute' => 'Progress',
        'value' => function($model) {
    if ($model["Price"] > 0) {
        return Utility::moneyFormat(round(100 * $model["Approved"] / $model["Price"])) . '%';
    } else {
        return '';
    }
}
    ],
];
?>
<?=

kartik\grid\GridView::widget([
    'id'=>'contract-cumulative-grid',
    'dataProvider' => $dataProvider,
    'columns' =>
    ($contractModel['ContractCategory'] != 13) ? $array : $arraypksk
]);
?>
                