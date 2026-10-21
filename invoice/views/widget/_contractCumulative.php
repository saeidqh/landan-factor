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
        'label' => Yii::t('contract','Currency'),
        'value' => 'CurrencyTitle',
    ],
    [
        'label' => Yii::t('contract','Price'),
        'value' => function($model) {
            return Utility::moneyFormatByCurrency($model["Price"], $model['CurrencyID']);
//            return Utility::moneyFormat(($model['CurrencyID'] == 1) ? round($model["Price"]) : $model["Price"], ($model['CurrencyID'] != 1) ? 2 : null);
        }
        
    ],
    [
        'label' => Yii::t('contract',!empty($amountStatus) ? $amountStatus : 'Approved'),
        'value' => function($model) {
            return Utility::moneyFormatByCurrency($model["Approved"], $model['CurrencyID']);
//            return Utility::moneyFormat(($model['CurrencyID'] == 1) ? round($model["Approved"]) : $model["Approved"], ($model['CurrencyID'] != 1) ? 2 : null);
        }
    ],
    [
        'label' => Yii::t('contract','Income'),
        'value' => function($model) {
            return Utility::moneyFormatByCurrency($model["Income"], $model['CurrencyID']);
//            return Utility::moneyFormat(($model['CurrencyID'] == 1) ? round($model["Income"]) : $model["Income"], ($model['CurrencyID'] != 1) ? 2 : null);
        }
    ],
    [
        'label' => Yii::t('contract','Adjustment'),
        'value' => function($model) {
            return Utility::moneyFormatByCurrency($model["Adjustment"], $model['CurrencyID']);
//            return Utility::moneyFormat(($model['CurrencyID'] == 1) ? round($model["Adjustment"]) : $model["Adjustment"], ($model['CurrencyID'] != 1) ? 2 : null);
        }
    ],
    [
        'label' => Yii::t('contract','LC'),
        'value' => function($model) {
            return Utility::moneyFormatByCurrency($model["LC"], $model['CurrencyID']);
//            return Utility::moneyFormat(($model['CurrencyID'] == 1) ? round($model["LC"]) : $model["LC"], ($model['CurrencyID'] != 1) ? 2 : null);
        }
    ],
    [
        'label' => Yii::t('contract','Logistic'),
        'value' => function($model) {
            return Utility::moneyFormatByCurrency($model["Logistic"], $model['CurrencyID']);
//            return Utility::moneyFormat(($model['CurrencyID'] == 1) ? round($model["Logistic"]) : $model["Logistic"], ($model['CurrencyID'] != 1) ? 2 : null);
        }
    ],
    [
        'label' => Yii::t('contract','Insurance'),
        'value' => function($model) {
            return Utility::moneyFormatByCurrency($model["Insurance"], $model['CurrencyID']);
//            return Utility::moneyFormat(($model['CurrencyID'] == 1) ? round($model["Insurance"]) : $model["Insurance"], ($model['CurrencyID'] != 1) ? 2 : null);
        }
    ],
    [
        'label' => Yii::t('contract','ServiceFee'),
        'value' => function($model) {
            return Utility::moneyFormatByCurrency($model["ServiceFee"], $model['CurrencyID']);
//            return Utility::moneyFormat(($model['CurrencyID'] == 1) ? round($model["ServiceFee"]) : $model["ServiceFee"], ($model['CurrencyID'] != 1) ? 2 : null);
        }
    ],
    [
        'label' => Yii::t('contract','Advanced'),
        'value' => function($model) {
            return Utility::moneyFormatByCurrency($model["Advanced"], $model['CurrencyID']);
//            return Utility::moneyFormat(round($model["Advanced"]), ($model['CurrencyID'] != 1) ? 2 : null);
        }
    ],
    [
        'label' => Yii::t('contract','Remain'),
        'value' => function($model) {
            return Utility::moneyFormatByCurrency($model["Price"] - $model["Approved"], $model['CurrencyID']);
//            return Utility::moneyFormat(round($model["Price"] - $model["Approved"]), ($model['CurrencyID'] != 1) ? 2 : null);
        }
    ],
    [
        'label' => Yii::t('contract','Final Book'),
        'value' => function($model) {
            return Utility::moneyFormatByCurrency($model["Finalbook"], $model['CurrencyID']);
//            return Utility::moneyFormat(($model['CurrencyID'] == 1) ? round($model["Finalbook"]) : $model["ServiceFee"], ($model['CurrencyID'] != 1) ? 2 : null);
        }
    ],
    [
        'label' => Yii::t('contract','Progress'),
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
        'label' => Yii::t('contract','Currency'),
        'value' => 'CurrencyTitle',
    ],
    [
        'label' => Yii::t('contract','Price'),
        'value' => function($model) {
            return Utility::moneyFormatByCurrency($model["Price"], $model['CurrencyID']);
//            return Utility::moneyFormat(round($model["Price"]), ($model['CurrencyID'] != 1) ? 2 : null);
        }
    ],
    [
        'label' => Yii::t('contract','Requested'),
        'value' => function($model) {
            return Utility::moneyFormatByCurrency($model["Requested"], $model['CurrencyID']);
//            return Utility::moneyFormat(round($model["Requested"]), ($model['CurrencyID'] != 1) ? 2 : null);
        }
    ],
    [
        'label' => Yii::t('contract',!empty($amountStatus) ? $amountStatus : 'Approved'),
        'value' => function($model) {
            return Utility::moneyFormatByCurrency($model["Approved"], $model['CurrencyID']);
//            return Utility::moneyFormat(round($model["Approved"]), ($model['CurrencyID'] != 1) ? 2 : null);
        }
    ],
    [
        'label' => Yii::t('contract','Income'),
        'value' => function($model) {
            return Utility::moneyFormatByCurrency($model["Income"], $model['CurrencyID']);
//            return Utility::moneyFormat(round($model["Income"]), ($model['CurrencyID'] != 1) ? 2 : null);
        }
    ],
    [
        'label' => Yii::t('contract','Adjustment'),
        'value' => function($model) {
            return Utility::moneyFormatByCurrency($model["Adjustment"], $model['CurrencyID']);
//            return Utility::moneyFormat(round($model["Adjustment"]), ($model['CurrencyID'] != 1) ? 2 : null);
        }
    ],
    [
        'label' => Yii::t('contract','Penalty'),
        'value' => function($model) {
            return Utility::moneyFormatByCurrency($model["Penalty"], $model['CurrencyID']);
//            return Utility::moneyFormat(round($model["Penalty"]), ($model['CurrencyID'] != 1) ? 2 : null);
        }
    ],
    [
        'label' => Yii::t('contract','Remain'),
        'value' => function($model) {
            return Utility::moneyFormatByCurrency($model["Price"] - $model["Approved"], $model['CurrencyID']);
//            return Utility::moneyFormat(round($model["Price"] - $model["Approved"]), ($model['CurrencyID'] != 1) ? 2 : null);
        }
    ],
    [
        'label' => Yii::t('contract','Progress'),
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
    'id' => 'contract-cumulative-grid' . $wid,
    'dataProvider' => $dataProvider,
    'columns' =>
    ($contractModel['ContractCategory'] != 13) ? $array : $arraypksk
]);
?>
                