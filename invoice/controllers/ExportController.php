<?php

namespace app\modules\invoice\controllers;

use yii\web\Controller;
use Yii;

class ExportController extends Controller {

    public $access = null;

    public function behaviors() {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['invoice-list'],
                'rules' => [
                    [
                        'actions' => ['invoice-list'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function($rule, $action, $exteraData = []) {
                            return \app\access\AccessControl::isAccess(['formAction', 'aid' => 131]);
                        },
                    ],
                ],
            ],
            'verbs' => [
                'class' => \yii\filters\VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionInvoiceList() {
        $contractTable = \app\modules\contract\models\Contract::tableName();
        $contractRevisionTable = \app\modules\contract\models\ContractRevision::tableName();
        $contractCategoryTable = \app\modules\contract\models\ContractCategory::tableName();
        $contractTypeTable = \app\modules\contract\models\ContractType::tableName();
        $contractRevisionTypeTable = \app\modules\contract\models\RevisionType::tableName();
        $contractStatusTable = \app\modules\contract\models\ContractStatus::tableName();
        $personTable = \app\modules\OBS\models\Person::tableName();
        $personCategoryTable = \app\modules\OBS\models\PersonCategory::tableName();
        $currencyTable = \app\modules\currency\models\Currency::tableName();
        $invoiceTable = \app\modules\invoice\models\InvoiceEdit::tableName();
        $invoiceAmountTable = \app\modules\invoice\models\InvoiceAmountEdit::tableName();

        $invoiceTypeTable = \app\modules\invoice\models\InvoiceTypes::tableName();
        $invoiceAmountTypesTable = \app\modules\invoice\models\InvoiceAmountTypes::tableName();
        $invoiceDocumentTable = \app\modules\invoice\models\InvoiceDocument::tableName();
        $actionTable = \app\modules\workflow\models\Action::tableName();
        $costCodeTable = \app\modules\wbs\models\Costcodenew::tableName();
        $processEntityTable = \app\modules\workflow\models\ProcessEntity::tableName();

        //_____________________________________________

        $invoiceSearchModel = new \app\modules\invoice\models\InvoiceSearch();
        $invoiceSearchModel->setScenario('search');
        $invoiceDataProvider = $invoiceSearchModel->search(Yii::$app->request->queryParams);

        $invoiceDataProvider->pagination->pageSize = 50000;


        $mainQuery1 = (new \yii\db\Query())
                ->from($invoiceTable)
                ->innerJoin(['subQuery' => "(" . $invoiceDataProvider->query->createCommand()->rawSql . ")"], "subQuery.InvoiceID=$invoiceTable.InvoiceID")
                ->leftJoin($contractTable, "$contractTable.ContractID=$invoiceTable.ContractID")
                ->leftJoin($invoiceTypeTable, "$invoiceTypeTable.InvoiceTypeID=$invoiceTable.InvoiceType")
                ->leftJoin($processEntityTable, "$processEntityTable.InvoiceIDRef=$invoiceTable.InvoiceID")
                ->leftJoin($actionTable, "$actionTable.ActionID=$processEntityTable.LastActionIDRef")
                ->leftJoin("$personTable as signer", "$contractTable.SignerPersonIDRef=signer.PersonID")
                ->leftJoin($contractTypeTable, "$contractTypeTable.ContractTypeID=$contractTable.ContractType")
                ->leftJoin("$personTable as subContractor", "$contractTable.SubContractorPersonIDRef=subContractor.PersonID")
                ->addSelect([
            "ContractID" => "$contractTable.ContractID",
            'ContractNo' => "$contractTable.ContractNo",
            'ContractSubject' => "$contractTable.ContractSubject",
            'ContractDate' => "$contractTable.ContractDate",
            'ContractDateHijri' => "$contractTable.ContractDateHijri",
            'ContractCategory' => "$contractTable.ContractCategory",
            'ContractTypeTitle' => "$contractTypeTable.ContractTypeTitle",
            'SubContractorPersonTitle' => "subContractor.PersonTitle",
            'Signer' => "signer.PersonTitle",
            'adjustmentPrice' => "$contractTable.adjustmentPrice1",
            'subProjectList' => "$contractTable.subProjectList",
            'InvoiceID' => "$invoiceTable.InvoiceID",
            'InvoiceType' => "$invoiceTypeTable.InvoiceType",
            'Title' => "$invoiceTable.Title",
            'RevisionNumber' => "$invoiceTable.RevisionNumber",
            'CutOffDateHijri' => "$invoiceTable.CutOffDateHijri",
            'CutOffDate' => "$invoiceTable.CutOffDate",
            'AmountStatus' => "$invoiceTable.AmountStatus",
            'Description' => "$invoiceTable.Description",
            'Approved' => "(select sum(AmountApproved1) from $invoiceAmountTable where InvoiceID=$invoiceTable.InvoiceID and AmountType= 1)",
            'Penalty' => "(select sum(AmountApproved1) from $invoiceAmountTable where InvoiceID=$invoiceTable.InvoiceID and AmountType= 3)",
            'Income' => "(select sum(Income1) from $invoiceAmountTable where InvoiceID=$invoiceTable.InvoiceID and AmountType= 1)",
            'approvedIrr' => "(select sum(approved1IrrF1) from $invoiceAmountTable where InvoiceID=$invoiceTable.InvoiceID and AmountType= 1)",
            'approvedUsd' => "(select sum(approved1UsdF1) from $invoiceAmountTable where InvoiceID=$invoiceTable.InvoiceID and AmountType= 1)",
            'hasDocument' => "IF((SELECT count(1) from $invoiceDocumentTable where $invoiceDocumentTable.InvoiceIDRef=$invoiceTable.InvoiceID and ($invoiceDocumentTable.isDeleted is null or $invoiceDocumentTable.isDeleted != 1)) > 0, 'دارد', 'ندارد')",
            'CurrencyTitle' => "$invoiceTable.Currency1Title",
            'LastActionDateHijri' => "$invoiceTable.LastUpdateHijri",
            'LastActionID' => "$actionTable.ActionID",
            'ActionTitle' => "$actionTable.ActionTitle2",
            'ActionTitle' => "$actionTable.ActionTitle2",
            'ActionTitle' => "$actionTable.ActionTitle2",
                ])
        ;

        $mainQuery2 = (new \yii\db\Query())
                ->from($invoiceTable)
                ->innerJoin(['subQuery' => "(" . $invoiceDataProvider->query->createCommand()->rawSql . ")"], "subQuery.InvoiceID=$invoiceTable.InvoiceID")
                ->leftJoin($contractTable, "$contractTable.ContractID=$invoiceTable.ContractID")
                ->leftJoin($invoiceTypeTable, "$invoiceTypeTable.InvoiceTypeID=$invoiceTable.InvoiceType")
                ->leftJoin($processEntityTable, "$processEntityTable.InvoiceIDRef=$invoiceTable.InvoiceID")
                ->leftJoin($actionTable, "$actionTable.ActionID=$processEntityTable.LastActionIDRef")
                ->leftJoin("$personTable as signer", "$contractTable.SignerPersonIDRef=signer.PersonID")
                ->leftJoin($contractTypeTable, "$contractTypeTable.ContractTypeID=$contractTable.ContractType")
                ->leftJoin("$personTable as subContractor", "$contractTable.SubContractorPersonIDRef=subContractor.PersonID")
                ->addSelect([
                    "ContractID" => "$contractTable.ContractID",
                    'ContractNo' => "$contractTable.ContractNo",
                    'ContractSubject' => "$contractTable.ContractSubject",
                    'ContractDate' => "$contractTable.ContractDate",
                    'ContractDateHijri' => "$contractTable.ContractDateHijri",
                    'ContractCategory' => "$contractTable.ContractCategory",
                    'ContractTypeTitle' => "$contractTypeTable.ContractTypeTitle",
                    'SubContractorPersonTitle' => "subContractor.PersonTitle",
                    'Signer' => "signer.PersonTitle",
                    'adjustmentPrice' => "$contractTable.adjustmentPrice1",
                    'subProjectList' => "$contractTable.subProjectList",
                    'InvoiceID' => "$invoiceTable.InvoiceID",
                    'InvoiceType' => "$invoiceTypeTable.InvoiceType",
                    'Title' => "$invoiceTable.Title",
                    'RevisionNumber' => "$invoiceTable.RevisionNumber",
                    'CutOffDateHijri' => "$invoiceTable.CutOffDateHijri",
                    'CutOffDate' => "$invoiceTable.CutOffDate",
                    'AmountStatus' => "$invoiceTable.AmountStatus",
                    'Description' => "$invoiceTable.Description",
                    'Approved' => "(select sum(AmountApproved2) from $invoiceAmountTable where InvoiceID=$invoiceTable.InvoiceID and AmountType= 1)",
                    'Penalty' => "(select sum(AmountApproved2) from $invoiceAmountTable where InvoiceID=$invoiceTable.InvoiceID and AmountType= 3)",
                    'Income' => "(select sum(Income2) from $invoiceAmountTable where InvoiceID=$invoiceTable.InvoiceID and AmountType= 1)",
                    'approvedIrr' => "(select sum(approved2IrrF1) from $invoiceAmountTable where InvoiceID=$invoiceTable.InvoiceID and AmountType= 1)",
                    'approvedUsd' => "(select sum(approved2UsdF1) from $invoiceAmountTable where InvoiceID=$invoiceTable.InvoiceID and AmountType= 1)",
                    'hasDocument' => "IF((SELECT count(1) from $invoiceDocumentTable where $invoiceDocumentTable.InvoiceIDRef=$invoiceTable.InvoiceID and ($invoiceDocumentTable.isDeleted is null or $invoiceDocumentTable.isDeleted != 1)) > 0, 'دارد', 'ندارد')",
                    'CurrencyTitle' => "$invoiceTable.Currency2Title",
                    'LastActionDateHijri' => "$invoiceTable.LastUpdateHijri",
                    'LastActionID' => "$actionTable.ActionID",
                    'ActionTitle' => "$actionTable.ActionTitle2",
                ])
                ->andWhere(['>', "$invoiceTable.Currency2IDRef", 0])
        ;

        $mainQuery = (new \yii\db\Query())
                ->from(["tempTable" => $mainQuery1
                    ->union($mainQuery2, TRUE)
                ])
                ->addSelect([
                    "ContractID",
                    'ContractNo',
                    'ContractSubject',
                    'ContractDate',
                    'ContractDateHijri',
                    'ContractCategory',
                    'ContractTypeTitle',
                    'SubContractorPersonTitle',
                    'Signer',
                    'adjustmentPrice',
                    'subProjectList',
                    'InvoiceID',
                    'InvoiceType',
                    'Title',
                    'RevisionNumber',
                    'hasDocument',
                    'CutOffDateHijri',
                    'CutOffDate',
                    'AmountStatus',
                    'Description',
                    'Approved',
                    'Penalty',
                    'Income',
                    'CurrencyTitle',
                    'LastActionDateHijri',
                    'LastActionID',
                    'ActionTitle',
                    'approvedIrr',
                    'approvedUsd',
                ])
                ->orderBy(["ContractID" => SORT_ASC, "Title" => SORT_ASC])
                ->all()
        ;

        $format = ['0' => 'integer',
            '1' => 'string',
            '2' => 'string',
            '3' => 'string',
            '4' => 'string',
            '5' => 'string',
            '6' => 'string',
            '7' => 'string',
            '8' => 'string',
            '9' => 'integer',
            '10' => 'string',
            '11' => 'integer',
            '12' => 'string',
            '13' => 'string',
            '14' => 'string',
            '15' => 'string',
            '16' => 'string',
            '17' => 'string',
            '18' => 'string',
            '19' => 'integer',
            '20' => 'integer',
            '21' => 'integer',
            '22' => 'string',
            '23' => 'string',
            '24' => 'integer',
            '25' => 'string',
            '26' => 'integer-sepatator',
            '27' => 'integer-sepatator',
            '28' => 'string',
            '29' => 'string',
        ];
        $activityManagement = new \app\components\ActivityManagement();
        $actionType = "Export";
        $subType = "ExportFile";
        $subTypeDescription["ExportName"] = "ExportInvoiceEdit";
        $subTypeDescription["FileName"] = "mainTemplate.xlsx";
        $subTypeDescription["FilePatch"] = \app\components\Templates::getTemplate('excel', 'mainTemplate.xlsx');
        $activityManagement->sendActitvityLog($actionType, $subType, $subTypeDescription);
        
        \app\components\Utility::exportDataAsLibxl($mainQuery, "Invoice_List", "Invoice_List", $format);
    }

}
