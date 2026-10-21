<?php

use app\modules\cashRequest\assets\FormCashRequestAsset;
use app\components\Utility;
use yii\helpers\Html;

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
                        <th rowspan="3" colspan="8" class="border-t border-lr" style="line-height: 4px">

                        <span class='logo-psa-admin' ><img src="<?= $logo_admin; ?>" style="" /></span>
                        <br>
                        <br>
                        <p style="font-size: 8px;margin-bottom: -19px;">
                            <?= "Invoice ID : " ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= $processEntityModel->invoice->InvoiceID; ?>
                        </p>
                        &nbsp;
                        </th>
                        <th class="border-t border-lr  " colspan="34" style="line-height: 22px">
                            <p style="font-size: 10px;margin-bottom: -2px;margin-top: -7px;"><?= 'سیستم مدیریت یکپارچه'; ?></p>
                            <p style="font-size: 16px;margin-bottom: -42px;margin-top: -2px;"><?= 'کاربرگ بررسی و تائید صورت وضعیت'; ?></p>
                        &nbsp;</p>
                        </th>
                        <th rowspan="3"  colspan="8" class="border-t border-lr font-calibri font-italic font-bold font-size-10" width='15%'  style="line-height: 12px">
                            <span class='logo-psa' ><img src="<?= $logo; ?>" style="" /></span>
                            <p style="font-size: 6px;margin-bottom: -15px;"><?= 'integarated Cost' ?></p>
                            <br>
                            <p style="font-size: 6px;margin-bottom: -15px;"><?= 'Management System' ?></p>
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
                    <th class="border-t border-lr font-inherit font-size-10" colspan="7"><?= '0001' ?>&nbsp;</th>
                    <th class="border-t border-l font-inherit font-size-10" colspan="7"><?= 'FO' ?>&nbsp;</th>
                    <th class="border-t border-l font-inherit font-size-10" colspan="6" ><?= 'PM' ?>&nbsp;</th>
                    <th class="border-t border-l font-inherit font-size-10" colspan="7"><?= '000' ?>&nbsp;</th>
                    <th class="border-t border-l font-inherit font-size-10" colspan="7"><?= 'PSA' ?>&nbsp;</th>
                </tr>

                
                </thead>
                <tbody>
                    <!---  Part Two ---->
                    <!--Invoice Specification-->
                    <tr class="border-trbl ">
                        <td class="border-rbl text-right border-trbl table-header-bg blue-main-head" colspan="25" >
                            <span class="font-bold pr5 pl5" style="text-align: center;font-size: 12px;margin-top: 5px;margin-bottom: -8px;"> 
                                <?php echo 'مشخصات قرارداد' ?></span>
                            </span>
                            &nbsp;</td>
                        <td class="border-rbl text-right border-trbl table-header-bg blue-main-head" colspan="25" >
                            <span class="font-bold pr5 pl5" style="text-align: center;font-size: 12px;margin-top: 5px;margin-bottom: -8px;"> 
                                <?php echo 'کد هزینه' ?></span>
                            </span>
                            &nbsp;</td>
                    </tr>
                    
                    <tr class="font-size-10 border-tbl"  style="line-height: 10px;text-align: left;">
                        <th class="border-t border-lr font-inherit font-size-10 text-center" colspan="8"><?= 'پیمانکار' ?>&nbsp;</th>
                        <th class="border-t border-lr font-inherit font-size-10" colspan="17" style="text-align: center;"><?=  Html::encode($processEntityModel->invoice->contract->SubcontractorsList) ?>&nbsp;</th>
                        <th class="border-t border-lr font-inherit font-size-10 text-center" colspan="7" rowspan="2"><?= 'پروژه ها' ?>&nbsp;</th>
                        <th class="border-t border-lr font-inherit font-size-10" colspan="18" rowspan="2" style="text-align: center;"><?=  Html::encode($projectString); ?>&nbsp;</th>
                    </tr>
                    <tr class="font-size-10 border-tbl"  style="line-height: 10px;text-align: left;">
                        <th class="border-t border-lr font-inherit font-size-10 text-center" colspan="8"><?= 'شماره قرارداد' ?>&nbsp;</th>
                        <th class="border-t border-lr font-inherit font-size-10" colspan="17"  style="text-align: center;"><?=  Html::encode($processEntityModel->invoice->contract->ContractNo) ?>&nbsp;</th>
                    </tr>
                    
                    <tr class="font-size-10 border-tbl"  style="line-height: 10px;text-align: left;">
                        <th class="border-t border-lr font-inherit font-size-10 text-center" colspan="8"><?= 'موضوع قرارداد' ?>&nbsp;</th>
                        <th class="border-t border-lr font-inherit font-size-10" colspan="17"  style="text-align: center;"><?=  Html::encode($processEntityModel->invoice->contract->ContractSubject) ?>&nbsp;</th>
                        <th class="border-t border-lr font-inherit font-size-10 text-center" colspan="7"><?= 'زیرپروژه EPS ' ?>&nbsp;</th>
                        <th class="border-t border-lr font-inherit font-size-10" colspan="18" style="text-align: center;"><?=  Html::encode($processEntityModel->invoice->contract->subProjectList) ?>&nbsp;</th>
                    </tr>
                    
                    <tr class="font-size-10 border-tbl"  style="line-height: 10px;text-align: left;">
                        <th class="border-tb border-lr font-inherit font-size-10 text-center" colspan="8" rowspan="2"><?= 'مبلغ قرارداد' ?>&nbsp;</th>
                        <th class="border-t border-lr font-inherit font-size-10" colspan="15"><?= Utility::moneyFormatByCurrency($processEntityModel->invoice->contract->lastRider->Price1,$processEntityModel->invoice->contract->lastRider->currency1->CurrencyTitle) ?>&nbsp;</th>
                        <th class="border-t border-l font-inherit font-size-10 text-center" colspan="2"><?=  Html::encode($processEntityModel->invoice->contract->lastRider->currency1->CurrencyTitle) ?>&nbsp;</th>
                        <th class="border-t border-lr font-inherit font-size-10 text-center" colspan="7" ><?= 'تاریخ شروع' ?>&nbsp;</th>
                        <th class="border-t border-lr font-inherit font-size-10" colspan="18" style="text-align: center;"><?= $processEntityModel->invoice->contract->ContractDateHijri; ?>&nbsp;</th>
                    </tr>
                    
                    <tr class="font-size-10 border-trbl"  style="line-height: 10px;text-align: left;">
                        <th class="border-tb border-lr font-inherit font-size-10" colspan="15"><?= Utility::moneyFormatByCurrency($processEntityModel->invoice->contract->lastRider->Price2,isset($processEntityModel->invoice->contract->lastRider->currency2->CurrencyTitle)? Html::encode($processEntityModel->invoice->contract->lastRider->currency2->CurrencyTitle):null) ?>&nbsp;</th>
                        <th class="border-tb border-l font-inherit font-size-10 text-center" colspan="2"><?= isset($processEntityModel->invoice->contract->lastRider->currency2->CurrencyTitle)?  Html::encode($processEntityModel->invoice->contract->lastRider->currency2->CurrencyTitle) : null; ?>&nbsp;</th>
                        <th class="border-tb border-lr font-inherit font-size-10 text-center" colspan="7" ><?= 'تاریخ پایان' ?>&nbsp;</th>
                        <th class="border-tb border-lr font-inherit font-size-10" colspan="18" style="text-align: center;"><?= $processEntityModel->invoice->contract->lastRider->FinishDateHijri; ?>&nbsp;</th>
                    </tr>
                    
                  
                    <tr class="border-trbl ">
                        <td class="border-rbl text-right border-trbl table-header-bg blue-main-head" colspan="50" >
                            <span class="font-bold pr5 pl5" style="text-align: center;font-size: 12px;margin-top: 5px;margin-bottom: -8px;"> 
                                <?php echo 'مشخصات صورت وضعیت' ?></span>
                            </span>
                            &nbsp;</td>
                    </tr>

                    <!--Invoice Specification Value-->
                    <tr class="font-size-10 border-tbl"  style="line-height: 10px;text-align: left;">
                        <th class="border-t border-lr font-inherit font-size-10 text-center" colspan="8" rowspan="2"><?= 'نوع و شماره صورت وضعیت' ?>&nbsp;</th>
                        <th class="border-t border-l font-inherit font-size-10" colspan="6" rowspan="2" style="word-wrap: break-word;text-align: center;"><?=  Html::encode($processEntityModel->invoice->Title) .' / '.  Html::encode($processEntityModel->invoice->type->InvoiceType) ?>&nbsp;</th>
                        
                        <th class="border-t border-l font-inherit font-size-10 text-center" colspan="6" rowspan="2"><?= "دوره صورت وضعیت"; ?>&nbsp;</th>
                        <th class="border-t border-l font-inherit font-size-10" colspan="5"><?= isset($processEntityModel->invoice->previousInvocie->CutOffDateHijri)?$processEntityModel->invoice->previousInvocie->CutOffDateHijri:'' ?>&nbsp;</th>
                        <th class="border-t border-l font-inherit font-size-10 text-center" colspan="4"><?= "تا" ?>&nbsp;</th>
                        <th class="border-t border-l font-inherit font-size-10" colspan="6" ><?= $processEntityModel->invoice->CutOffDateHijri; ?>&nbsp;</th>
                        
                        <th class="border-t border-l font-inherit font-size-10 text-center" colspan="7" rowspan="2"><?= 'تاریخ تحویل صورت وضعیت' ?>&nbsp;</th>
                        <th class="border-t border-lr font-inherit font-size-10" colspan="8" style="text-align: center;"><?= $firstAction->ActionDateHijri ?>&nbsp;</th>
                    </tr>
                    <tr class="font-size-10 border-tbl"  style="line-height: 10px;text-align: left;">
                        <th class="border-t border-l font-inherit font-size-10" colspan="5"><?= isset($processEntityModel->invoice->previousInvocie->CutOffDateHijri)?Utility::convertDate($processEntityModel->invoice->previousInvocie->CutOffDateHijri,false):""; ?>&nbsp;</th>
                        <th class="border-t border-l font-inherit font-size-10 text-center" colspan="4"><?= "تا" ?>&nbsp;</th>
                        <th class="border-t border-l font-inherit font-size-10" colspan="6" ><?= Utility::convertDate($processEntityModel->invoice->CutOffDateHijri,false); ?>&nbsp;</th>
                        <th class="border-t border-lr font-inherit font-size-10" colspan="8" style="text-align: center;"><?= $firstAction->ActionDate  ?>&nbsp;</th>
                    </tr>
                
                    <tr class="font-size-10 border-tbl"  style="line-height: 10px;text-align: right;">
                        <th class="border-t border-lr font-inherit font-size-10 text-center" colspan="8"><?= 'توضیحات' ?>&nbsp;</th>
                        <th class="border-t border-l font-inherit font-size-8" colspan="42"><?php  
                        $income="";
                        
                        if($processEntityModel->invoice->cumulativeIncome1(6)>0 || $processEntityModel->invoice->cumulativeIncome2(6)>0){
                            $income.="<span  style='color: red;font-weight: 600;font-size: 9px;'>"."لازم به توضیح است باید مبلغ تجمعی ";
                            $income.= Utility::moneyFormatByCurrency($processEntityModel->invoice->cumulativeIncome1(6),$processEntityModel->invoice->Currency1Title) .' '.  Html::encode($processEntityModel->invoice->Currency1Title);
                            if($processEntityModel->invoice->cumulativeIncome2(6)>0){
                                $income.=' و ' . Utility::moneyFormatByCurrency($processEntityModel->invoice->cumulativeIncome2(6),$processEntityModel->invoice->Currency2Title) .' '.  Html::encode($processEntityModel->invoice->Currency2Title);
                            }
                            $income.="مطابق لیست پیوست از حساب پیمانکار مربوطه کسر گردد.";
                            $income.="</span>";
                        }
                        
                        $income.=$processEntityModel->invoice->Description ;
                        echo $income; ?>&nbsp;</th>
                    </tr>
                    
                    <tr class="font-size-10 border-tbl"  style="line-height: 10px;text-align: left;background-color: lightgray;text-align: center;">
                        <th class="border-t border-lr font-inherit font-size-10" colspan="8" ><?= 'پروژه'; ?>&nbsp;</th>
                        <th class="border-t border-l font-inherit font-size-10" colspan="3"><?= "ارز 2"; ?>&nbsp;</th>
                        <th class="border-t border-l font-inherit font-size-10" colspan="7"><?= "تجمعی 2" ?>&nbsp;</th>
                        <th class="border-t border-l font-inherit font-size-10" colspan="7"><?= "دوره ای 2"; ?>&nbsp;</th>
                        <th class="border-t border-l font-inherit font-size-10" colspan="3"><?= "ارز 1"; ?>&nbsp;</th>
                        <th class="border-t border-l font-inherit font-size-10" colspan="7"><?= "تجمعی 1" ?>&nbsp;</th>
                        <th class="border-t border-l font-inherit font-size-10" colspan="7"><?= "دوره ی 1"; ?>&nbsp;</th>
                        <th class="border-t border-l font-inherit font-size-10" colspan="8"><?= 'نوع مبلغ' ?>&nbsp;</th>
                    </tr>
                    <?php 
                        $cumulativeRequested1ByCostCode=0;
                        $cumulativeRequested2ByCostCode=0;
                        $approved1ByCostCode=0;
                        $approved2ByCostCode=0;
                        
                        $cumulativeApproved1ByCostCode=0;
                        $cumulativeApproved2ByCostCode=0;
                        $requested1ByCostCode=0;
                        $requested2ByCostCode=0;
                        $countRow=0;
                        foreach ($projectList as $key => $value) { 
                            $countRow++;
                            $cumulativeRequested1ByCostCode+=$processEntityModel->invoice->cumulativeRequested1ByCostCode(1, $value["NodeID"]);
                            $cumulativeRequested2ByCostCode+=$processEntityModel->invoice->cumulativeRequested2ByCostCode(1, $value["NodeID"]);
                            
                            $requested1ByCostCode+=$processEntityModel->invoice->requested1ByCostCode($value["NodeID"]);
                            $requested2ByCostCode+=$processEntityModel->invoice->requested2ByCostCode($value["NodeID"]);
                            
                            
                            $cumulativeApproved1ByCostCode+=$processEntityModel->invoice->cumulativeApproved1ByCostCode(1, $value["NodeID"]);
                            $cumulativeApproved2ByCostCode+=$processEntityModel->invoice->cumulativeApproved2ByCostCode(1, $value["NodeID"]);
                            
                            $approved1ByCostCode+=$processEntityModel->invoice->approved1ByCostCode($value["NodeID"]);
                            $approved2ByCostCode+=$processEntityModel->invoice->approved2ByCostCode($value["NodeID"]);
                        ?>
                            <tr class="font-size-10 border-trbl"  style="line-height: 10px;text-align: left;color: #cf0000;">
                                <th class="border-t border-lr font-inherit font-size-10 text-center" colspan="8"><?= $value["EPSTitle"]; ?>&nbsp;</th>
                                <th class="border-t border-l font-inherit font-size-10" colspan="3"><?=  Html::encode($processEntityModel->invoice->Currency2Title); ?>&nbsp;</th>
                                <th class="border-t border-l font-inherit font-size-10" colspan="7"><?= Utility::moneyFormatByCurrency($processEntityModel->invoice->cumulativeRequested2ByCostCode(5, $value["NodeID"]), $processEntityModel->invoice->Currency2Title) ; ?>&nbsp;</th>
                                <th class="border-t border-l font-inherit font-size-10" colspan="7"><?= Utility::moneyFormatByCurrency($processEntityModel->invoice->requested2ByCostCode($value["NodeID"]), $processEntityModel->invoice->Currency2Title); ?>&nbsp;</th>
                                <th class="border-t border-l font-inherit font-size-10" colspan="3"><?=  Html::encode($processEntityModel->invoice->Currency1Title); ?>&nbsp;</th>
                                <th class="border-t border-l font-inherit font-size-10" colspan="7"><?= Utility::moneyFormatByCurrency($processEntityModel->invoice->cumulativeRequested1ByCostCode(5, $value["NodeID"]), $processEntityModel->invoice->Currency1Title); ?>&nbsp;</th>
                                <th class="border-t border-l font-inherit font-size-10" colspan="7"><?= Utility::moneyFormatByCurrency($processEntityModel->invoice->requested1ByCostCode($value["NodeID"]), $processEntityModel->invoice->Currency1Title); ?>&nbsp;</th> &nbsp;</th>
                                <th class="border-t border-l font-inherit font-size-10 text-center" colspan="8"><?= 'ادعایی' ?>&nbsp;</th>
                            </tr>

                            <tr class="font-size-10 border-tbl"  style="line-height: 10px;text-align: left;color: #078c1b;">
                                <th class="border-t border-lr font-inherit font-size-10 text-center" colspan="8"><?= $value["EPSTitle"]; ?>&nbsp;</th>
                                <th class="border-t border-l font-inherit font-size-10" colspan="3"><?=  Html::encode($processEntityModel->invoice->Currency2Title); ?>&nbsp;</th>
                                <th class="border-t border-l font-inherit font-size-10" colspan="7"><?= Utility::moneyFormatByCurrency($processEntityModel->invoice->cumulativeApproved2ByCostCode(6, $value["NodeID"]), $processEntityModel->invoice->Currency2Title) ; ?>&nbsp;</th>
                                <th class="border-t border-l font-inherit font-size-10" colspan="7"><?= Utility::moneyFormatByCurrency($processEntityModel->invoice->approved2ByCostCode($value["NodeID"]), $processEntityModel->invoice->Currency2Title); ?>&nbsp;</th>
                                <th class="border-t border-l font-inherit font-size-10" colspan="3"><?=  Html::encode($processEntityModel->invoice->Currency1Title); ?>&nbsp;</th>
                                <th class="border-t border-l font-inherit font-size-10" colspan="7"><?= Utility::moneyFormatByCurrency($processEntityModel->invoice->cumulativeApproved1ByCostCode(6, $value["NodeID"]), $processEntityModel->invoice->Currency1Title); ?>&nbsp;</th>
                                <th class="border-t border-l font-inherit font-size-10" colspan="7"><?= Utility::moneyFormatByCurrency($processEntityModel->invoice->approved1ByCostCode($value["NodeID"]) , $processEntityModel->invoice->Currency1Title); ?>&nbsp;</th> &nbsp;</th>
                                <th class="border-t border-l font-inherit font-size-10 text-center" colspan="8"><?= 'تائید شده و در گردش' ?>&nbsp;</th>
                            </tr>

                    <?php } ?>
                            
                        <?php if($countRow>1){ ?>
                            <tr class="font-size-10 border-trbl"  style="line-height: 10px;text-align: left;color: #cf0000;">
                                <th class="border-t border-lr font-inherit font-size-10 text-center" colspan="8" rowspan="2"><?= 'جمع'; ?>&nbsp;</th>
                                <th class="border-t border-l font-inherit font-size-10" colspan="3"><?= $processEntityModel->invoice->Currency2Title; ?>&nbsp;</th>
                                <th class="border-t border-l font-inherit font-size-10" colspan="7"><?= Utility::moneyFormatByCurrency($cumulativeRequested2ByCostCode, $processEntityModel->invoice->Currency2Title) ; ?>&nbsp;</th>
                                <th class="border-t border-l font-inherit font-size-10" colspan="7"><?= Utility::moneyFormatByCurrency($requested2ByCostCode, $processEntityModel->invoice->Currency2Title); ?>&nbsp;</th>
                                <th class="border-t border-l font-inherit font-size-10" colspan="3"><?=  Html::encode($processEntityModel->invoice->Currency1Title); ?>&nbsp;</th>
                                <th class="border-t border-l font-inherit font-size-10" colspan="7"><?= Utility::moneyFormatByCurrency($cumulativeRequested1ByCostCode, $processEntityModel->invoice->Currency1Title); ?>&nbsp;</th>
                                <th class="border-t border-l font-inherit font-size-10" colspan="7"><?= Utility::moneyFormatByCurrency($requested1ByCostCode, $processEntityModel->invoice->Currency1Title); ?>&nbsp;</th> &nbsp;</th>
                                <th class="border-t border-l font-inherit font-size-10 text-center" colspan="8"><?= 'ادعایی' ?>&nbsp;</th>
                            </tr>

                            <tr class="font-size-10 border-tbl"  style="line-height: 10px;text-align: left;color: #078c1b;">
                                <th class="border-t border-l font-inherit font-size-10" colspan="3"><?=  Html::encode($processEntityModel->invoice->Currency2Title); ?>&nbsp;</th>
                                <th class="border-t border-l font-inherit font-size-10" colspan="7"><?= Utility::moneyFormatByCurrency($cumulativeApproved2ByCostCode, $processEntityModel->invoice->Currency2Title) ; ?>&nbsp;</th>
                                <th class="border-t border-l font-inherit font-size-10" colspan="7"><?= Utility::moneyFormatByCurrency($approved2ByCostCode, $processEntityModel->invoice->Currency2Title); ?>&nbsp;</th>
                                <th class="border-t border-l font-inherit font-size-10" colspan="3"><?=  Html::encode($processEntityModel->invoice->Currency1Title); ?>&nbsp;</th>
                                <th class="border-t border-l font-inherit font-size-10" colspan="7"><?= Utility::moneyFormatByCurrency($cumulativeApproved1ByCostCode, $processEntityModel->invoice->Currency1Title); ?>&nbsp;</th>
                                <th class="border-t border-l font-inherit font-size-10" colspan="7"><?= Utility::moneyFormatByCurrency($approved1ByCostCode , $processEntityModel->invoice->Currency1Title); ?>&nbsp;</th> &nbsp;</th>
                                <th class="border-t border-l font-inherit font-size-10 text-center" colspan="8"><?= 'تائید شده' ?>&nbsp;</th>
                            </tr>
                        <?php } ?>



                    <!--Invoice Specification-->
                    <tr class="border-trbl ">
                        <td class="border-rbl text-right border-trbl table-header-bg blue-main-head" colspan="50" >
                            <span class="font-bold pr5 pl5" style="text-align: center;font-size: 12px;margin-top: 5px;margin-bottom: -8px;"> 
                                <?php echo 'تاییدات و یادداشت ها' ?></span>
                            </span>
                            &nbsp;</td>
                    </tr>

                    
                    <?php
