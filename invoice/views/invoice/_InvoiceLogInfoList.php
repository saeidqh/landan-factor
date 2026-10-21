<?php
use yii\grid\GridView;
use app\components\Utility;
?>
<?= GridView::widget([
    'id' => 'tbl-invoice-grid1',
    'filterModel' => false,
    'dataProvider' => $invoiceLogDataProvider,
    'columns' => array(
        array(
            'header' => 'Invoice Type',
            'format' => 'raw',
            'filter' => false,
            'attribute' => 'type.InvoiceType',
        ),
        array(
            'header' => 'Invoice No',
            'format' => 'raw',
            'filter' => false,
            'attribute' => 'Title',
        ),
        array(
            'header' => 'Revision No',
            'format' => 'raw',
            'filter' => false,
            'attribute' => 'RevisionNumber',
        ),
        array(
            'header' => 'ParrentID',
            'format' => 'raw',
            'filter' => false,
            'attribute' => 'ParrentID',
        ),
        array(
            'header' => 'CutOffDate',
            'format' => 'raw',
            'filter' => false,
            'attribute' => 'CutOffDateHijri',
        ),
        array(
            'attribute'=>'Description',
            'format'=>'raw',
            'filter' => false,
            'value'=>function($data){
                return Utility::generateTextShorterSpan($data['Description'],30,60);
            },
            'options'=>array(
                'width'=>'120px',
            ),
        ),  
        array(
            'format' => 'raw',
            'attribute' => 'UserName',
            'label' => 'UserName',
//            'value' => 'user.UserName',
            'value' => 'user.fullName',
        ),
         array(
            'label'=>'Action Title',
            'format'=>'raw',
            'filter' => false,
            'value'=>function($model){
                return Utility::generateTextShorterSpan($model->action->ActionTitle2,30,60);
            },
            'options'=>array(
                'width'=>'120px',
            ),
        ),                        
        array(
            'header' => 'Date',
            'format' => 'raw',
            'filter' => false,
            'attribute' => 'updateHijri',
        ),
    ),
]);
?>









