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

class InvoiceSearchMaincontract extends InvoiceEdit {

    public $contractNo;
    public $contractSubject;
    public $subContractorTitle;
    public $clientTitle;
    public $contractTypeTitle;
    public $invoiceType;
    public $invoiceTypeTitle;
    public $lastActionTitle;
    public $approved;
    public $income;
    public $penalty;
    public $currency;
    public $subProjectList;
    public $lastActionDateHijri;

    public function rules() {
        return [
            [['contractNo', 'clientTitle', 'contractSubject', 'subContractorTitle', 'contractTypeTitle', 'Title', 'invoiceType', 'invoiceTypeTitle', 'lastActionTitle', 'approved', 'income', 'penalty', 'currency', 'InvoiceType', 'CutOffDateHijri', 'CutOffDate', 'Description', 'Currency1Title', 'Currency2Title', 'AmountStatus', 'InsertDateHijri', 'LastUpdateHijri', 'subProjectList', 'lastActionDateHijri'], 'safe', 'on' => 'search'],
        ];
    }

    public static function getDb() {
        return \app\components\Utility::db();
    }

    public function search($params) {

        $uid = Yii::$app->session->get('UserID');
        $obp = Yii::$app->session->get('ObsPosition');

        $contractTable = \app\modules\contract\models\Contract::tableName();
        $invoiceTable = \app\modules\invoice\models\InvoiceEdit::tableName();
        $userObsEntityTable = \app\components\CandoHelper::braketTable(\app\modules\account\models\UserObsEntity::tableName());
        $procesEntityTable = \app\modules\workflow\models\ProcessEntity::tableName();
        $personTable = \app\modules\OBS\models\Person::tableName();
        $actionTable = \app\modules\workflow\models\Action::tableName();
        $invoiceTypeTable = \app\modules\invoice\models\InvoiceTypes::tableName();
        $contractTypeTable = \app\modules\contract\models\ContractType::tableName();

        $contractQuery = \app\modules\contract\models\Contract::find()->maincontract('view');
        $activeQuery = static::find()
                ->innerJoin(["contractQuery" => $contractQuery], "contractQuery.ContractID=$invoiceTable.ContractID")
                ->leftJoin($contractTypeTable, "contractQuery.ContractType=$contractTypeTable.ContractTypeID")
                ->leftJoin($invoiceTypeTable, "$invoiceTypeTable.InvoiceTypeID=$invoiceTable.InvoiceType")
                ->leftJoin("$personTable as subcontractor", "subcontractor.PersonID=contractQuery.SubContractorPersonIDRef")
                ->leftJoin("$personTable as client", "client.PersonID=contractQuery.SignerPersonIDRef")
                ->innerJoin($procesEntityTable, "$procesEntityTable.InvoiceIDRef=$invoiceTable.InvoiceID")
                ->innerJoin($actionTable, "$procesEntityTable.LastActionIdRef=$actionTable.ActionID");

//        $activeQuery->addSelect([
//            'contractNo'=>'contractQuery.ContractNo',
//            'contractSubject'=>'contractQuery.contractSubject',
//            'subContractorTitle'=>'subcontractor.PersonTitle',
//            'clientTitle'=>'client.PersonTitle',
//            'InvoiceID'=>"$invoiceTable.InvoiceID",
//        ]);


        $dataProvider = new ActiveDataProvider([
            'query' => $activeQuery,
            'pagination' => [
                'pageSize' => Yii::$app->params['pageSize'],
            ],
        ]);

        $dataProvider->setSort(new \yii\data\Sort([
            'attributes' => [
                'contractNo' => [
                    'asc' => ['contractQuery.contractNo' => SORT_ASC],
                    'desc' => ['contractQuery.contractNo' => SORT_DESC],
                ],
                'lastActionDateHijri' => [
                    'asc' => [ProcessEntity::tableName() . '.LastActionDateHijri' => SORT_ASC],
                    'desc' => [ProcessEntity::tableName() . '.LastActionDateHijri' => SORT_DESC],
                ],
                'contractSubject' => [
                    'asc' => ['contractQuery.contractSubject' => SORT_ASC],
                    'desc' => ['contractQuery.contractSubject' => SORT_DESC],
                ],
                'subContractorTitle' => [
                    'asc' => ['subcontractor.PersonTitle' => SORT_ASC],
                    'desc' => ['subcontractor.PersonTitle' => SORT_DESC],
                ],
                'clientTitle' => [
                    'asc' => ['client.PersonTitle' => SORT_ASC],
                    'desc' => ['client.PersonTitle' => SORT_DESC],
                ],
                'contractTypeTitle' => [
                    'asc' => ['subcontractor.PersonTitle' => SORT_ASC],
                    'desc' => ['subcontractor.PersonTitle' => SORT_DESC],
                ],
                'Title' => [
                    'asc' => ['Title' => SORT_ASC],
                    'desc' => ['Title' => SORT_DESC],
                ],
                'CutOffDateHijri' => [
                    'asc' => ['CutOffDateHijri' => SORT_ASC],
                    'desc' => ['CutOffDateHijri' => SORT_DESC],
                ],
                'invoiceTypeTitle' => [
                    'asc' => [InvoiceTypes::tableName() . '.InvoiceType' => SORT_ASC],
                    'desc' => [InvoiceTypes::tableName() . '.InvoiceType' => SORT_DESC],
                ],
                'lastActionTitle' => [
                    'asc' => [Action::tableName() . '.ActionTitle2' => SORT_ASC],
                    'desc' => [Action::tableName() . '.ActionTitle2' => SORT_DESC],
                ],
                'approved' => false,
            ]
        ]));

        
        
        if ((!$this->load($params)) and $this->validate()) {
            $totalDataProvider = $this->totalAmount($activeQuery);
            return [
                'dataProvider' => $dataProvider,
                'totalDataProvider' => $totalDataProvider,
            ];
        }
        $activeQuery
                ->andFilterWhere(['LIKE', 'contractQuery.ContractNo', $this->contractNo])
                ->andFilterWhere(['or', ['LIKE', 'contractQuery.ContractSubject', str_replace('ی', 'ي', $this->contractSubject)]
                    , ['LIKE', 'contractQuery.ContractSubject', str_replace('ي', 'ی', $this->contractSubject)]
                    , ['LIKE', 'contractQuery.ContractSubject', str_replace('ک', 'ك', $this->contractSubject)]
                    , ['LIKE', 'contractQuery.ContractSubject', str_replace('ك', 'ک', $this->contractSubject)]
                ])
                ->andFilterWhere(['or', ['LIKE', 'subcontractor.PersonTitle', str_replace('ی', 'ي', $this->subContractorTitle)]
                    , ['LIKE', 'subcontractor.PersonTitle', str_replace('ي', 'ی', $this->subContractorTitle)]
                    , ['LIKE', 'subcontractor.PersonTitle', str_replace('ک', 'ك', $this->subContractorTitle)]
                    , ['LIKE', 'subcontractor.PersonTitle', str_replace('ك', 'ک', $this->subContractorTitle)]
                ])
                ->andFilterWhere(['or', ['LIKE', 'client.PersonTitle', str_replace('ی', 'ي', $this->clientTitle)]
                    , ['LIKE', 'client.PersonTitle', str_replace('ي', 'ی', $this->clientTitle)]
                    , ['LIKE', 'client.PersonTitle', str_replace('ک', 'ك', $this->clientTitle)]
                    , ['LIKE', 'client.PersonTitle', str_replace('ك', 'ک', $this->clientTitle)]
                ])
                ->andFilterWhere(['LIKE', ContractType::tableName() . '.ContractTypeTitle', $this->contractTypeTitle])
                ->andFilterWhere(['LIKE', 'Title', $this->Title])
                ->andFilterWhere(['LIKE', Action::tableName() . '.ActionTitle2', $this->lastActionTitle])
                ->andFilterWhere(['LIKE', 'CutOffDateHijri', $this->CutOffDateHijri])
                ->andFilterWhere(['LIKE', InvoiceTypes::tableName() . '.InvoiceType', $this->invoiceTypeTitle])
                ->andFilterWhere(['LIKE', 'contractQuery.subProjectList', $this->subProjectList])
                ->andFilterWhere(['LIKE', 'contractQuery.subProjectList', $this->subProjectList])
                ->andFilterWhere(['LIKE', 'contractQuery.subProjectList', $this->subProjectList])
                ->andFilterWhere(['LIKE', $procesEntityTable . '.LastActionDateHijri', $this->lastActionDateHijri])
        ;
        $totalDataProvider = $this->totalAmount($activeQuery);
        return [
            'dataProvider' => $dataProvider,
            'totalDataProvider' => $totalDataProvider,
        ];
    }