//    <editor-fold desc="----< مدیریت  / معاونت تخصصی >----" defaultstate="collapsed" >
                    ?>
                    <!--Invoice Specification-->
                    <tr class="border-tbl ">
                        <td class="border-rbl border-tbl blue-light-main-head text-center" colspan="50" >
                            <span class="font-bold pr5 pl5 font-size-10" style="margin-top: 10px;margin-bottom: -10px;direction: ltr;"> 
                                <?php echo 'مدیریت  / معاونت تخصصی' ?></span>
                            </span>
                            &nbsp;</td>
                    </tr>
                    
                    <!--Invoice Specification-->
                    <tr class="border-tbl text-left">
                        <td class="border-trbl" colspan="7" rowspan="4">    
                            <span class="font-bold pr5 pl5 font-size-10" style="width: 64px;margin-top: -4px;margin-right: 5px;text-align: center;"> 
                                <?php echo 'کارشناس / مسئول بخش' ?></span></span></span>
                            </span>
                        </td>
                        
                        <td class="border-t border-r" colspan="5" rowspan="2" style="text-align: center;">    
                            <span class="pr5 pl5 font-size-10" style=""> 
                                <?php echo 'مبلغ تجمعی' ?>
                            </span>
                        </td>
                        
                        <td class="border-t border-r" colspan="10" rowspan="1" style="text-align: center;">    
                            <span class="pr5 pl5 font-size-10" style=""> 
                                <?php echo Utility::moneyFormatByCurrency($sgn_01['AmountApproved1Cumulative'], $processEntityModel->invoice->Currency1Title) ?>
                            </span>
                        </td>
                        <td class="border-t border-lr" colspan="3" rowspan="1" style="text-align: center;">    
                            <span class="pr5 pl5 font-size-10" style=""> 
                                <?php echo $processEntityModel->invoice->Currency1Title ?>
                            </span>
                        </td>

                        <td class="border-trbl" colspan="7" rowspan="4">    
                            <span class="font-bold pr5 pl5 font-size-10"  style="width: 64px;margin-top: -4px;margin-right: 5px;text-align: center;"> 
                                <?php echo 'مدیر / معاون مربوطه' ?></span></span></span>
                            </span>
                        </td>
                        <td class="border-t border-r" colspan="5" rowspan="2" style="text-align: center;">    
                            <span class="pr5 pl5 font-size-10" style=""> 
                                <?php echo 'مبلغ تجمعی' ?>
                            </span>
                        </td>
                        <td class="border-t border-r" colspan="10" rowspan="1" style="text-align: center;">    
                            <span class="pr5 pl5 font-size-10" style=""> 
                                <?php echo Utility::moneyFormatByCurrency($sgn_02['AmountApproved1Cumulative'], $processEntityModel->invoice->Currency1Title) ?>
                            </span>
                        </td>
                        <td class="border-t border-lr" colspan="3" rowspan="1" style="text-align: center;">    
                            <span class="pr5 pl5 font-size-10" style=""> 
                                <?php echo $processEntityModel->invoice->Currency1Title ?>
                            </span>
                        </td>
                    </tr>
                    
                    <tr class="border-tbl text-left">

                        <td class="border-t border-r" colspan="10" rowspan="1" style="text-align: center;">    
                            <span class="pr5 pl5 font-size-10" style=""> 
                                <?php echo Utility::moneyFormatByCurrency($sgn_01['AmountApproved2Cumulative'], $processEntityModel->invoice->Currency2Title) ?>
                            </span>
                        </td>
                        <td class="border-t border-lr" colspan="3" rowspan="1" style="text-align: center;">    
                            <span class="pr5 pl5 font-size-10" style=""> 
                                <?php echo $processEntityModel->invoice->Currency2Title ?>
                            </span>
                        </td>
                        <td class="border-t border-r" colspan="10" rowspan="1" style="text-align: center;">    
                            <span class="pr5 pl5 font-size-10" style=""> 
                                <?php echo Utility::moneyFormatByCurrency($sgn_02['AmountApproved2Cumulative'], $processEntityModel->invoice->Currency2Title) ?>
                            </span>
                        </td>
                        <td class="border-t border-lr" colspan="3" rowspan="1" style="text-align: center;">    
                            <span class="pr5 pl5 font-size-10" style=""> 
                                <?php echo $processEntityModel->invoice->Currency2Title ?>
                            </span>
                        </td>
                    </tr>
                    
                    <?php if (false){ ?>
                        
                    <tr class="text-right">
                        <td class="border-trbl" colspan="18" rowspan="1" style="text-align: right;">    
                            <span class="pr5 pl5 font-size-10" style=""> 
                                <span class="pr5 pl5 font-size-10"> 
                                    <?php echo !empty($sgn_01['description']) ?'توضیحات:'.$sgn_01['description']:NULL; ?>
                                </span>
                            </span>
                        </td>
                        <td class="border-trbl" colspan="18" rowspan="1" style="text-align: right;">    
                            <span class="pr5 pl5 font-size-10" style=""> 
                                <span class="pr5 pl5 font-size-10"> 
                                    <?php echo !empty($sgn_02['description1']) ?'توضیحات:'.$sgn_02['description']:NULL; ?>
                                </span>
                            </span>
                        </td>
                    </tr>
                    <?php }  else { ?>
                        <tr class="text-right">
                            <td class="border-lr border-t" colspan="18" rowspan="1" style="text-align: right;">    
                                
                            </td>
                            <td class="border-lr border-t" colspan="18" rowspan="1" style="text-align: right;">    
                                
                            </td>
                        </tr>
                    <?php }?>
                    
                    <tr class="border-b text-left"> 

                        <td class="border-lr border-b" colspan="18" style="height: 70px;">    
                            <span style="margin-top: -16px;">
                            <?= !empty($sgn_01['uid']) ? yii\helpers\Html::img(['usersignature', 'uid' => $sgn_01['uid'], 'oid' => $sgn_01['oid']], [
                                        'style' => 'width: 90px;margin-top: -55px;margin-bottom: -61px;margin-left: 137px;'
                                    ]) : null;
                            ?>
                            </span>
                            <span class="font-bold pr5 pl5 font-size-10"> 
                                <?php echo!empty($sgn_01['UserName']) ? $sgn_01['UserName'] : ''; ?>
                            </span>

                            <span class="font-bold pr5 pl5 font-size-10" style="margin-bottom: -1px;"> 
                                <?php echo!empty($sgn_01['ActionDateHijri']) ? $sgn_01['ActionDateHijri'] : ''; ?>
                            </span>
                        </td>
                        <td class="border-lr border-b" colspan="18" style="height: 70px;">    
                            <span style="margin-top: -16px;">
                            <?= !empty($sgn_02['uid']) ? yii\helpers\Html::img(['usersignature', 'uid' => $sgn_02['uid'], 'oid' => $sgn_02['oid']], [
                                        'style' => 'width: 90px;margin-top: -55px;margin-bottom: -61px;margin-left: 137px;'
                                    ]) : null;
                            ?>
                            </span>
                            <span class="font-bold pr5 pl5 font-size-10"> 
                                <?php echo!empty($sgn_02['UserName']) ? $sgn_02['UserName'] : ''; ?>
                            </span>

                            <span class="font-bold pr5 pl5 font-size-10" style="margin-bottom: -1px;"> 
                                <?php echo!empty($sgn_02['ActionDateHijri']) ? $sgn_02['ActionDateHijri'] : ''; ?>
                            </span>
                        </td>

                    </tr>

                    <?php
