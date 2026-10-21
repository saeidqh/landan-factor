<?php
use yii\helpers\Url;
use yii\bootstrap\Html;
use yii\bootstrap\Modal;
use yii\widgets\Pjax;
?>
<ul id="myTab" class="nav nav-tabs" role="tablist">
    <?php if ($access[2] === 2 || $access[2] === 3): ?><li class="<?= ($tab == '#invoice_info') ? 'active':'' ?>"><a href="<?= Url::to(['view', 'peid' => $peid]) ?>"><i class="fa-info"></i> <?= Yii::t('invoice', 'Invoice Info') ?></a></li><?php endif; ?>
    <?php if ($access[1] === 2 || $access[1] === 3): ?><li class="<?= ($tab == '#invoice_amount') ? 'active':'' ?>"><a href="<?= Url::to(['view-invoice-amount', 'peid' => $peid]) ?>"><i class="fa-dollar"></i> <?= Yii::t('invoice', 'Invoice Amount') ?></a></li><?php endif; ?>
    <?php if ($access[0] === 2 || $access[0] === 3): ?><li class="<?= ($tab == '#invoice_document') ? 'active':'' ?>"><a href="<?= Url::to(['view-invoice-document', 'peid' => $peid]) ?>"><i class="en-docs"></i> <?= Yii::t('invoice', 'Invoice Document') ?></a></li><?php endif; ?>
    <?php if ($invoiceModel->contract->ContractCategory == 13 && ($access[1] === 2 || $access[1] === 3)): ?><li class="<?= ($tab == '#invoice_pksk_info') ? 'active':'' ?>"><a href="<?= Url::to(['view-pksk-info', 'peid' => $peid]) ?>"><i class="en-text2"></i> <?= Yii::t('invoice', 'PKSK Info') ?></a></li><?php endif; ?>
    <?php if ($invoiceModel->contract->ContractCategory == 13 && ($access[1] === 2 || $access[1] === 3)): ?><li class="<?= ($tab == '#invoice_pksk_list') ? 'active':'' ?>"><a href="<?= Url::to(['view-pksk-list', 'peid' => $peid]) ?>"><i class="en-text2"></i> <?= Yii::t('invoice', 'PKSK Related List') ?></a></li><?php endif; ?>
    <?php if ($invoiceModel->processEntity->ProcessEntityID != null): ?><li class="<?= ($tab == '#contract_revision') ? 'active':'' ?>"><a href="<?= Url::to(['view-contract-revisions', 'peid' => $peid]) ?>"><i class="en-reply-all"></i> <?= Yii::t('invoice', 'Contract Revisions') ?></a></li> <?php endif; ?>
    <?php if ($invoiceModel->processEntity->ProcessEntityID != null): ?><li class="<?= ($tab == '#contract_budget') ? 'active':'' ?>"><a href="<?= Url::to(['view-contract-budget', 'peid' => $peid]) ?>"><i class="im-bold"></i> <?= Yii::t('invoice', 'Contract Budget') ?></a></li> <?php endif; ?>
    <?php if ($invoiceModel->processEntity->ProcessEntityID != null): ?><li class="<?= ($tab == '#contract_issue') ? 'active':'' ?>"><a href="<?= Url::to(['view-contract-issue', 'peid' => $peid]) ?>"><i class="im-notification"></i> <?= Yii::t('invoice', 'Contract Issue') ?></a></li> <?php endif; ?>
    <?php if ($invoiceModel->processEntity->ProcessEntityID != null): ?><li class="<?= ($tab == '#contract_invoices') ? 'active':'' ?>"><a href="<?= Url::to(['view-contract-invoices', 'peid' => $peid]) ?>"><i class="fa-dollar"></i> <?= Yii::t('invoice', 'Contract Invoices') ?></a></li> <?php endif; ?>
</ul>

<?php
//next action
Modal::begin([
    'id' => 'invoice-nextaction-modal',
    'size' => Modal::SIZE_LARGE,
    'header' => '<h4 class="modal-title"><i class="im-arrow-right2"></i> Next action</h4>'
]);
Pjax::begin(['id' => 'pjax-modal']);
$js = "
        function showModal(obj){
            var url = $(obj).attr('href');
            $.ajax({
                url: url
            })
            .done(function(data){
                $('#pjax-modal').html(data);
                 $('#invoice-nextaction-modal').modal('show');
            });
            return false;
        }
        $('#pjax-modal').on('pjax:send', function(){
            ajaxProgressLoader('show', '.modal-content');
        });
        
        $('#pjax-modal').on('pjax:complete', function(){
            ajaxProgressLoader('hide', '.modal-content');
        });
        
    ";
$this->registerJs($js, yii\web\View::POS_END);
Pjax::end();
Modal::end();
?>  
<?php if ($todoExists) { ?>
    <?=
    Html::a('<i class="fa-circle-arrow-right rotate-360" style="font-size:48px"></i>', Url::to(['/workflow/next-action/next-action', 'peid' => $invoiceModel->processEntity->ProcessEntityID])
            , ['class' => 'pull-right mb5 animated bounceInLeft next-action',
        'onclick' => 'showModal(this); return false;',
        'data-pjax' => 0,
        'style' => 'color:#24cc42;',
        "data-original-title" => "Go to next action",
        "data-toggle" => "tooltip",]);
    ?>
<?php } ?>
<?php
$this->registerCss("
    .next-action:hover{
        opacity:1;
    }
    .next-action{
        position: fixed;
        z-index: 10000000;
        right: 10px;
        bottom: 10px;
        opacity:0.3;
    }
    ");
//letter modal
Modal::begin([
    'id' => 'letter-modal',
    'header' => '<h4 class="modal-title"><span class="fa-envelope"></span> Create letter</h4>',
]);
echo '<div id="leter-content"></div>';
$js = "
     //show CreateLetter modal
    function showModalCreateLetter(obj){
        var url = $(obj).attr('href');
        $.ajax({
            url:url,
            data:{lid:$('#drop-letter').val()}
        })
        .done(function(data){
            $('#leter-content').html(data);
        });
    }
    
";
$this->registerJs($js, yii\web\View::POS_END);
Modal::end();
?>

