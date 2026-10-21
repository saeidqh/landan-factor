<?php
use yii\grid\GridView;
use app\components\Utility;
?>

<?= GridView::widget([
    'id' => 'tbl-invoice-grid2',
//    'enableSorting' =>false,
    'filterModel' => false,
    'dataProvider' => $invoiceAmountLogDataProvider,
    'columns' => array(
        array(
            'header' => 'Amount ID',
            'format' => 'raw',
            'attribute' => 'amountId',
            'filter' => false,
            'options' => array('width' => '60px'),
        ),
        array(
            'header' => 'Amount Type',
            'format' => 'raw',
            'filter' => false,
            'value' => 'type.InvoiceAmountType',
        ),array(
            'header' => 'Cost Code',
            'format' => 'raw',
            'filter' => false,
            'value' => 'costCode.NodeFullCode',
            'options'=>array(
                'width'=>'140px',
            ),
        ),
        array(
            'attribute'=>'Approved',
            'format'=>'raw',
            'filter' => false,
            'value'=> function($data){
                    return '<table class="inner-table">'
                        . '<tr><td width=\'200\'>'
                            .  Utility::moneyFormat($data["AmountApproved1"]) 
                        . '<tr class="top-border"><td >'
                            . Utility::moneyFormat($data["AmountApproved2"])
                    . '</table>';
            },'options'=>array(
                'width'=>'145px',
            ),
        ),
        array(
            'attribute'=>'Requested',
            'format'=>'raw',
            'filter' => false,
            'value'=> function($data){
                    return '<table class="inner-table">'
                        . '<tr><td width=\'200\'>'
                            .  Utility::moneyFormat($data["AmountRequested1"]) 
                        . '</td><td class="label-silver">'.$data['currency1']["CurrencyTitle"].'</td></tr>'
                        . '<tr class="top-border"><td >'
                            . Utility::moneyFormat($data["AmountRequested2"])
                        .' </td><td class="label-silver">'.$data['currency2']["CurrencyTitle"].'</td></tr>'
                    . '</table>';
            },'options'=>array(
                'width'=>'155px',
            ),
        ),
        array(
            'attribute'=>'Action Title',
            'format'=>'raw',
            'filter' => false,
            'value'=>function($data){
                return Utility::generateTextShorterSpan($data['action']['ActionTitle2'],30,60);
            },
            'options'=>array(
                'width'=>'120px',
            ),
        ),   
        array(
            'header' => 'UserName',
            'format' => 'raw',
            'filter' => false,
            'attribute' => 'user.UserName',
            'value' => 'user.fullName',
        ),array(
            'header' => 'Date',
            'format' => 'raw',
            'filter' => false,
            'attribute' => 'updateHijri',
        ),array(
            'label' => 'Owner',
            'format' => 'raw',
            'filter' => false,
            'value' => 'owner.ContractNo',
        ),
        
    ),
]);
?>