//    </editor-fold>
                    ?>

                    <?php
//    <editor-fold desc="----< مدیر برنامه ریزی و کنترل پروژه کارگاه  >----" defaultstate="collapsed" >
                    ?>
                    
                    <tr class="border-tbl ">
                        <td class="border-rbl border-tbl blue-light-main-head text-center" colspan="50" >
                            <span class="font-bold pr5 pl5 font-size-10" style="margin-top: 10px;margin-bottom: -10px;direction: ltr;"> 
                                <?php echo 'مدیریت برنامه ریزی و کنترل پروژه کارگاه' ?></span>
                            </span>
                            &nbsp;</td>
                    </tr>
                    
                    <!--Invoice Specification-->
                    <tr class="border-tbl text-left">
                        <td class="border-trbl" colspan="7" rowspan="4">    
                            <span class="font-bold pr5 pl5 font-size-10" style="width: 64px;margin-top: -4px;margin-right: 5px;text-align: center;"> 
                                <?php echo 'کارشناس / مسئول کنترل هزینه' ?></span></span></span>
                            </span>
                        </td>
                        
                        <td class="border-t border-r" colspan="5" rowspan="2" style="text-align: center;">    
                            <span class="pr5 pl5 font-size-10" style=""> 
                                <?php echo 'مبلغ تجمعی' ?>
                            </span>
                        </td>
                        
                        <td class="border-t border-r" colspan="10" rowspan="1" style="text-align: center;">    
                            <span class="pr5 pl5 font-size-10" style=""> 
                                <?php echo Utility::moneyFormatByCurrency($sgn_03['AmountApproved1Cumulative'], $processEntityModel->invoice->Currency1Title) ?>
                            </span>
                        </td>
                        <td class="border-t border-lr" colspan="3" rowspan="1" style="text-align: center;">    
                            <span class="pr5 pl5 font-size-10" style=""> 
                                <?php echo $processEntityModel->invoice->Currency1Title ?>
                            </span>
                        </td>

                        <td class="border-trbl" colspan="7" rowspan="4">    
                            <span class="font-bold pr5 pl5 font-size-10" style="text-align: center;"> 
                                <?php echo 'مدیر برنامه ریزی و کنترل پروژه' ?>
                            </span>
                        </td>
                        <td class="border-t border-r" colspan="5" rowspan="2" style="text-align: center;">    
                            <span class="pr5 pl5 font-size-10" style=""> 
                                <?php echo 'مبلغ تجمعی' ?>
                            </span>
                        </td>
                        <td class="border-t border-r" colspan="10" rowspan="1" style="text-align: center;">    
                            <span class="pr5 pl5 font-size-10" style=""> 
                                <?php echo Utility::moneyFormatByCurrency($sgn_04['AmountApproved1Cumulative'], $processEntityModel->invoice->Currency1Title) ?>
                            </span>
                        </td>
                        <td class="border-t border-lr" colspan="3" rowspan="1" style="text-align: center;">    
                            <span class="pr5 pl5 font-size-10" style=""> 
                                <?php echo $processEntityModel->invoice->Currency1Title ?>
                            </span>
                        </td>
                    </tr>
                    
                    <tr class="border-tbl text-left">

                        <td class="border-t border-r" colspan="10" rowspan="1" style="text-align: center;">    
                            <span class="pr5 pl5 font-size-10" style=""> 
                                <?php echo Utility::moneyFormatByCurrency($sgn_03['AmountApproved2Cumulative'], $processEntityModel->invoice->Currency2Title) ?>
                            </span>
                        </td>
                        <td class="border-t border-lr" colspan="3" rowspan="1" style="text-align: center;">    
                            <span class="pr5 pl5 font-size-10" style=""> 
                                <?php echo $processEntityModel->invoice->Currency2Title ?>
                            </span>
                        </td>
                        <td class="border-t border-r" colspan="10" rowspan="1" style="text-align: center;">    
                            <span class="pr5 pl5 font-size-10" style=""> 
                                <?php echo Utility::moneyFormatByCurrency($sgn_04['AmountApproved2Cumulative'], $processEntityModel->invoice->Currency2Title) ?>
                            </span>
                        </td>
                        <td class="border-t border-lr" colspan="3" rowspan="1" style="text-align: center;">    
                            <span class="pr5 pl5 font-size-10" style=""> 
                                <?php echo $processEntityModel->invoice->Currency2Title ?>
                            </span>
                        </td>
                    </tr>
                    
                    <?php if (false){ ?>
                        
                    <tr class="text-right">
                        <td class="border-trbl" colspan="18" rowspan="1" style="text-align: right;">    
                            <span class="pr5 pl5 font-size-10" style=""> 
                                <span class="pr5 pl5 font-size-10"> 
                                    <?php echo !empty($sgn_03['description']) ?'توضیحات:'.$sgn_03['description']:NULL; ?>
                                </span>
                            </span>
                        </td>
                        <td class="border-trbl" colspan="18" rowspan="1" style="text-align: right;">    
                            <span class="pr5 pl5 font-size-10" style=""> 
                                <span class="pr5 pl5 font-size-10"> 
                                    <?php echo !empty($sgn_04['description1']) ?'توضیحات:'.$sgn_04['description']:NULL; ?>
                                </span>
                            </span>
                        </td>
                    </tr>
                    <?php }  else { ?>
                        <tr class="text-right">
                            <td class="border-lr border-t" colspan="18" rowspan="1" style="text-align: right;">    
                                
                            </td>
                            <td class="border-lr border-t" colspan="18" rowspan="1" style="text-align: right;">    
                                
                            </td>
                        </tr>
                    <?php }?>
                    
                    <tr class="border-b text-left"> 

                        <td class="border-lr border-b" colspan="18" style="height: 70px;">    
                            <span style="margin-top: -16px;">
                            <?= !empty($sgn_03['uid']) ? yii\helpers\Html::img(['usersignature', 'uid' => $sgn_03['uid'], 'oid' => $sgn_03['oid']], [
                                        'style' => 'width: 90px;margin-top: -55px;margin-bottom: -61px;margin-left: 137px;'
                                    ]) : null;
                            ?>
                            </span>
                            <span class="font-bold pr5 pl5 font-size-10"> 
                                <?php echo!empty($sgn_03['UserName']) ? $sgn_03['UserName'] : ''; ?>
                            </span>

                            <span class="font-bold pr5 pl5 font-size-10" style="margin-bottom: -1px;"> 
                                <?php echo!empty($sgn_03['ActionDateHijri']) ? $sgn_03['ActionDateHijri'] : ''; ?>
                            </span>
                        </td>
                        <td class="border-lr border-b" colspan="18" style="height: 70px;">    
                            <span style="margin-top: -16px;">
                            <?= !empty($sgn_04['uid']) ? yii\helpers\Html::img(['usersignature', 'uid' => $sgn_04['uid'], 'oid' => $sgn_04['oid']], [
                                        'style' => 'width: 90px;margin-top: -55px;margin-bottom: -61px;margin-left: 137px;'
                                    ]) : null;
                            ?>
                            </span>
                            <span class="font-bold pr5 pl5 font-size-10"> 
                                <?php echo!empty($sgn_04['UserName']) ? $sgn_04['UserName'] : ''; ?>
                            </span>

                            <span class="font-bold pr5 pl5 font-size-10" style="margin-bottom: -1px;"> 
                                <?php echo!empty($sgn_04['ActionDateHijri']) ? $sgn_04['ActionDateHijri'] : ''; ?>
                            </span>
                        </td>

                    </tr>

                    <?php
