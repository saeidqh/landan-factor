<?php

use app\modules\cashRequest\assets\FormCashRequestAsset;
use app\components\Utility;
use app\components\numToPersianWord;
use yii\helpers\Url;

FormCashRequestAsset::register($this);
?>

<!DOCTYPE html>
<html lang=en>
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <head>
        <meta charset=utf-8 />
        <style>
            .divTable {
                display: table;
                width: 100%;
            }

            .divTableRow {
                display: table-row;
            }
            td.image-right {
                width: 77px;
                height: 77px;
                background-size: 96px 96px;
                background-repeat: no-repeat;
                background-position: right top;
            }
            td.image-left {
                width: 77px;
                height: 77px;
                background-size: 96px 96px;
                background-repeat: no-repeat;
                background-position:left top;
            }
            td.image-center {
                width: 77px;
                height: 77px;
                background-size: 96px 96px;
                background-repeat: no-repeat;
                background-position: center top;
            }
            td.bottom {
                vertical-align: bottom;
            }
        </style>

        <style type="text/css">

            .rotateForm {
                -webkit-transform: rotate(-90deg);
                float: left;
            }
            
            td{
                word-wrap: break-word;
            }
            
            body{margin:0;padding:0;}
            *{
                -webkit-print-color-adjust: exact;
                font-size:14px;
            }
            
            table tbody tr td{line-height: 1;font-size: 14px;}
            span{display: block;}
            .font-size-12{font-size: 12px;}
            .font-size-10{font-size: 10px;}
            .font-size-11{font-size: 11px;}
            .font-size-8{font-size: 8px;}
            .font-bnazanin{font-family: 'B Nazanin','B Mitra','Tahoma'; font-size:14px;}
            .font-bmitra{font-family:'B Mitra','Tahoma'; font-size:14px;}
            .font-calibri{font-family: 'Calibri';}
            .font-tahoma{font-family: Tahoma;}
            .font-bold{font-weight:bold;}
            .font-inherit{font-weight:inherit;}
            .font-italic{font-style:italic;}
            .font-size-10{font-size:10px;}
            .font-size-12{font-size:12px;}
            .font-size-14{font-size:14px;}
            .p5{padding:5px;}
            .pr5{padding-right:5px;}
            .pl5{padding-left:5px;}

            .pr25{padding-right:25px !important;}
            .pl25{padding-left:25px !important;}

            .mt10{margin-top:10px;}

            .main-print-box{
                margin:0;
                padding:0;
                height:895px;
                width:680px;
            }

            .text-center{text-align:center}
            .text-right{text-align:right}
            .text-left{text-align:left}
            .border-trbl{border:solid 1px black;}
            .border-t{border-top:solid 1px black;}
            .border-r{border-right:solid 1px black;}
            .border-b{border-bottom:solid 1px black;}
            .border-l{border-left:solid 1px black;}
            .border-tb{border-bottom:solid 1px black;border-top:solid 1px black;}
            .border-lr{border-left:solid 1px black;border-right:solid 1px black;}
            .border-rbl{border-left:solid 1px black;border-right:solid 1px black;border-bottom:solid 1px black;}
            .border-trl{border-left:solid 1px black;border-right:solid 1px black;border-top:solid 1px black;}
            .border-tbl{border-left:solid 1px black;border-right:solid 1px black;border-top:solid 1px black;}

            .table-header-bg{background-color: #eeece1;}

            .blue-main-head{background-color: #95b3d7;}
            .blue-light-main-head{background-color: #cad8e6;;}
            .blue-sub-head{background-color: #95b3d7;}
            .blue-sub-content{background-color: #dbe5f1;}

            .green-main-head{background-color: #c2d69a;}
            .green-sub-head{background-color: #c2d69a;}
            .green-sub-content{background-color: #eaf1dd;}

            .lblue-main-head{background-color: #93cddd;}
            .lblue-sub-head{background-color: #93cddd;}
            .lblue-sub-content{background-color: #dbeef3;}

            .olive-main-head{background-color: #948b54;}
            .olive-sub-head{background-color: #c5be97;}
            .olive-sub-content{background-color: #eeece1;}
            .olive-check{background-color: #73c153;}

            .sign-box{display:block; z-index: 1;margin-bottom:-25px; }
            .sign-box img { width: 70px; height:55px; z-index: 1;}

            .signer-name{z-index: 10;margin-top:10px;margin-bottom:5px;}
            .logo-psa img{width:78px;height:60px;}
            .logo-psa-admin img{width:78px;}
            .logo-cando img{width:98px;height:20px;}

            .fake-lock{
                position:fixed; 
                top:0; 
                right:0;
                bottom: 0;
                left:0;
                z-index: 100;

            }


        </style>
        <script type="text/javascript">
            $(document).ready(function () {
                $(document).on('contextmenu', function (event) {
                    window.print();
                    return false;

                });

                $(document).keydown(function (event) {
                    if (event.which == 123 || event.which == 81 || event.which == 83) {
                        event.preventDefault();
                    }
                });
            });
            function printDoc() {

            }

        </script>

    </head>
    
    <body>
        <div class='main-print-box font-tahoma' dir="rtl" >
            <table class="border-trl text-center" style="width:100%;table-layout: fixed;" cellspacing="0" width="595px"  heigh="100%">

                <colgroup>
                    <col style='width:13px;'/>  <!--Line (1)-->
                    <col style='width:13px;'/>  <!--Line (2)-->
                    <col style='width:13px;'/>  <!--Line (3)-->
                    <col style='width:13px;'/>  <!--Line (4)-->
                    <col style='width:13px;'/>  <!--Line (5)-->
                    <col style='width:13px;'/>  <!--Line (6)-->
                    <col style='width:13px;'/>  <!--Line (7)-->
                    <col style='width:13px;'/>  <!--Line (8)-->
                    <col style='width:13px;'/>  <!--Line (9)-->
                    <col style='width:13px;'/>  <!--Line (10)-->
                    <col style='width:13px;'/>  <!--Line (11)-->
                    <col style='width:13px;'/>  <!--Line (12)-->
                    <col style='width:13px;'/>  <!--Line (13)-->
                    <col style='width:13px;'/>  <!--Line (14)-->
                    <col style='width:13px;'/>  <!--Line (15)-->
                    <col style='width:13px;'/>  <!--Line (16)-->
                    <col style='width:13px;'/>  <!--Line (17)-->
                    <col style='width:13px;'/>  <!--Line (18)-->
                    <col style='width:13px;'/>  <!--Line (19)-->
                    <col style='width:13px;'/>  <!--Line (20)-->
                    <col style='width:13px;'/>  <!--Line (21)-->
                    <col style='width:13px;'/>  <!--Line (22)-->
                    <col style='width:13px;'/>  <!--Line (23)-->
                    <col style='width:13px;'/>  <!--Line (24)-->
                    <col style='width:13px;'/>  <!--Line (25)-->
                    <col style='width:13px;'/>  <!--Line (26)-->
                    <col style='width:13px;'/>  <!--Line (27)-->
                    <col style='width:13px;'/>  <!--Line (28)-->
                    <col style='width:13px;'/>  <!--Line (29)-->
                    <col style='width:13px;'/>  <!--Line (30)-->
                    <col style='width:13px;'/>  <!--Line (31)-->
                    <col style='width:13px;'/>  <!--Line (32)-->
                    <col style='width:13px;'/>  <!--Line (33)-->
                    <col style='width:13px;'/>  <!--Line (34)-->
                    <col style='width:13px;'/>  <!--Line (35)-->
                    <col style='width:13px;'/>  <!--Line (36)-->
                    <col style='width:13px;'/>  <!--Line (37)-->
                    <col style='width:13px;'/>  <!--Line (38)-->
                    <col style='width:13px;'/>  <!--Line (39)-->
                    <col style='width:13px;'/>  <!--Line (40)-->
                    <col style='width:13px;'/>  <!--Line (41)-->
                    <col style='width:13px;'/>  <!--Line (42)-->
                    <col style='width:13px;'/>  <!--Line (43)-->
                    <col style='width:13px;'/>  <!--Line (44)-->
                    <col style='width:13px;'/>  <!--Line (45)-->
                    <col style='width:13px;'/>  <!--Line (46)-->
                    <col style='width:13px;'/>  <!--Line (47)-->
                    <col style='width:13px;'/>  <!--Line (48)-->
                    <col style='width:13px;'/>  <!--Line (49)-->
                    <col style='width:13px;'/>  <!--Line (50)-->
                </colgroup>
                
                <thead>
                    <tr class="table-header-bg" style="width:100%">
                        <th rowspan="3" colspan="8" class="border-t border-lr"  style="line-height: 4px">

                        <span class='logo-psa-admin' ><img src="<?= $logo_admin; ?>" style="" /></span>
                        <br>
                        <br>
                        <p style="font-size: 6px;margin-bottom: -19px;">
                            <?= "Invoice ID : " ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= $processEntityModel->invoice->InvoiceID; ?>
                        </p>
                        &nbsp;
                        </th>
                <th class="border-t border-lr  " colspan="34" style="line-height: 22px">
                    <p style="font-size: 10px;margin-bottom: -2px;margin-top: -7px;"><?= 'Integrated Management system'; ?></p>
                    <p style="font-size: 16px;margin-bottom: -42px;margin-top: -2px;"><?= 'Invoice Approval Coversheet'; ?></p>
                &nbsp;</p>
                </th>
                <th rowspan="3"  colspan="8" class="border-t border-lr font-calibri font-italic font-bold font-size-10" width='15%'  style="line-height: 12px">
                    <span class='logo-psa' ><img src="<?= $logo; ?>" style="" /></span>
                <p style="font-size: 6px;margin-bottom: -15px;"><?= 'Offshore Professional Company' ?></p>
                &nbsp;</th>
                </tr>
                <tr class="table-header-bg font-size-10"  style="line-height: 10px">
                    <th class="border-t border-lr font-inherit font-size-10" colspan="7"><?= 'Serial' ?>&nbsp;</th>
                    <th class="border-t border-l font-inherit font-size-10" colspan="7"><?= 'Doc Type' ?>&nbsp;</th>
                    <th class="border-t border-l font-inherit font-size-10" colspan="6" ><?= 'Descipline' ?>&nbsp;</th>
                    <th class="border-t border-l font-inherit font-size-10" colspan="7"><?= 'Field Code' ?>&nbsp;</th>
                    <th class="border-t border-l font-inherit font-size-10" colspan="7"><?= 'Prj ID' ?>&nbsp;</th>
                </tr>
                <tr class="table-header-bg font-size-10"  style="line-height: 10px">
                    <th class="border-t border-lr font-inherit font-size-10" colspan="7"><?= '0012-67' ?>&nbsp;</th>
                    <th class="border-t border-l font-inherit font-size-10" colspan="7"><?= 'FO' ?>&nbsp;</th>
                    <th class="border-t border-l font-inherit font-size-10" colspan="6" ><?= 'PM' ?>&nbsp;</th>
                    <th class="border-t border-l font-inherit font-size-10" colspan="7"><?= '000' ?>&nbsp;</th>
                    <th class="border-t border-l font-inherit font-size-10" colspan="7"><?= 'PGFK' ?>&nbsp;</th>
                </tr>

                
                
                </thead>
                <tbody>
                    <!---  Part Two ---->
                    <!--Invoice Specification-->
                    <tr class="border-trbl ">
                        <td class="border-rbl text-right border-trbl table-header-bg blue-main-head" colspan="50" >
                            <span class="font-bold pr5 pl5" style="text-align: center;font-size: 12px;margin-top: 5px;margin-bottom: -16px;"> 
                                <?php echo 'Invoice Specification' ?></span>
                            </span>
                            &nbsp;</td>
                    </tr>
                    <tr class="border-tbl">
                        <td class="border-rbl border-tbl blue-light-main-head text-center" colspan="25" >
                            <span class="font-bold pr5 pl5 font-size-10" style="margin-top: 10px;margin-bottom: -10px;"> 
                                <?php echo 'Cost Code' ?></span>
                            </span>
                            &nbsp;</td>
                        <td class="border-rbl border-tbl blue-light-main-head text-center" colspan="25" >
                            <span class="font-bold pr5 pl5 font-size-10" style="margin-top: 10px;margin-bottom: -10px;"> 
                                <?php echo 'Contract Sepcification' ?></span>
                            </span>
                            &nbsp;</td>
                    </tr>
                    <tr class="font-size-10 border-tbl"  style="line-height: 10px;text-align: left;">
                        <th class="border-t border-lr font-inherit font-size-10" colspan="18"><?= yii\helpers\Html::encode($projectString); ?>&nbsp;</th>
                        <th class="border-t border-l font-inherit font-size-10 text-center" colspan="7" ><?= 'Project' ?>&nbsp;</th>
                        <th class="border-t border-l font-inherit font-size-10" colspan="17"><?=  yii\helpers\Html::encode($processEntityModel->invoice->contract->SubcontractorsList) ?>&nbsp;</th>
                        <th class="border-t border-l font-inherit font-size-10 text-center" colspan="8"><?= 'Subcontractor' ?>&nbsp;</th>
                    </tr>
                    <tr class="font-size-10 border-tbl"  style="line-height: 10px;text-align: left;">
                        <th class="border-t border-lr font-inherit font-size-10" colspan="18"><?= '' ?>&nbsp;</th>
                        <th class="border-t border-l font-inherit font-size-10 text-center" colspan="7" ><?= 'PlatForm' ?>&nbsp;</th>
                        <th class="border-t border-l font-inherit font-size-10" colspan="17" rowspan="2"><?=  yii\helpers\Html::encode($processEntityModel->invoice->contract->ContractSubject) ?>&nbsp;</th>
                        <th class="border-t border-l font-inherit font-size-10 text-center" colspan="8" rowspan="2"><?= 'Contract Subject' ?>&nbsp;</th>
                    </tr>
                    <tr class="font-size-10 border-tbl"  style="line-height: 10px;text-align: left;">
                        <th class="border-t border-lr font-inherit font-size-10" colspan="18"><?= '' ?>&nbsp;</th>
                        <th class="border-t border-l font-inherit font-size-10 text-center" colspan="7" ><?= 'Location' ?>&nbsp;</th>
                    </tr>
                    <tr class="font-size-10 border-tbl"  style="line-height: 10px;text-align: left;">
                        <th class="border-t border-lr font-inherit font-size-10" colspan="18"><?= '' ?>&nbsp;</th>
                        <th class="border-t border-l font-inherit font-size-10 text-center" colspan="7" ><?= 'Rig' ?>&nbsp;</th>
                        <th class="border-t border-l font-inherit font-size-10" colspan="17"><?=  yii\helpers\Html::encode($processEntityModel->invoice->contract->ContractNo) ?>&nbsp;</th>
                        <th class="border-t border-l font-inherit font-size-10 text-center" colspan="8"><?= 'Contract No' ?>&nbsp;</th>
                    </tr>
                    <tr class="font-size-10 border-tbl"  style="line-height: 10px;text-align: left;">
                        <th class="border-t border-lr font-inherit font-size-10" colspan="18"><?= ''; ?>&nbsp;</th>
                        <th class="border-t border-l font-inherit font-size-10 text-center" colspan="7" ><?= 'Well' ?>&nbsp;</th>
                        <th class="border-t border-l font-inherit font-size-10 text-center" colspan="2"><?= isset($processEntityModel->invoice->contract->lastRider->currency2->CurrencyTitle)? yii\helpers\Html::encode($processEntityModel->invoice->contract->lastRider->currency2->CurrencyTitle) : null; ?>&nbsp;</th>
                        <th class="border-t border-l font-inherit font-size-10" colspan="6"><?= $processEntityModel->invoice->contract->lastRider->Price2 ?>&nbsp;</th>
                        <th class="border-t border-l font-inherit font-size-10 text-center" colspan="2"><?=  yii\helpers\Html::encode($processEntityModel->invoice->contract->lastRider->currency1->CurrencyTitle) ?>&nbsp;</th>
                        <th class="border-t border-l font-inherit font-size-10" colspan="7"><?= $processEntityModel->invoice->contract->lastRider->Price1 ?>&nbsp;</th>
                        <th class="border-t border-l font-inherit font-size-10 text-center" colspan="8"><?= 'Contract Price' ?>&nbsp;</th>
                    </tr>
                    <tr class="font-size-10 border-tbl"  style="line-height: 10px;text-align: left;">
                        <th class="border-t border-lr font-inherit font-size-10" colspan="18"><?= $processEntityModel->invoice->contract->subProjectList ?>&nbsp;</th>
                        <th class="border-t border-l font-inherit font-size-10 text-center" colspan="7" ><?= 'EPS' ?>&nbsp;</th>
                        <th class="border-t border-l font-inherit font-size-10" colspan="2"><?= isset($processEntityModel->invoice->contract->lastRider->currency2->CurrencyTitle)?  yii\helpers\Html::encode($processEntityModel->invoice->contract->lastRider->currency2->CurrencyTitle) :null; ?>&nbsp;</th>
                        <th class="border-t border-l font-inherit font-size-10" colspan="6"><?= Utility::moneyFormatByCurrency($processEntityModel->invoice->contract->lastRider->Price2-$processEntityModel->invoice->approved2,$processEntityModel->invoice->contract->lastRider->currency1->CurrencyTitle); ?>&nbsp;</th>
                        <th class="border-t border-l font-inherit font-size-10" colspan="2"><?=  yii\helpers\Html::encode($processEntityModel->invoice->contract->lastRider->currency1->CurrencyTitle) ?>&nbsp;</th>
                        <th class="border-t border-l font-inherit font-size-10" colspan="7"><?= Utility::moneyFormatByCurrency($processEntityModel->invoice->contract->lastRider->Price1-$processEntityModel->invoice->approved1,$processEntityModel->invoice->contract->lastRider->currency1->CurrencyTitle); ?>&nbsp;</th>
                        <th class="border-t border-l font-inherit font-size-10 text-center" colspan="8"><?= 'Remain Contract Price' ?>&nbsp;</th>
                    </tr>
                    
                    <tr class="border-trbl">
                        <td class="border-rbl border-tbl blue-light-main-head text-center" colspan="50" >
                            <span class="font-bold pr5 pl5 font-size-10" style="margin-top: 10px;margin-bottom: -10px;"> 
                                <?php echo 'Invoice Specification' ?></span>
                            </span>
                            &nbsp;</td>
                    </tr>

                    <!--Invoice Specification Value-->
                    <tr class="font-size-10 border-tbl"  style="line-height: 10px;text-align: left;">
                        <th class="border-t border-lr font-inherit font-size-10" colspan="8"><?= $sgn_01['ActionDateHijri'] ?>&nbsp;</th>
                        <th class="border-t border-l font-inherit font-size-10 text-center" colspan="7" rowspan="2"><?= 'Receipt date' ?>&nbsp;</th>
                        <th class="border-t border-l font-inherit font-size-10" colspan="6" ><?= $processEntityModel->invoice->CutOffDateHijri; ?>&nbsp;</th>
                        <th class="border-t border-l font-inherit font-size-10 text-center" colspan="4"><?= "To" ?>&nbsp;</th>
                        <th class="border-t border-l font-inherit font-size-10" colspan="5"><?= $processEntityModel->invoice->fromCutOffDateHijri; ?>&nbsp;</th>
                        <th class="border-t border-l font-inherit font-size-10 text-center" colspan="6" rowspan="2"><?= "Invoice Period"; ?>&nbsp;</th>
                        <th class="border-t border-l font-inherit font-size-10" colspan="6" rowspan="2" style="word-wrap: break-word;"><?=  yii\helpers\Html::encode($processEntityModel->invoice->Title) ?>&nbsp;</th>
                        <th class="border-t border-l font-inherit font-size-10 text-center" colspan="8" rowspan="2"><?= 'Invoice No' ?>&nbsp;</th>
                    </tr>
                    <tr class="font-size-10 border-tbl"  style="line-height: 10px;text-align: left;">
                        <th class="border-t border-lr font-inherit font-size-10" colspan="8"><?= isset($sgn_01['ActionDateHijri'])?Utility::convertDate(str_split($sgn_01['ActionDateHijri'], 10)[0],false):""; ?>&nbsp;</th>
                        <th class="border-t border-l font-inherit font-size-10" colspan="6" ><?= Utility::convertDate($processEntityModel->invoice->CutOffDateHijri,false); ?>&nbsp;</th>
                        <th class="border-t border-l font-inherit font-size-10 text-center" colspan="4"><?= "To" ?>&nbsp;</th>
                        <th class="border-t border-l font-inherit font-size-10" colspan="5"><?= !empty($processEntityModel->invoice->fromCutOffDateHijri)?Utility::convertDate($processEntityModel->invoice->fromCutOffDateHijri,false):""; ?>&nbsp;</th>
                    </tr>
                    
                    <tr class="font-size-10 border-tbl"  style="line-height: 10px;text-align: left;">
                        <th class="border-t border-lr font-inherit font-size-10" colspan="42"><?=  yii\helpers\Html::encode($processEntityModel->invoice->Description); ?>&nbsp;</th>
                        <th class="border-t border-l font-inherit font-size-10 text-center" colspan="8"><?= 'Description' ?>&nbsp;</th>
                    </tr>
                    
                    <tr class="font-size-10 border-tbl"  style="line-height: 10px;text-align: left;background-color: lightgray;text-align: center;">
                        <th class="border-t border-lr font-inherit font-size-10" colspan="8" ><?= 'Project'; ?>&nbsp;</th>
                        <th class="border-t border-l font-inherit font-size-10" colspan="3"><?= "Cur 2"; ?>&nbsp;</th>
                        <th class="border-t border-l font-inherit font-size-10" colspan="7"><?= "Camulative 2" ?>&nbsp;</th>
                        <th class="border-t border-l font-inherit font-size-10" colspan="7"><?= "Periodic 2"; ?>&nbsp;</th>
                        <th class="border-t border-l font-inherit font-size-10" colspan="3"><?= "Cur 1"; ?>&nbsp;</th>
                        <th class="border-t border-l font-inherit font-size-10" colspan="7"><?= "Camulative 1" ?>&nbsp;</th>
                        <th class="border-t border-l font-inherit font-size-10" colspan="7"><?= "Periodic 1"; ?>&nbsp;</th>
                        <th class="border-t border-l font-inherit font-size-10" colspan="8"><?= 'Amount' ?>&nbsp;</th>
                    </tr>
                    <?php foreach ($projectList as $key => $value) { ?>
                        <tr class="font-size-10 border-trbl"  style="line-height: 10px;text-align: left;">
                            <th class="border-t border-lr font-inherit font-size-10 text-center" colspan="8"><?= $value["EPSTitle"]; ?>&nbsp;</th>
                            <th class="border-t border-l font-inherit font-size-10" colspan="3"><?=  yii\helpers\Html::encode($processEntityModel->invoice->Currency2Title); ?>&nbsp;</th>
                            <th class="border-t border-l font-inherit font-size-10" colspan="7"><?= Utility::moneyFormatByCurrency($processEntityModel->invoice->cumulativeRequested2ByCostCode(1, $value["NodeID"]), $processEntityModel->invoice->Currency2Title) ; ?>&nbsp;</th>
                            <th class="border-t border-l font-inherit font-size-10" colspan="7"><?= Utility::moneyFormatByCurrency($processEntityModel->invoice->requested2ByCostCode($value["NodeID"]), $processEntityModel->invoice->Currency2Title); ?>&nbsp;</th>
                            <th class="border-t border-l font-inherit font-size-10" colspan="3"><?=  yii\helpers\Html::encode($processEntityModel->invoice->Currency1Title); ?>&nbsp;</th>
                            <th class="border-t border-l font-inherit font-size-10" colspan="7"><?= Utility::moneyFormatByCurrency($processEntityModel->invoice->cumulativeRequested1ByCostCode(1, $value["NodeID"]), $processEntityModel->invoice->Currency1Title); ?>&nbsp;</th>
                            <th class="border-t border-l font-inherit font-size-10" colspan="7"><?= Utility::moneyFormatByCurrency($processEntityModel->invoice->requested1ByCostCode($value["NodeID"]), $processEntityModel->invoice->Currency1Title); ?>&nbsp;</th> &nbsp;</th>
                            <th class="border-t border-l font-inherit font-size-10 text-center" colspan="8"><?= 'Request' ?>&nbsp;</th>
                        </tr>
                        
                        <tr class="font-size-10 border-tbl"  style="line-height: 10px;text-align: left;">
                            <th class="border-t border-lr font-inherit font-size-10 text-center" colspan="8"><?= $value["EPSTitle"]; ?>&nbsp;</th>
                            <th class="border-t border-l font-inherit font-size-10" colspan="3"><?=  yii\helpers\Html::encode($processEntityModel->invoice->Currency2Title); ?>&nbsp;</th>
                            <th class="border-t border-l font-inherit font-size-10" colspan="7"><?= Utility::moneyFormatByCurrency($processEntityModel->invoice->cumulativeApproved2ByCostCode(1, $value["NodeID"]), $processEntityModel->invoice->Currency2Title) ; ?>&nbsp;</th>
                            <th class="border-t border-l font-inherit font-size-10" colspan="7"><?= Utility::moneyFormatByCurrency($processEntityModel->invoice->approved2ByCostCode($value["NodeID"]), $processEntityModel->invoice->Currency2Title); ?>&nbsp;</th>
                            <th class="border-t border-l font-inherit font-size-10" colspan="3"><?=  yii\helpers\Html::encode($processEntityModel->invoice->Currency1Title); ?>&nbsp;</th>
                            <th class="border-t border-l font-inherit font-size-10" colspan="7"><?= Utility::moneyFormatByCurrency($processEntityModel->invoice->cumulativeApproved1ByCostCode(1, $value["NodeID"]), $processEntityModel->invoice->Currency1Title); ?>&nbsp;</th>
                            <th class="border-t border-l font-inherit font-size-10" colspan="7"><?= Utility::moneyFormatByCurrency($processEntityModel->invoice->approved1ByCostCode($value["NodeID"]), $processEntityModel->invoice->Currency1Title); ?>&nbsp;</th> &nbsp;</th>
                            <th class="border-t border-l font-inherit font-size-10 text-center" colspan="8"><?= 'Approved' ?>&nbsp;</th>
                        </tr>

                    <?php } ?>

                    
                    <!--Invoice Specification-->
                    <tr class="border-tbl">
                        <td class="border-rbl text-right border-tbl table-header-bg blue-main-head" colspan="50" >
                            <span class="font-bold pr5 pl5" style="text-align: center;margin-top: 10px;margin-bottom: -10px;"> 
                                <?php echo 'Approval & Notes' ?></span>
                            </span>
                            &nbsp;</td>
                    </tr>



                    <?php
//    <editor-fold desc="----< Technical (Eng ,Rig ,Log ,...) Dept. In Site(kish ,Asloyeh ,...) >----" defaultstate="collapsed" >
                    ?>
                    <!--Invoice Specification-->
                    <tr class="border-tbl ">
                        <td class="border-rbl border-tbl blue-light-main-head text-center" colspan="50" >
                            <span class="font-bold pr5 pl5 font-size-10" style="margin-top: 10px;margin-bottom: -10px;direction: ltr;"> 
                                <?php echo 'Technical (Eng ,Rig ,Log ,...) Dept. In Site(kish ,Asloyeh ,...)' ?></span>
                            </span>
                            &nbsp;</td>
                    </tr>
                    
                    <!--Invoice Specification-->
                    <tr class="border-tbl text-left">
                        <td class="border-trbl" colspan="1" rowspan="2">    
                            <span class="font-bold pr5 pl5 font-size-10" style="transform: rotate(-90deg);width: 64px;margin-top: -4px;margin-right: -33px;"> 
                                <?php echo 'Max.7 days' ?></span></span></span>
                            </span>

                        </td>
                        <td class="border-t border-trl" colspan="49" style="text-align: center;">    
                            <span class="pr5 pl5 font-size-10" style="margin-top: 2px;margin-bottom: 10px;"> 
                                <?php echo 'Quality & Quantity of the invoice has been checked and approved ' ?>
                            </span>

                        </td>

                    </tr>
                    
                    <tr class="border-tbl text-left">

                        <td class="border-r border-b" colspan="17"  style="height: 40px;">    
                            <span class="pr5 pl5 font-size-10" style="margin-top: -11px;margin-bottom: 2px;"> 
                                <?php echo !empty($sgn_03['uid']) ?$sgn_03['description']:':Remarks'; ?></span>
                            </span>
                            <?= !empty($sgn_03['uid']) ? yii\helpers\Html::img(['usersignature', 'uid' => $sgn_03['uid'], 'oid' => $sgn_03['oid']], [
                                        'style' => 'width: 90px;margin-top: -55px;margin-bottom: -56px;margin-left: 137px;'
                                    ]) : null;
                            ?>
                            <span class="font-bold pr5 pl5 font-size-10"> 
                                <?php echo!empty($sgn_03['AmountApproved1']) ? "Amount1 : ".  Utility::moneyFormatByCurrency($sgn_03['AmountApproved1'],$processEntityModel->invoice->Currency1Title) ." ".$sgn_03['Currency1Title'] : ''; ?>
                            </span>
                            <span class="font-bold pr5 pl5 font-size-10"> 
                                <?php echo!empty($sgn_03['AmountApproved2']) ? "Amount2 : ".  Utility::moneyFormatByCurrency($sgn_03['AmountApproved2'],$processEntityModel->invoice->Currency2Title) ." ".$sgn_03['Currency2Title'] : ''; ?>
                            </span>
                            <span class="font-bold pr5 pl5 font-size-10"> 
                                <?php echo!empty($sgn_03['UserName']) ? $sgn_03['UserName'] : ''; ?>
                            </span>

                            <span class="font-bold pr5 pl5 font-size-10" style="margin-bottom: -1px;"> 
                                <?php echo!empty($sgn_03['ActionDateHijri']) ? $sgn_03['ActionDateHijri'] : ''; ?>
                            </span>
                        </td>
                        
                        <td class="border-b" colspan="16">
                            <span class="pr5 pl5 font-size-10" style="margin-top: -11px;margin-bottom: 2px;"> 
                                <?php echo !empty($sgn_02['uid']) ?$sgn_02['description']:':Remarks'; ?></span>
                            </span>
                            <?php
                            echo!empty($sgn_02['uid']) ? yii\helpers\Html::img(['usersignature', 'uid' => $sgn_02['uid'], 'oid' => $sgn_02['oid']], [
                                        'style' => 'width: 90px;margin-top: -55px;margin-bottom: -56px;margin-left: 137px;'
                                    ]) : null;
                            ?>
                            
                            <span class="font-bold pr5 pl5 font-size-10"> 
                                <?php echo!empty($sgn_02['AmountApproved1']) ? "Amount1 : ".  Utility::moneyFormatByCurrency($sgn_02['AmountApproved1'],$processEntityModel->invoice->Currency1Title) ." ".$sgn_02['Currency1Title'] : ''; ?>
                            </span>
                            <span class="font-bold pr5 pl5 font-size-10"> 
                                <?php echo!empty($sgn_02['AmountApproved2']) ? "Amount2 : ".  Utility::moneyFormatByCurrency($sgn_02['AmountApproved2'],$processEntityModel->invoice->Currency2Title) ." ".$sgn_02['Currency2Title'] : ''; ?>
                            </span>
                            <span class="font-bold pr5 pl5 font-size-10"> 
                                <?php echo!empty($sgn_02['UserName']) ? $sgn_02['UserName'] : ''; ?>
                            </span>
                            
                            <span class="font-bold pr5 pl5 font-size-10" style="margin-bottom: -1px;"> 
                            <?php echo!empty($sgn_02['ActionDateHijri']) ? $sgn_02['ActionDateHijri'] : ''; ?></span>
                        </td>
                        
                        <td class="border-l border-b" colspan="16">
                            <span class="pr5 pl5 font-size-10" style="margin-top: -11px;margin-bottom: 2px;"> 
                                <?php echo !empty($sgn_01['uid']) ?$sgn_01['description']:':Remarks'; ?></span>
                            </span>
                            <?php
                            echo!empty($sgn_01['uid']) ? yii\helpers\Html::img(['usersignature', 'uid' => $sgn_01['uid'], 'oid' => $sgn_01['oid']], [
                                        'style' => 'width: 90px;margin-top: -55px;margin-bottom: -56px;margin-left: 137px;'
                                    ]) : null;
                            ?>
                            
                            <span class="font-bold pr5 pl5 font-size-10"> 
                                <?php echo!empty($sgn_01['AmountApproved1']) ? "Amount1 : ".  Utility::moneyFormatByCurrency($sgn_01['AmountApproved1'],$processEntityModel->invoice->Currency1Title) ." ".$sgn_01['Currency1Title'] : ''; ?>
                            </span>
                            <span class="font-bold pr5 pl5 font-size-10"> 
                                <?php echo!empty($sgn_01['AmountApproved2']) ? "Amount2 : ".  Utility::moneyFormatByCurrency($sgn_01['AmountApproved2'],$processEntityModel->invoice->Currency2Title) ." ".$sgn_01['Currency2Title'] : ''; ?>
                            </span>
                            <span class="font-bold pr5 pl5 font-size-10"> 
                                <?php echo!empty($sgn_01['UserName']) ? $sgn_01['UserName'] : ''; ?>
                            </span>
                            
                            <span class="font-bold pr5 pl5 font-size-10" style="margin-bottom: -1px;"> 
                            <?php echo!empty($sgn_01['ActionDateHijri']) ? $sgn_01['ActionDateHijri'] : ''; ?></span>
                        </td>

                    </tr>


                    <?php
//    </editor-fold>
                    ?>



                    <?php
//    <editor-fold desc="----< Contract Engineering Dept >----" defaultstate="collapsed" >
                    ?>
                    <tr class="border-tbl ">
                        <td class="border-rbl border-tbl blue-light-main-head text-center" colspan="50" >
                            <span class="font-bold pr5 pl5 font-size-10" style="margin-top: 10px;margin-bottom: -10px;"> 
                                <?php echo 'Invoice Dept' ?></span>
                            </span>
                            &nbsp;</td>
                    </tr>
                    
                    <!--Invoice Specification-->
                    <tr class="border-tbl text-left">
                        <td class="border-trbl" colspan="1" rowspan="2">    
                            <span class="font-bold pr5 pl5 font-size-10" style="transform: rotate(-90deg);width: 64px;margin-top: -4px;margin-right: -33px;"> 
                                <?php echo 'Max.8 days' ?></span></span></span>
                            </span>

                        </td>
                        <td class="border-t border-trl" colspan="49" style="text-align: left;">    
                            <span class="pr5 pl5 font-size-10" style="margin-top: 2px;margin-bottom: 10px;"> 
                                <?php echo 'Scope compatibility has been checked and approved.based on contract terms&conditions and other departments comments; All releted dedications applied' ?>
                            </span>

                        </td>

                    </tr>
                    
                    <tr class="border-tbl text-left">
                        <td class="border-r border-b" colspan="25">
                            <span class="pr5 pl5 font-size-10" style="margin-top: -11px;margin-bottom: 2px;"> 
                                <?php echo !empty($sgn_04['uid']) ?$sgn_04['description']:':Remarks'; ?></span>
                            </span>
                            <?php
                            echo!empty($sgn_04['uid']) ? yii\helpers\Html::img(['usersignature', 'uid' => $sgn_04['uid'], 'oid' => $sgn_04['oid']], [
                                        'style' => 'width: 90px;margin-top: -55px;margin-bottom: -56px;margin-left: 137px;'
                                    ]) : null;
                            ?>
                            <span class="font-bold pr5 pl5 font-size-10"> 
                                <?php echo!empty($sgn_04['AmountApproved1']) ? "Amount1 : ".  Utility::moneyFormatByCurrency($sgn_04['AmountApproved1'],$processEntityModel->invoice->Currency1Title) ." ".$sgn_04['Currency1Title'] : ''; ?>
                            </span>
                            <span class="font-bold pr5 pl5 font-size-10"> 
                                <?php echo!empty($sgn_04['AmountApproved2']) ? "Amount2 : ".  Utility::moneyFormatByCurrency($sgn_04['AmountApproved2'],$processEntityModel->invoice->Currency2Title) ." ".$sgn_04['Currency2Title'] : ''; ?>
                            </span>
                            <span class="font-bold pr5 pl5 font-size-10"> 
                                <?php echo!empty($sgn_04['UserName']) ? $sgn_04['UserName'] : ''; ?>
                            </span>
                            
                            <span class="font-bold pr5 pl5 font-size-10" style="margin-bottom: -1px;"> 
                            <?php echo!empty($sgn_04['ActionDateHijri']) ? $sgn_04['ActionDateHijri'] : ''; ?></span>
                        </td>

                        <td class="border-l border-b" colspan="24">    

                        </td>
                        
                        

                    </tr>


                    <?php
//    </editor-fold>
                    ?>

                    

                    <?php
//    <editor-fold desc="----< Planning & PMO Dept.(Cost Controle) >----" defaultstate="collapsed" >
                    ?>
                    <tr class="border-tbl ">
                        <td class="border-rbl border-tbl blue-light-main-head text-center" colspan="50" >
                            <span class="font-bold pr5 pl5 font-size-10" style="margin-top: 10px;margin-bottom: -10px;direction: ltr;"> 
                                <?php echo 'Planning & PMO Dept(Cost Control)' ?></span>
                            </span>
                            &nbsp;</td>
                    </tr>
                    
                    <!--Invoice Specification-->
                    <tr class="border-tbl text-left">
                        <td class="border-trbl" colspan="1" rowspan="2">    
                            <span class="font-bold pr5 pl5 font-size-10" style="transform: rotate(-90deg);width: 64px;margin-top: -4px;margin-right: -33px;"> 
                                <?php echo 'Max.2 days' ?></span></span></span>
                            </span>

                        </td>
                        <td class="border-t border-trl" colspan="49" style="text-align: left;">    
                            <span class="pr5 pl5 font-size-10" style="margin-top: 2px;margin-bottom: 10px;"> 
                                <?php echo "Invoice and it's supporting documents has been checked and recorded." ?>
                            </span>

                        </td>

                    </tr>
                    
                    <tr class="border-tbl text-left">

                        <td class="border-r border-b" colspan="24" style="height: 40px;">    
                           <span class="pr5 pl5 font-size-10" style="margin-top: -11px;margin-bottom: 2px;"> 
                                <?php echo !empty($sgn_06['uid']) ?$sgn_06['description']:':Remarks'; ?></span>
                            </span>
                            <?php
                            echo!empty($sgn_06['uid']) ? yii\helpers\Html::img(['usersignature', 'uid' => $sgn_06['uid'], 'oid' => $sgn_06['oid']], [
                                        'style' => 'width: 90px;margin-top: -55px;margin-bottom: -56px;margin-left: 137px;'
                                    ]) : null;
                            ?>
                            <span class="font-bold pr5 pl5 font-size-10"> 
                                <?php echo!empty($sgn_06['AmountApproved1']) ? "Amount1 : ".  Utility::moneyFormatByCurrency($sgn_06['AmountApproved1'],$processEntityModel->invoice->Currency1Title) ." ".$sgn_06['Currency1Title'] : ''; ?>
                            </span>
                            <span class="font-bold pr5 pl5 font-size-10"> 
                                <?php echo!empty($sgn_06['AmountApproved2']) ? "Amount2 : ".  Utility::moneyFormatByCurrency($sgn_06['AmountApproved2'],$processEntityModel->invoice->Currency2Title) ." ".$sgn_06['Currency2Title'] : ''; ?>
                            </span>
                            <span class="font-bold pr5 pl5 font-size-10"> 
                                <?php echo!empty($sgn_06['UserName']) ? $sgn_06['UserName'] : ''; ?>
                            </span>
                            
                            <span class="font-bold pr5 pl5 font-size-10" style="margin-bottom: -1px;"> 
                            <?php echo!empty($sgn_06['ActionDateHijri']) ? $sgn_06['ActionDateHijri'] : ''; ?></span>
                        </td>
                        
                        <td class="border-l border-b" colspan="25">
                            <span class="pr5 pl5 font-size-10" style="margin-top: -11px;margin-bottom: 2px;"> 
                                <?php echo !empty($sgn_05['uid']) ?$sgn_05['description']:':Remarks'; ?></span>
                            </span>
                            <?php
                            echo!empty($sgn_05['uid']) ? yii\helpers\Html::img(['usersignature', 'uid' => $sgn_05['uid'], 'oid' => $sgn_05['oid']], [
                                        'style' => 'width: 90px;margin-top: -55px;margin-bottom: -56px;margin-left: 137px;'
                                    ]) : null;
                            ?>
                            <span class="font-bold pr5 pl5 font-size-10"> 
                                <?php echo!empty($sgn_05['AmountApproved1']) ? "Amount1 : ".  Utility::moneyFormatByCurrency($sgn_05['AmountApproved1'],$processEntityModel->invoice->Currency1Title) ." ".$sgn_05['Currency1Title'] : ''; ?>
                            </span>
                            <span class="font-bold pr5 pl5 font-size-10"> 
                                <?php echo!empty($sgn_05['AmountApproved2']) ? "Amount2 : ".  Utility::moneyFormatByCurrency($sgn_05['AmountApproved2'],$processEntityModel->invoice->Currency2Title) ." ".$sgn_05['Currency2Title'] : ''; ?>
                            </span>
                            <span class="font-bold pr5 pl5 font-size-10"> 
                                <?php echo!empty($sgn_05['UserName']) ? $sgn_05['UserName'] : ''; ?>
                            </span>
                            
                            <span class="font-bold pr5 pl5 font-size-10" style="margin-bottom: -1px;"> 
                            <?php echo!empty($sgn_05['ActionDateHijri']) ? $sgn_05['ActionDateHijri'] : ''; ?></span>
                        </td>

                    </tr>

                    <?php
//    </editor-fold>
                    ?>



<?php
//    <editor-fold desc="----< Project Manager >----" defaultstate="collapsed" >
?>
                    <!--Invoice Specification-->
                    <tr class="border-tbl ">
                        <td class="border-rbl border-tbl blue-light-main-head text-center" colspan="25" >
                            <span class="font-bold pr5 pl5 font-size-10" style="margin-top: 10px;margin-bottom: -10px;"> 
                                <?php echo 'Project Manager' ?></span>
                            </span>
                            &nbsp;</td>
                        
                        <td class="border-rbl border-tbl blue-light-main-head text-center" colspan="25" >
                            <span class="font-bold pr5 pl5 font-size-10" style="margin-top: 10px;margin-bottom: -10px;"> 
                                <?php echo 'Site manager' ?></span>
                            </span>
                            &nbsp;</td>
                    </tr>
                    <!--Invoice Specification-->
                    <tr class="border-tbl text-left">
                        <td class="border-lr border-b" colspan="1" rowspan="2">    
                            <span class="font-bold pr5 pl5 font-size-10" style="transform: rotate(-90deg);width: 64px;margin-top: -4px;margin-right: -33px;"> 
                                <?php echo 'Max.2 days' ?>
                            </span>
                        </td>
                        <td class="border-lr" colspan="24">    
                            <span class="pr5 pl5 font-size-10" style="margin-top: -1px;margin-bottom: 18px;"> 
                                <?= 'Invocie has been checked and Approved' ?>
                               </span>
                        </td>
                        <!-- <td class="border-rbl border-tbl" colspan="16" rowspan="2">
                            <span class="pr5 pl5 font-size-10" style="margin-bottom: 15px;"> 
                            <?php echo "Original, Copy and soft copy of invoice along with it's supporting documents have been received" ?></span>
                            </span>

                            <span class="pr5 pl5 font-size-10" style=""> 
                            <?php echo ":initials" ?></span>
                            </span>
                            <span class="pr5 pl5 font-size-10" style=""> 
                            <?php echo ":Date" ?></span>
                            </span>

                        </td> -->
                        <td class="border-lr" colspan="25">    
                            <span class="pr5 pl5 font-size-10" style="margin-top: -1px;margin-bottom: 18px;"> 
                                <?= 'Invocie has been checked and Approved' ?>
                            </span>
                        </td>
                        
                    </tr>
                    <tr class="border-tbl text-left">

                        <td class="border-lr border-b" colspan="24" style="height: 40px;">
                            <span class="pr5 pl5 font-size-10" style="margin-top: -20px;margin-bottom: 5px;"> 
                                <?php echo !empty($sgn_08['uid']) ?$sgn_08['description']:':Remarks'; ?>
                            </span>
                            </span>
                            <?= !empty($sgn_08['uid']) ? yii\helpers\Html::img(['usersignature', 'uid' => $sgn_08['uid'], 'oid' => $sgn_08['oid']], [
                                        'style' => 'width: 90px;margin-top: -55px;margin-bottom: -57px;margin-left: 117px;'
                                    ]) : null;
                            ?>
                            <span class="font-bold pr5 pl5 font-size-10"> 
                                <?php echo!empty($sgn_08['AmountApproved1']) ? "Amount1 : ".  Utility::moneyFormatByCurrency($sgn_08['AmountApproved1'], $processEntityModel->invoice->Currency1Title) ." ".$sgn_08['Currency1Title'] : ''; ?>
                            </span>
                            <span class="font-bold pr5 pl5 font-size-10"> 
                                <?php echo!empty($sgn_08['AmountApproved2']) ? "Amount2 : ".  Utility::moneyFormatByCurrency($sgn_08['AmountApproved2'], $processEntityModel->invoice->Currency2Title) ." ".$sgn_08['Currency2Title'] : ''; ?>
                            </span>
                            <span class="font-bold pr5 pl5 font-size-10"> 
                                <?php echo!empty($sgn_08['UserName']) ? $sgn_08['UserName'] : ''; ?>
                            </span>

                            <span class="font-bold pr5 pl5 font-size-10" style="margin-bottom: -1px;"> 
                                <?php echo!empty($sgn_08['ActionDateHijri']) ? $sgn_08['ActionDateHijri'] : ''; ?>
                            </span>
                            
                        </td>
                        <td class="border-lr border-b" colspan="25">    
                            <span class="pr5 pl5 font-size-10" style="margin-top: -20px;margin-bottom: 5px;"> 
                                <?php echo !empty($sgn_07['uid']) ?$sgn_07['description']:':Remarks'; ?></span>
                            </span>
                            <?= !empty($sgn_07['uid']) ? yii\helpers\Html::img(['usersignature', 'uid' => $sgn_07['uid'], 'oid' => $sgn_07['oid']], [
                                        'style' => 'width: 90px;margin-top: -55px;margin-bottom: -57px;margin-left: 117px;'
                                    ]) : null;
                            ?>
                            <span class="font-bold pr5 pl5 font-size-10"> 
                                <?php echo!empty($sgn_07['AmountApproved1']) ? "Amount1 : ".  Utility::moneyFormatByCurrency($sgn_07['AmountApproved1'], $processEntityModel->invoice->Currency1Title) ." ".$sgn_07['Currency1Title'] : ''; ?>
                            </span>
                            <span class="font-bold pr5 pl5 font-size-10"> 
                                <?php echo!empty($sgn_07['AmountApproved2']) ? "Amount2 : ".  Utility::moneyFormatByCurrency($sgn_07['AmountApproved2'], $processEntityModel->invoice->Currency2Title) ." ".$sgn_07['Currency2Title'] : ''; ?>
                            </span>
                            <span class="font-bold pr5 pl5 font-size-10"> 
                                <?php echo!empty($sgn_07['UserName']) ? $sgn_07['UserName'] : ''; ?>
                            </span>

                            <span class="font-bold pr5 pl5 font-size-10" style="margin-bottom: -1px;"> 
                                <?php echo!empty($sgn_07['ActionDateHijri']) ? $sgn_07['ActionDateHijri'] : ''; ?>
                            </span>
                        </td>
                    </tr>


<?php
//    </editor-fold>
?>


<?php
//    <editor-fold desc="----< Finnancial Affairs Dept >----" defaultstate="collapsed" >
?>
                    <!--Invoice Specification-->
                    <tr class="border-tbl ">
                        <td class="border-rbl border-tbl blue-light-main-head text-center" colspan="17" >
                            <span class="font-bold pr5 pl5 font-size-10" style="margin-top: 10px;margin-bottom: -10px;"> 
                                <?php echo 'DCC' ?></span>
                            </span>
                            &nbsp;</td>
                        <td class="border-rbl border-tbl blue-light-main-head text-center" colspan="16" >
                            <span class="font-bold pr5 pl5 font-size-10" style="margin-top: 10px;margin-bottom: -10px;"> 
<?php echo 'Financial Affairs Dept' ?></span>
                            </span>
                            &nbsp;</td>
                        <td class="border-lr border-tbl blue-light-main-head text-center" colspan="17" >
                            <span class="font-bold pr5 pl5 font-size-8" style="margin-top: 10px;margin-bottom: -10px;"> 
<?php echo 'Invoice Dispatcher' ?></span>
                            </span>
                            &nbsp;</td>
                    </tr>
                    <!--Invoice Specification-->
                    <tr class="border-tbl text-left">
                        <td class="border-lr border-b" colspan="1" rowspan="2">    
                            <span class="font-bold pr5 pl5 font-size-10" style="transform: rotate(-90deg);width: 64px;margin-top: -4px;margin-right: -33px;"> 
<?php echo 'Max.3 days' ?></span>
                        </td>
                        <td class="border-lr" colspan="16">   
                            <span class="pr5 pl5 font-size-10" style="margin-top: 2px;margin-bottom: 18px;"> 
                                <?= "The copy of invoice along with it's Supporting documents has been received" ?>
                            </span>
                        </td>
                        <td class="border-lr" colspan="16">    
                            <span class="pr5 pl5 font-size-10" style="margin-top: -7px;margin-bottom: 18px;"> 
                                <?= 'Invocie has been checked and Approved and registered' ?>
                            </span>
                        </td>
                        <td class="border-rbl border-tbl" colspan="17" rowspan="2">
                            <span class="pr5 pl5 font-size-10" style="margin-bottom: 15px;"> 
<?php echo "Original, Copy and soft copy of invoice along with it's supporting documents have been received" ?></span>
                            </span>

                            <span class="pr5 pl5 font-size-10" style=""> 
                            <?php echo ":initials" ?></span>
                            </span>
                            <span class="pr5 pl5 font-size-10" style=""> 
                            <?php echo ":Date" ?></span>
                            </span>

                        </td>
                    </tr>
                    
                    <tr class="border-tbl text-left">

                        <td class="border-lr border-b" colspan="16" style="height: 40px;">    
                                <?php
                                echo!empty($sgn_non['uid']) ? yii\helpers\Html::img(['usersignature', 'uid' => $sgn_non['uid'], 'oid' => $sgn_non['oid']], [
                                            'style' => 'width: 90px;margin-top: -55px;margin-bottom: -36px;margin-left: 68px;'
                                        ]) : null;
                                ?>
                            <span class="font-bold pr5 pl5 font-size-10"> 
                            <?php echo!empty($sgn_non['UserName']) ? $sgn_non['UserName'] : ''; ?>
                            </span>

                            <span class="font-bold pr5 pl5 font-size-10"> 
<?php echo!empty($sgn_non['ActionDateHijri']) ? $sgn_non['ActionDateHijri'] : ''; ?></span>
                        </td>
                        <td class="border-lr border-b" colspan="16">
                                <span class="pr5 pl5 font-size-10" style="margin-top: -29px;margin-bottom: 2px;"> 
                                <?php echo !empty($sgn_09['uid']) ?$sgn_09['description']:':Remarks'; ?></span>
                            </span>
                            <?php
                            echo!empty($sgn_09['uid']) ? yii\helpers\Html::img(['usersignature', 'uid' => $sgn_09['uid'], 'oid' => $sgn_09['oid']], [
                                        'style' => 'width: 90px;margin-top: -55px;margin-bottom: -56px;margin-left: 137px;'
                                    ]) : null;
                            ?>
                            <span class="font-bold pr5 pl5 font-size-10"> 
                                <?php echo!empty($sgn_09['AmountApproved1']) ? "Amount1 : ".  Utility::moneyFormatByCurrency($sgn_09['AmountApproved1'], $processEntityModel->invoice->Currency1Title) ." ".$sgn_09['Currency1Title'] : ''; ?>
                            </span>
                            <span class="font-bold pr5 pl5 font-size-10"> 
                                <?php echo!empty($sgn_09['AmountApproved2']) ? "Amount2 : ".  Utility::moneyFormatByCurrency($sgn_09['AmountApproved2'], $processEntityModel->invoice->Currency2Title) ." ".$sgn_09['Currency2Title'] : ''; ?>
                            </span>
                            <span class="font-bold pr5 pl5 font-size-10"> 
                                <?php echo!empty($sgn_09['UserName']) ? $sgn_09['UserName'] : ''; ?>
                            </span>

                            <span class="font-bold pr5 pl5 font-size-10" style="margin-bottom: -1px;"> 
                            <?php echo!empty($sgn_09['ActionDateHijri']) ? $sgn_09['ActionDateHijri'] : ''; ?></span>
                        </td>
                    </tr>
                    


<?php
//    </editor-fold>
?>



                </tbody>                

            </table>
            <!----------------------------------------------------------------------------->

        </div>
        <div class='fake-lock'>

        </div>
    </body>