    public function totalAmount($InvoiceQuery) {
        $invoiceAmountTable = InvoiceAmountEdit::tableName();
        $invoiceTable = InvoiceEdit::tableName();


        $uid = Yii::$app->session->get("UserID");
        $obp = Yii::$app->session->get("ObsPosition");


        $contractTable = \app\modules\contract\models\Contract::tableName();
        $invoiceTable = \app\modules\invoice\models\InvoiceEdit::tableName();
        $invoiceAmountTable = \app\modules\invoice\models\InvoiceAmountEdit::tableName();
        $userObsEntityTable = \app\components\CandoHelper::braketTable(\app\modules\account\models\UserObsEntity::tableName());
        $procesEntityTable = \app\modules\workflow\models\ProcessEntity::tableName();
        $personTable = \app\modules\OBS\models\Person::tableName();
        $actionTable = \app\modules\workflow\models\Action::tableName();
        $invoiceTypeTable = \app\modules\invoice\models\InvoiceTypes::tableName();
        $contractTypeTable = \app\modules\contract\models\ContractType::tableName();
        $currencyTable = \app\modules\currency\models\Currency::tableName();

        

        $subQuery1 = (new \yii\db\Query())
                ->from($invoiceAmountTable)
                ->innerJoin(['invoiceQuery'=>$InvoiceQuery], "invoiceQuery.InvoiceID=$invoiceAmountTable.InvoiceID")
                ->select(["Currency1 as Currency", "sum($invoiceAmountTable.AmountApproved1) as AmountApproved", "sum($invoiceAmountTable.AmountRequested1) as AmountRequested", "(0) as Income", "(0) as Penalty"])
                ->where(["AmountType" => 1])
                ->groupBy("$invoiceAmountTable.Currency1")
        ;


        $subQuery2 = (new \yii\db\Query())
                ->from($invoiceAmountTable)
                ->innerJoin(['invoiceQuery'=>$InvoiceQuery], "invoiceQuery.InvoiceID=$invoiceAmountTable.InvoiceID")
                ->select(["Currency2 as Currency", "sum($invoiceAmountTable.AmountApproved2) as AmountApproved", "sum($invoiceAmountTable.AmountRequested2) as AmountRequested", "(0) as Income", "(0) as Penalty"])
                ->where(["AmountType" => 1])
                ->groupBy("$invoiceAmountTable.Currency2")
        ;

//        Income
        $subQuery3 = (new \yii\db\Query())
                ->from($invoiceAmountTable)
                ->innerJoin(['invoiceQuery'=>$InvoiceQuery], "invoiceQuery.InvoiceID=$invoiceAmountTable.InvoiceID")
                ->select(["Currency1 as Currency", "(0) as AmountApproved", "(0) as AmountRequested", "sum($invoiceAmountTable.Income1) as Income", "(0) as Penalty"])
                ->groupBy("$invoiceAmountTable.Currency1")
        ;

        $subQuery4 = (new \yii\db\Query())
                ->from($invoiceAmountTable)
                ->innerJoin(['invoiceQuery'=>$InvoiceQuery], "invoiceQuery.InvoiceID=$invoiceAmountTable.InvoiceID")
                ->select(["Currency2 as Currency", "(0) as AmountApproved", "(0) as AmountRequested", "sum($invoiceAmountTable.Income2) as Income,(0) as Penalty"])
                ->groupBy("$invoiceAmountTable.Currency2")
        ;
//        Penalty
        $subQuery5 = (new \yii\db\Query())
                ->from($invoiceAmountTable)
                ->innerJoin(['invoiceQuery'=>$InvoiceQuery], "invoiceQuery.InvoiceID=$invoiceAmountTable.InvoiceID")
                ->select(["Currency1 as Currency", "(0) as AmountApproved", "(0) as AmountRequested", "(0) as Income,sum($invoiceAmountTable.AmountApproved1) as Penalty"])
                ->where(["AmountType" => 3])
                ->groupBy("$invoiceAmountTable.Currency1")
        ;
        $subQuery6 = (new \yii\db\Query())
                ->from($invoiceAmountTable)
                ->innerJoin(['invoiceQuery'=>$InvoiceQuery], "invoiceQuery.InvoiceID=$invoiceAmountTable.InvoiceID")
                ->select(["Currency2 as Currency", "(0) as AmountApproved", "(0) as AmountRequested", "(0) as Income,sum($invoiceAmountTable.AmountApproved1) as Penalty"])
                ->where(["AmountType" => 3])
                ->groupBy("$invoiceAmountTable.Currency2")
        ;
        $subQuery7 = (new \yii\db\Query())->from($currencyTable)->select("CurrencyTitle");

        $mainQuery1 = (new \yii\db\Query())
                ->from(["temptable" => $subQuery1
                    ->union($subQuery2, TRUE)
                    ->union($subQuery3, TRUE)
                    ->union($subQuery4, TRUE)
                    ->union($subQuery5, TRUE)
                    ->union($subQuery6, TRUE)
                ])
                ->innerJoin($currencyTable, $currencyTable . ".CurrencyID=temptable.Currency")
                ->select([
                    "Currency",
                    "CurrencyTitle" => $subQuery7->where($currencyTable . ".CurrencyID=temptable.Currency")
                    , "Sum(temptable.AmountApproved) as Approved", "Sum(temptable.AmountRequested) as Requested", "Sum(temptable.Income) as Income", "Sum(temptable.Penalty) as Penalty"])
                ->groupBy("temptable.Currency")
                ->having(["and",
                    ["not", ["temptable.Currency" => null]],
                    ["not", ["temptable.Currency" => 0]],
                ])
                ->all()
        ;

        return new \yii\data\ArrayDataProvider(
                [
            "allModels" => $mainQuery1,
                ]
        );
    }

}