//    </editor-fold>
                    ?>

                    <?php
//    <editor-fold desc="----<  ریاست کارگاه  >----" defaultstate="collapsed" >
                    ?>
                    <tr class="border-tbl ">
                        <td class="border-rbl border-tbl blue-light-main-head text-center" colspan="50" >
                            <span class="font-bold pr5 pl5 font-size-10" style="margin-top: 10px;margin-bottom: -10px;direction: ltr;"> 
                                <?php echo 'ریاست کارگاه' ?></span>
                            </span>
                            &nbsp;</td>
                    </tr>
                    
                    <!--Invoice Specification-->
                    <tr class="border-tbl text-left">
                        <td class="border-trbl" colspan="7" rowspan="4">    
                            <span class="font-bold pr5 pl5 font-size-10" style="width: 64px;margin-top: -4px;margin-right: 5px;"> 
                                <?php echo 'رئیس کارگاه' ?></span></span></span>
                            </span>
                        </td>
                        
                        <td class="border-t border-r" colspan="5" rowspan="2" style="text-align: center;">    
                            <span class="pr5 pl5 font-size-10" style=""> 
                                <?php echo 'مبلغ تجمعی' ?>
                            </span>
                        </td>
                        
                        <td class="border-t border-r" colspan="10" rowspan="1" style="text-align: center;">    
                            <span class="pr5 pl5 font-size-10" style=""> 
                                <?php echo Utility::moneyFormatByCurrency($sgn_05['AmountApproved1Cumulative'], $processEntityModel->invoice->Currency1Title) ?>
                            </span>
                        </td>
                        <td class="border-t border-lr" colspan="3" rowspan="1" style="text-align: center;">    
                            <span class="pr5 pl5 font-size-10" style=""> 
                                <?php echo $processEntityModel->invoice->Currency1Title ?>
                            </span>
                        </td>

                        <td class="border-r border-tb" colspan="7" rowspan="4">    
                            <span class="font-bold pr5 pl5 font-size-10" style=""> 
                                
                            </span>
                        </td>
                        <td class="border-t" colspan="5" rowspan="2" style="text-align: center;">    
                            <span class="pr5 pl5 font-size-10" style=""> 
                                
                            </span>
                        </td>
                        <td class="border-t" colspan="10" rowspan="1" style="text-align: center;">    
                            <span class="pr5 pl5 font-size-10" style=""> 
                                
                            </span>
                        </td>
                        <td class="border-t border-l" colspan="3" rowspan="1" style="text-align: center;">    
                            <span class="pr5 pl5 font-size-10" style=""> 
                                
                            </span>
                        </td>
                    </tr>
                    
                    <tr class="border-tbl text-left">
                        <td class="border-t border-r" colspan="10" rowspan="1" style="text-align: center;">    
                            <span class="pr5 pl5 font-size-10" style=""> 
                                <?php echo Utility::moneyFormatByCurrency($sgn_05['AmountApproved2Cumulative'], $processEntityModel->invoice->Currency2Title) ?>
                            </span>
                        </td>
                        <td class="border-t border-lr" colspan="3" rowspan="1" style="text-align: center;">    
                            <span class="pr5 pl5 font-size-10" style=""> 
                                <?php echo $processEntityModel->invoice->Currency2Title ?>
                            </span>
                        </td>
                        <td class="" colspan="10" rowspan="1" style="text-align: center;">    
                            <span class="pr5 pl5 font-size-10" style=""> 
                                
                            </span>
                        </td>
                        <td class="border-l" colspan="3" rowspan="1" style="text-align: center;">    
                            <span class="pr5 pl5 font-size-10" style=""> 
                                
                            </span>
                        </td>
                    </tr>
                    
                    <?php if (false){ ?>
                        
                    <tr class="text-right">
                        <td class="border-trbl" colspan="18" rowspan="1" style="text-align: right;">    
                            <span class="pr5 pl5 font-size-10" style=""> 
                                <span class="pr5 pl5 font-size-10"> 
                                    <?php echo !empty($sgn_05['description']) ?'توضیحات:'.$sgn_05['description']:NULL; ?>
                                </span>
                            </span>
                        </td>
                        <td class="border-l border-b" colspan="18" rowspan="1" style="text-align: right;">    
                            <span class="pr5 pl5 font-size-10" style=""> 
                                <span class="pr5 pl5 font-size-10"> 
                                    
                                </span>
                            </span>
                        </td>
                    </tr>
                    <?php }  else { ?>
                        <tr class="text-right">
                            <td class="border-lr border-t" colspan="18" rowspan="1" style="text-align: right;">    
                                
                            </td>
                            <td class="border-l" colspan="18" rowspan="1" style="text-align: right;">    
                                
                            </td>
                        </tr>
                    <?php }?>
                    
                    <tr class="border-b text-left"> 

                        <td class="border-lr border-b" colspan="18" style="height: 70px;">    
                            <span style="margin-top: -16px;">
                            <?= !empty($sgn_05['uid']) ? yii\helpers\Html::img(['usersignature', 'uid' => $sgn_05['uid'], 'oid' => $sgn_05['oid']], [
                                        'style' => 'width: 90px;margin-top: -55px;margin-bottom: -61px;margin-left: 137px;'
                                    ]) : null;
                            ?>
                            </span>
                            <span class="font-bold pr5 pl5 font-size-10"> 
                                <?php echo!empty($sgn_05['UserName']) ? $sgn_05['UserName'] : ''; ?>
                            </span>

                            <span class="font-bold pr5 pl5 font-size-10" style="margin-bottom: -1px;"> 
                                <?php echo!empty($sgn_05['ActionDateHijri']) ? $sgn_05['ActionDateHijri'] : ''; ?>
                            </span>
                        </td>
                        <td class="border-l border-b" colspan="18" style="height: 70px;">    
                            <span style="margin-top: -16px;">
                            <?= !empty($sgn_non['uid']) ? yii\helpers\Html::img(['usersignature', 'uid' => $sgn_non['uid'], 'oid' => $sgn_non['oid']], [
                                        'style' => 'width: 90px;margin-top: -55px;margin-bottom: -61px;margin-left: 137px;'
                                    ]) : null;
                            ?>
                            </span>
                            <span class="font-bold pr5 pl5 font-size-10"> 
                                <?php echo!empty($sgn_non['UserName']) ? $sgn_non['UserName'] : ''; ?>
                            </span>

                            <span class="font-bold pr5 pl5 font-size-10" style="margin-bottom: -1px;"> 
                                <?php echo!empty($sgn_non['ActionDateHijri']) ? $sgn_non['ActionDateHijri'] : ''; ?>
                            </span>
                        </td>
                    </tr>
                    
                    <?php
