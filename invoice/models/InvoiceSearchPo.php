<?php

namespace app\modules\invoice\models;

use Yii;
use yii\data\ActiveDataProvider;
use app\modules\workflow\models\Action;
use app\modules\workflow\models\ProcessEntity;
use app\modules\contract\models\Contract;
use app\modules\OBS\models\Person;
use app\modules\contract\models\ContractType;
use app\modules\invoice\models\InvoiceTypes;

class InvoiceSearchPo extends InvoiceEdit {

    public $ContractNo;
    public $ContractSubject;
    public $signerTitle;
    public $invoiceTypeTitle;
    public $approved;
    public $income;
    public $penalty;
    public $currency;

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return [
            [['ContractNo', 'ContractSubject', 'signerTitle', 'Title', 'invoiceTypeTitle', 'approved', 'income', 'penalty', 'currency', 'InvoiceType', 'Title', 'CutOffDateHijri', 'CutOffDate',], 'safe', 'on' => 'search'],
        ];
    }

    public static function getDb() {
        return \app\components\Utility::db();
    }

    public function search($params, $type, $projectKeys = null, $amountStatus = 5) {

        $uid = Yii::$app->session->get('UserID');
        $obp = Yii::$app->session->get('ObsPosition');

        $contractTable = \app\modules\contract\models\Contract::tableName();
        $invoiceTable = \app\modules\invoice\models\InvoiceEdit::tableName();

        $personTable = \app\modules\OBS\models\Person::tableName();

        $invoiceTypeTable = \app\modules\invoice\models\InvoiceTypes::tableName();

        $amountStatusArray = [];
        if ($amountStatus == 0 || $amountStatus == 1 || $amountStatus == 5) {
            $amountStatusArray = [$amountStatus];
        } elseif ($amountStatus == 6) {
            $amountStatusArray = [1, 5];
        }

        //        $activeQuery = static::find()
//                ->innerJoin($contractTable, "$contractTable.ContractID=$invoiceTable.ContractID")
//                ->leftJoin($invoiceTypeTable, "$invoiceTypeTable.InvoiceTypeID=$invoiceTable.InvoiceType")
//               ->innerJoin($userObsEntityTable, "$userObsEntityTable.entityId=$contractTable.ContractID")
//                ->leftJoin("$personTable as signer", "signer.PersonID=$contractTable.SignerPersonIDRef")
//                ->andWhere("$userObsEntityTable.userIdRef=$uid and $userObsEntityTable.obsIdRef=$obp and $userObsEntityTable.accessType='InvoiceList' //and $userObsEntityTable.entityType='contract'")
//                ->andWhere("$contractTable.ContractCategory=13")
//                ->andWhere(["$invoiceTable.AmountStatus"=>$amountStatusArray]);

        $contractQuery = \app\modules\contract\models\Contract::find()->contractAccess('InvoiceList');
        $activeQuery = static::find()
                ->innerJoin($contractTable, "$contractTable.ContractID=$invoiceTable.ContractID")
                ->leftJoin($invoiceTypeTable, "$invoiceTypeTable.InvoiceTypeID=$invoiceTable.InvoiceType")
                ->leftJoin("$personTable as signer", "signer.PersonID=$contractTable.SignerPersonIDRef")
                ->innerJoin(["contractQuery" => $contractQuery], "contractQuery.ContractID=$invoiceTable.ContractID")
                ->andWhere("$contractTable.ContractCategory=13")
                ->andWhere(["$invoiceTable.AmountStatus" => $amountStatusArray]);

        if ($type == 'LC') {
            $activeQuery->andWhere("$invoiceTable.InvoiceType=17");
        } elseif ($type == 'Logestic') {
            $activeQuery->andWhere("$invoiceTable.InvoiceType=18");
        } elseif ($type == 'Insurance') {
            $activeQuery->andWhere("$invoiceTable.InvoiceType=19");
        } elseif ($type == 'ServieceFee') {
            $activeQuery->andWhere("$invoiceTable.InvoiceType=20");
        } elseif ($type == 'Advance') {
            $activeQuery->andWhere("$invoiceTable.InvoiceType=21");
        } else {
            $activeQuery->andWhere("$invoiceTable.InvoiceType<=5");
        }

        $activeQuery->addSelect([
            'InvoiceID',
            'ContractNo' => "$contractTable.ContractNo",
            'ContractSubject' => "$contractTable.ContractSubject",
            'signerTitle' => "signer.PersonTitle",
            'Title' => "Title",
            'CutOffDateHijri' => "CutOffDateHijri",
            'invoiceTypeTitle' => "$invoiceTypeTable.InvoiceType",
            'Currency1Title',
            'Currency2Title',
        ]);

        $dataProvider = new ActiveDataProvider([
            'db' => \app\components\Utility::db(),
            'query' => $activeQuery,
            'pagination' => [
                'pageSize' => Yii::$app->params['pageSize'],
            ],
            'sort' => [
                'attributes' => [
                    'ContractNo' => [
                        'asc' => ["$contractTable.ContractNo" => SORT_ASC],
                        'desc' => ["$contractTable.ContractNo" => SORT_DESC],
                    ],
                    'ContractSubject' => [
                        'asc' => ["$contractTable.ContractSubject" => SORT_ASC],
                        'desc' => ["$contractTable.ContractSubject" => SORT_DESC],
                    ],
                    'signerTitle' => [
                        'asc' => ["signer.PersonTitle" => SORT_ASC],
                        'desc' => ["signer.PersonTitle" => SORT_DESC],
                    ],
                    'Title',
                    'CutOffDateHijri',
                    'invoiceTypeTitle' => [
                        'asc' => ["$invoiceTypeTable.InvoiceType" => SORT_ASC],
                        'desc' => ["$invoiceTypeTable.InvoiceType" => SORT_DESC],
                    ],
                ]
            ],
        ]);


        if ((!$this->load($params)) and $this->validate()) {
            $res['dataProvider'] = $dataProvider;
            $res['totalDataProvider'] = $this->totalAmount($activeQuery, $projectKeys);
            return $res;
        }
        $activeQuery
                ->andFilterWhere(['LIKE', Contract::tableName() . '.ContractNo', $this->ContractNo])
                ->andFilterWhere(['LIKE', Contract::tableName() . '.ContractSubject', $this->ContractSubject])
                ->andFilterWhere(["or", ["LIKE", "signer.PersonTitle", str_replace("ی", "ي", $this->signerTitle)]
                    , ["LIKE", "signer.PersonTitle", str_replace("ي", "ی", $this->signerTitle)]
                    , ["LIKE", "signer.PersonTitle", str_replace("ک", "ك", $this->signerTitle)]
                    , ["LIKE", "signer.PersonTitle", str_replace("ك", "ک", $this->signerTitle)]
                ])->andFilterWhere(['LIKE', 'Title', $this->Title])
                ->andFilterWhere(['LIKE', 'CutOffDateHijri', $this->CutOffDateHijri])
                ->andFilterWhere(['LIKE', InvoiceTypes::tableName() . '.InvoiceType', $this->invoiceTypeTitle]);
        $res['dataProvider'] = $dataProvider;
        $res['totalDataProvider'] = $this->totalAmount($activeQuery, $projectKeys);
        return $res;
    }

    private function totalAmount($invoiceQuery, $projectKeys) {
        if ($projectKeys == '') {
            $projectKeys = '0';
        }
        $invoiceAmountTable = \app\modules\invoice\models\InvoiceAmountEdit::tableName();
        $costcodeTable = \app\modules\wbs\models\Costcodenew::tableName();

        $currencyQuery = (new \yii\db\Query())->select(['CurrencyTitle' => 'Currency1Title'])
                ->from(['invoiceQueryCurrency1' => $invoiceQuery])
                ->union((new \yii\db\Query())->select(['Currency2Title'])
                ->from(['invoiceQueryCurrency2' => $invoiceQuery])
                ->where("Currency2Title is not null and Currency2Title<>''"));


        $approvedQuery = (new \yii\db\Query())
                ->select([
                    'CurrencyTitle' => 'invoiceQueryApproved1.Currency1Title',
                    'AmountRequested' => "sum($invoiceAmountTable.AmountRequested1)",
                    'AmountApproved' => "sum($invoiceAmountTable.AmountApproved1)",
                ])
                ->from($invoiceAmountTable)
                ->innerJoin(['invoiceQueryApproved1' => $invoiceQuery], "invoiceQueryApproved1.InvoiceID=$invoiceAmountTable.InvoiceID")
                ->innerJoin("$costcodeTable", "$costcodeTable.NodeID=$invoiceAmountTable.CostCode")
                ->andWhere("$invoiceAmountTable.AmountType=1")
                ->andWhere(['in',"$costcodeTable.ProjectIDRef",$projectKeys])
                ->groupBy('invoiceQueryApproved1.Currency1Title')
                ->union(
                (new \yii\db\Query())
                ->select([
                    'CurrencyTitle' => 'invoiceQueryApproved2.Currency2Title',
                    'AmountRequested' => "sum($invoiceAmountTable.AmountRequested2)",
                    'AmountApproved' => "sum($invoiceAmountTable.AmountApproved2)",
                ])
                ->from($invoiceAmountTable)
                ->innerJoin(['invoiceQueryApproved2' => $invoiceQuery], "invoiceQueryApproved2.InvoiceID=$invoiceAmountTable.InvoiceID")
                ->innerJoin("$costcodeTable", "$costcodeTable.NodeID=$invoiceAmountTable.CostCode")
                ->andWhere("Currency2Title is not null and Currency2Title<>''")
                ->andWhere("$invoiceAmountTable.AmountType=1")
                ->andWhere(['in',"$costcodeTable.ProjectIDRef",$projectKeys])
                ->groupBy('invoiceQueryApproved2.Currency2Title')
        );



        $penaltyQuery = (new \yii\db\Query())
                ->select([
                    'CurrencyTitle' => 'invoiceQueryPenalty1.Currency1Title',
                    'Penalty' => "sum($invoiceAmountTable.AmountApproved1)",
                ])
                ->from($invoiceAmountTable)
                ->innerJoin(['invoiceQueryPenalty1' => $invoiceQuery], "invoiceQueryPenalty1.InvoiceID=$invoiceAmountTable.InvoiceID")
                ->innerJoin("$costcodeTable", "$costcodeTable.NodeID=$invoiceAmountTable.CostCode")
                ->andWhere("$invoiceAmountTable.AmountType=3")
                ->andWhere(['in',"$costcodeTable.ProjectIDRef",$projectKeys])
                ->groupBy('invoiceQueryPenalty1.Currency1Title')
                ->union(
                (new \yii\db\Query())
                ->select([
                    'CurrencyTitle' => 'invoiceQueryPenalty2.Currency2Title',
                    'Penalty' => "sum($invoiceAmountTable.AmountApproved2)",
                ])
                ->from($invoiceAmountTable)
                ->innerJoin(['invoiceQueryPenalty2' => $invoiceQuery], "invoiceQueryPenalty2.InvoiceID=$invoiceAmountTable.InvoiceID")
                ->innerJoin("$costcodeTable", "$costcodeTable.NodeID=$invoiceAmountTable.CostCode")
                ->andWhere("Currency2Title is not null and Currency2Title<>''")
                ->andWhere("$invoiceAmountTable.AmountType=3")
                 ->andWhere(['in',"$costcodeTable.ProjectIDRef",$projectKeys])
                ->groupBy('invoiceQueryPenalty2.Currency2Title')
        );



        $incomeQuery = (new \yii\db\Query())
                ->select([
                    'CurrencyTitle' => 'invoiceQueryIncome1.Currency1Title',
                    'Income' => "sum($invoiceAmountTable.Income1)",
                ])
                ->from($invoiceAmountTable)
                ->innerJoin(['invoiceQueryIncome1' => $invoiceQuery], "invoiceQueryIncome1.InvoiceID=$invoiceAmountTable.InvoiceID")
                ->innerJoin("$costcodeTable", "$costcodeTable.NodeID=$invoiceAmountTable.CostCode")
                ->andWhere("$invoiceAmountTable.AmountType=1")
                 ->andWhere(['in',"$costcodeTable.ProjectIDRef",$projectKeys])
                ->groupBy('invoiceQueryIncome1.Currency1Title')
                ->union(
                (new \yii\db\Query())
                ->select([
                    'CurrencyTitle' => 'invoiceQueryIncome2.Currency2Title',
                    'Income' => "sum($invoiceAmountTable.Income2)",
                ])
                ->from($invoiceAmountTable)
                ->innerJoin(['invoiceQueryIncome2' => $invoiceQuery], "invoiceQueryIncome2.InvoiceID=$invoiceAmountTable.InvoiceID")
                ->innerJoin("$costcodeTable", "$costcodeTable.NodeID=$invoiceAmountTable.CostCode")
                ->andWhere("Currency2Title is not null and Currency2Title<>''")
                ->andWhere("$invoiceAmountTable.AmountType=1")
                 ->andWhere(['in',"$costcodeTable.ProjectIDRef",$projectKeys])
                ->groupBy('invoiceQueryIncome2.Currency2Title')
        );

        $totalQuery = (new \yii\db\Query())->select([
                    'CurrencyTitle' => 'currencyQueryTotal.CurrencyTitle',
                    'Requested' => 'sum(approvedQueryTotal.AmountRequested)',
                    'Approved' => 'sum(approvedQueryTotal.AmountApproved)',
                    'Income' => 'sum(incomeQueryTotal.Income)',
                    'Penalty' => 'sum(penaltyQueryTotal.Penalty)',
                ])->from(['currencyQueryTotal' => $currencyQuery])
                ->leftJoin(['approvedQueryTotal' => $approvedQuery], "approvedQueryTotal.CurrencyTitle=currencyQueryTotal.CurrencyTitle")
                ->leftJoin(['incomeQueryTotal' => $incomeQuery], "incomeQueryTotal.CurrencyTitle=currencyQueryTotal.CurrencyTitle")
                ->leftJoin(['penaltyQueryTotal' => $penaltyQuery], "penaltyQueryTotal.CurrencyTitle=currencyQueryTotal.CurrencyTitle")
                ->groupBy('currencyQueryTotal.CurrencyTitle');

        $totalArray = $totalQuery->all();
        return new \yii\data\ArrayDataProvider(
                [
            "allModels" => $totalArray,
                ]
        );
    }

}