//    </editor-fold>
                    ?>

                    <?php
//    <editor-fold desc="----<  مدیر مالی >----" defaultstate="collapsed" >
                    ?>
                    <tr class="border-tbl ">
                        <td class="border-rbl border-tbl blue-light-main-head text-center" colspan="50" >
                            <span class="font-bold pr5 pl5 font-size-10" style="margin-top: 10px;margin-bottom: -10px;direction: ltr;"> 
                                <?php echo 'مدیر مالی' ?></span>
                            </span>
                            &nbsp;</td>
                    </tr>
                    <tr class="border-tbl ">
                        <td class="border-rbl border-tbl blue-light-main-head text-center" colspan="10" >
                            <span class="font-bold pr5 pl5 font-size-10" style="margin-top: 10px;margin-bottom: -10px;direction: ltr;"> 
                                <?php echo 'شماره عطف سند' ?></span>
                            </span>
                            &nbsp;
						</td>
						<td class="border-rbl border-tbl blue-light-main-head text-center" colspan="15" >
                            <span class="font-bold pr5 pl5 font-size-10" style="margin-top: 10px;margin-bottom: -10px;direction: ltr;"> 
                                <?php echo '' ?></span>
                            </span>
                            &nbsp;
						</td>
						<td class="border-rbl border-tbl blue-light-main-head text-center" colspan="10" >
							<span class="font-bold pr5 pl5 font-size-10" style="margin-top: 10px;margin-bottom: -10px;direction: ltr;"> 
								<?php echo 'تاریخ سند ' ?></span>
							</span>
							&nbsp;
						</td>
						<td class="border-rbl border-tbl blue-light-main-head text-center" colspan="15" >
                            <span class="font-bold pr5 pl5 font-size-10" style="margin-top: 10px;margin-bottom: -10px;direction: ltr;"> 
                                <?php echo '' ?></span>
                            </span>
                            &nbsp;
						</td>
                    </tr>
                    <!--Invoice Specification-->
                    <tr class="border-tbl text-left">
                        <td class="border-trbl" colspan="7" rowspan="4">    
                            <span class="font-bold pr5 pl5 font-size-10" style="width: 64px;margin-top: -4px;margin-right: 5px;text-align: center;"> 
                                <?php echo 'کارشناس / مسئول حسابداری' ?></span></span></span>
                            </span>
                        </td>
                        
                        <td class="border-t border-r" colspan="5" rowspan="2" style="text-align: center;">    
                            <span class="pr5 pl5 font-size-10" style=""> 
                                <?php echo 'مبلغ تجمعی' ?>
                            </span>
                        </td>
                        
                        <td class="border-t border-r" colspan="10" rowspan="1" style="text-align: center;">    
                            <span class="pr5 pl5 font-size-10" style=""> 
                                <?php echo Utility::moneyFormatByCurrency($sgn_06['AmountApproved1Cumulative'], $processEntityModel->invoice->Currency1Title) ?>
                            </span>
                        </td>
                        <td class="border-t border-lr" colspan="3" rowspan="1" style="text-align: center;">    
                            <span class="pr5 pl5 font-size-10" style=""> 
                                <?php echo $processEntityModel->invoice->Currency1Title ?>
                            </span>
                        </td>

                        <td class="border-r border-tb" colspan="7" rowspan="4">    
                            <span class="font-bold pr5 pl5 font-size-10" style=""> 
                                
                            </span>
                        </td>
                        <td class="border-t" colspan="5" rowspan="2" style="text-align: center;">    
                            <span class="pr5 pl5 font-size-10" style=""> 
                                
                            </span>
                        </td>
                        <td class="border-t" colspan="10" rowspan="1" style="text-align: center;">    
                            <span class="pr5 pl5 font-size-10" style=""> 
                                
                            </span>
                        </td>
                        <td class="border-t border-l" colspan="3" rowspan="1" style="text-align: center;">    
                            <span class="pr5 pl5 font-size-10" style=""> 
                                
                            </span>
                        </td>
                    </tr>
                    
                    <tr class="border-tbl text-left">
                        <td class="border-t border-r" colspan="10" rowspan="1" style="text-align: center;">    
                            <span class="pr5 pl5 font-size-10" style=""> 
                                <?php echo Utility::moneyFormatByCurrency($sgn_06['AmountApproved2Cumulative'], $processEntityModel->invoice->Currency2Title) ?>
                            </span>
                        </td>
                        <td class="border-t border-lr" colspan="3" rowspan="1" style="text-align: center;">    
                            <span class="pr5 pl5 font-size-10" style=""> 
                                <?php echo $processEntityModel->invoice->Currency2Title ?>
                            </span>
                        </td>
                        <td class="" colspan="10" rowspan="1" style="text-align: center;">    
                            <span class="pr5 pl5 font-size-10" style=""> 
                                
                            </span>
                        </td>
                        <td class="border-l" colspan="3" rowspan="1" style="text-align: center;">    
                            <span class="pr5 pl5 font-size-10" style=""> 
                                
                            </span>
                        </td>
                    </tr>
                    
                    <?php if (false){ ?>
                        
                    <tr class="text-right">
                        <td class="border-trbl" colspan="18" rowspan="1" style="text-align: right;">    
                            <span class="pr5 pl5 font-size-10" style=""> 
                                <span class="pr5 pl5 font-size-10"> 
                                    <?php echo !empty($sgn_06['description']) ?'توضیحات:'.$sgn_06['description']:NULL; ?>
                                </span>
                            </span>
                        </td>
                        <td class="border-l border-b" colspan="18" rowspan="1" style="text-align: right;">    
                            <span class="pr5 pl5 font-size-10" style=""> 
                                <span class="pr5 pl5 font-size-10"> 
                                    
                                </span>
                            </span>
                        </td>
                    </tr>
                    <?php }  else { ?>
                        <tr class="text-right">
                            <td class="border-lr border-t" colspan="18" rowspan="1" style="text-align: right;">    
                                
                            </td>
                            <td class="border-l" colspan="18" rowspan="1" style="text-align: right;">    
                                
                            </td>
                        </tr>
                    <?php }?>
                    
                    <tr class="border-b text-left"> 

                        <td class="border-lr border-b" colspan="18" style="height: 70px;">    
                            <span style="margin-top: -16px;">
                            <?= !empty($sgn_06['uid']) ? yii\helpers\Html::img(['usersignature', 'uid' => $sgn_06['uid'], 'oid' => $sgn_06['oid']], [
                                        'style' => 'width: 90px;margin-top: -55px;margin-bottom: -61px;margin-left: 137px;'
                                    ]) : null;
                            ?>
                            </span>
                            <span class="font-bold pr5 pl5 font-size-10"> 
                                <?php echo!empty($sgn_06['UserName']) ? $sgn_06['UserName'] : ''; ?>
                            </span>

                            <span class="font-bold pr5 pl5 font-size-10" style="margin-bottom: -1px;"> 
                                <?php echo!empty($sgn_06['ActionDateHijri']) ? $sgn_06['ActionDateHijri'] : ''; ?>
                            </span>
                        </td>
                        <td class="border-l border-b" colspan="18" style="height: 70px;">    
                            <span style="margin-top: -16px;">
                            <?= !empty($sgn_non['uid']) ? yii\helpers\Html::img(['usersignature', 'uid' => $sgn_non['uid'], 'oid' => $sgn_non['oid']], [
                                        'style' => 'width: 90px;margin-top: -55px;margin-bottom: -61px;margin-left: 137px;'
                                    ]) : null;
                            ?>
                            </span>
                            <span class="font-bold pr5 pl5 font-size-10"> 
                                <?php echo!empty($sgn_non['UserName']) ? $sgn_non['UserName'] : ''; ?>
                            </span>

                            <span class="font-bold pr5 pl5 font-size-10" style="margin-bottom: -1px;"> 
                                <?php echo!empty($sgn_non['ActionDateHijri']) ? $sgn_non['ActionDateHijri'] : ''; ?>
                            </span>
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



