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

class InvoiceSearch extends InvoiceEdit {

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
    public $hasDocument;
    public $subProjectList;
    public $lastActionDateHijri;
    public $requested;
    public $creatorName;
    public $InsertDateHijri;
    public $InvoiceID;

    public function rules() {
        return [
            [['contractNo', 'clientTitle','contractSubject', 'subContractorTitle', 'contractTypeTitle', 'Title', 'invoiceType', 'invoiceTypeTitle', 'lastActionTitle', 'approved', 'income', 'penalty', 'currency', 'InvoiceType', 'CutOffDateHijri', 'CutOffDate', 'Description', 'Currency1Title', 'Currency2Title', 'AmountStatus', 'InsertDateHijri', 'LastUpdateHijri', 'subProjectList', 'lastActionDateHijri', 'hasDocument', 'creatorName', 'InvoiceID'], 'safe', 'on' => 'search'],
        ];
    }

    public static function getDb() {
        return \app\components\Utility::db();
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return [
            'InvoiceID' => 'Invoice ID',
            'ContractID' => 'Contract',
            'InvoiceType' => 'Invoice Type',
            'Title' => 'Invoice Number',
            'RevisionNumber' => 'Revision Number',
            'ParrentID' => 'Parrent',
            'CutOffDateHijri' => 'Cut Off Date Hijri',
            'CutOffDate' => 'Cut Off Date',
            'Description' => 'Description',
            'Currency1IDRef' => 'Currency1',
            'Currency1Title' => 'Currency1 Title',
            'Currency2IDRef' => 'Currency2',
            'Currency2Title' => 'Currency2 Title',
            'AmountStatus' => 'Amount Status',
            'UserIDRef1' => 'User Idref1',
            'InsertDateHijri' => 'Insert Date Hijri',
            'UserIDRef2' => 'User Idref2',
            'LastUpdateHijri' => 'Last Update Hijri',
        ];
    }

    public function searchContract($params, $cid, $amountStatus = '') {
          $userTable = \app\modules\account\models\User::tableName();
          $invoiceTable = \app\modules\invoice\models\InvoiceEdit::tableName();
    $activeQuery = \app\components\ActiveModel::find('\app\modules\invoice\models\InvoiceEdit')
        ->contract($cid)
        ->leftJoin($userTable, "$userTable.UserID = $invoiceTable.UserIDRef1");

        if ($amountStatus == 1) {
            $activeQuery->andWhere('AmountStatus=1');
        } elseif ($amountStatus == 5) {
            $activeQuery->andWhere('AmountStatus=5');
        } elseif ($amountStatus == 0 && $amountStatus != '') {
            $activeQuery->andWhere('AmountStatus=0');
        } elseif ($amountStatus == 6) {
            $activeQuery->andWhere('(AmountStatus=5 or AmountStatus=1)');
        }
//        echo $activeQuery->createCommand()->rawSql;
//        die();
        
        $dataProvider = new ActiveDataProvider([
            'query' => $activeQuery,
            'pagination' => [
                'pageSize' => Yii::$app->params['pageSize'],
            ],
        ]);

        $dataProvider->sort->defaultOrder = ['CutOffDateHijri' => SORT_DESC, 'CutOffDateHijri' => SORT_DESC];

        if ((!$this->load($params)) and $this->validate()) {
            return $dataProvider;
        }
        $activeQuery
                ->andFilterWhere(['LIKE', 'Title', $this->Title])
                ->andFilterWhere(['LIKE', 'CutOffDateHijri', $this->CutOffDateHijri])
                ->andFilterWhere(['=', 'InvoiceType', $this->invoiceType])
                ->andFilterWhere(['LIKE', 'Description', $this->Description])
                ->andFilterWhere(['or', ['LIKE', 'contractRevision.Price1', $this->approved], ['LIKE', 'contractRevision.Price2', $this->approved]])
                 ->andFilterWhere(['LIKE', 'invoice_table_invoice.InvoiceID', $this->InvoiceID])
                ->andFilterWhere(['LIKE', 'invoice_table_invoice.InsertDateHijri', $this->InsertDateHijri])
                 ->andFilterWhere([
                    'or',
                    ['LIKE', "CONCAT($userTable.FirstName, ' ', $userTable.LastName)", str_replace('ی', 'ي', $this->creatorName)],
                    ['LIKE', "CONCAT($userTable.FirstName, ' ', $userTable.LastName)", str_replace('ي', 'ی', $this->creatorName)],
                    ['LIKE', "CONCAT($userTable.FirstName, ' ', $userTable.LastName)", str_replace('ک', 'ك', $this->creatorName)],
                    ['LIKE', "CONCAT($userTable.FirstName, ' ', $userTable.LastName)", str_replace('ك', 'ک', $this->creatorName)],
                ]);
        return $dataProvider;
    }

    public function searchDebitmemoCreate($params, $cid) {

        $activeQuery = \app\components\ActiveModel::find('\app\modules\invoice\models\InvoiceEdit')->debitmemoCreate($cid);

//        echo $activeQuery->createCommand()->getRawSql();
        $dataProvider = new ActiveDataProvider([
            'query' => $activeQuery,
            'pagination' => [
                'pageSize' => Yii::$app->params['pageSize'],
            ],
        ]);

        $dataProvider->sort->defaultOrder = ['CutOffDateHijri' => SORT_DESC, 'CutOffDateHijri' => SORT_DESC];

        if ((!$this->load($params)) and $this->validate()) {
            return $dataProvider;
        }
        $activeQuery
                ->andFilterWhere(['LIKE', 'Title', $this->Title])
                ->andFilterWhere(['LIKE', 'CutOffDateHijri', $this->CutOffDateHijri])
                ->andFilterWhere(['=', 'InvoiceType', $this->invoiceType]);
        return $dataProvider;
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
        $invoiceDocumentTable = \app\modules\invoice\models\InvoiceDocument::tableName();
        $contractTypeTable = \app\modules\contract\models\ContractType::tableName();
        $userTable = \app\modules\account\models\User::tableName();
        /*$activeQuery = \app\components\ActiveModel::find('\app\modules\invoice\models\InvoiceEdit')
                ->innerJoin($contractTable, "$contractTable.ContractID=$invoiceTable.ContractID")
                ->leftJoin($contractTypeTable, "$contractTable.ContractType=$contractTypeTable.ContractTypeID")
                ->leftJoin($invoiceTypeTable, "$invoiceTypeTable.InvoiceTypeID=$invoiceTable.InvoiceType")
                ->innerJoin($userObsEntityTable, "$userObsEntityTable.entityId=$contractTable.ContractID")
                ->leftJoin("$personTable as subcontractor", "subcontractor.PersonID=$contractTable.SubContractorPersonIDRef")
                ->leftJoin($procesEntityTable, "$procesEntityTable.InvoiceIDRef=$invoiceTable.InvoiceID")
                ->leftJoin($actionTable, "$procesEntityTable.LastActionIdRef=$actionTable.ActionID")
                ->andWhere("$userObsEntityTable.userIdRef=$uid and $userObsEntityTable.obsIdRef=$obp and $userObsEntityTable.accessType='InvoiceList' and $userObsEntityTable.entityType='contract'");*/


        $contractQuery = \app\modules\contract\models\Contract::find()->contractAccess('InvoiceList');
        $activeQuery = \app\components\ActiveModel::find('\app\modules\invoice\models\InvoiceEdit')
                ->innerJoin(["contractQuery" => $contractQuery], "contractQuery.ContractID=$invoiceTable.ContractID")
                ->leftJoin($contractTypeTable, "contractQuery.ContractType=$contractTypeTable.ContractTypeID")
                ->leftJoin($invoiceTypeTable, "$invoiceTypeTable.InvoiceTypeID=$invoiceTable.InvoiceType")
                ->leftJoin("$personTable as subcontractor", "subcontractor.PersonID=contractQuery.SubContractorPersonIDRef")
                ->leftJoin("$personTable as client", "client.PersonID=contractQuery.SignerPersonIDRef")
                ->leftJoin($procesEntityTable, "$procesEntityTable.InvoiceIDRef=$invoiceTable.InvoiceID")
                ->leftJoin($actionTable, "$procesEntityTable.LastActionIdRef=$actionTable.ActionID")
                ->leftJoin($userTable, "$userTable.UserID = $invoiceTable.UserIDRef1");
        

        $dataProvider = new ActiveDataProvider([
            'query' => $activeQuery,
            'pagination' => [
                'pageSize' => Yii::$app->params['pageSize'],
            ],
        ]);

        $hasDocument = "IF((SELECT count(1) from $invoiceDocumentTable where $invoiceDocumentTable.InvoiceIDRef=$invoiceTable.InvoiceID and ($invoiceDocumentTable.isDeleted is null or $invoiceDocumentTable.isDeleted != 1)) > 0, 1, 0)";
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
                'hasDocument' => [
                    'asc' => [$hasDocument => SORT_ASC],
                    'desc' => [$hasDocument => SORT_DESC],
                ],

                'approved' => false,
            ],
                            'InvoiceID' => [
            'asc' => ['invoice_table_invoice.InvoiceID' => SORT_ASC],
            'desc' => ['invoice_table_invoice.InvoiceID' => SORT_DESC],
        ],
        
        // ✅ اضافه کردن InsertDateHijri
        'InsertDateHijri' => [
            'asc' => ['invoice_table_invoice.InsertDateHijri' => SORT_ASC],
            'desc' => ['invoice_table_invoice.InsertDateHijri' => SORT_DESC],
        ],
        
        // ✅ اضافه کردن creatorName
        'creatorName' => [
            'asc' => ["CONCAT($userTable.FirstName, ' ', $userTable.LastName)" => SORT_ASC],
            'desc' => ["CONCAT($userTable.FirstName, ' ', $userTable.LastName)" => SORT_DESC],
        ],
        ]));

        if ((!$this->load($params)) and $this->validate()) {
            return $dataProvider;
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
                ->andFilterWhere(['LIKE', $hasDocument, $this->hasDocument])
                ->andFilterWhere(['LIKE', $procesEntityTable . '.LastActionDateHijri', $this->lastActionDateHijri])
                  ->andFilterWhere(['LIKE', 'invoice_table_invoice.InvoiceID', $this->InvoiceID])
                ->andFilterWhere(['LIKE', 'invoice_table_invoice.InsertDateHijri', $this->InsertDateHijri])
                 ->andFilterWhere([
                    'or',
                    ['LIKE', "CONCAT($userTable.FirstName, ' ', $userTable.LastName)", str_replace('ی', 'ي', $this->creatorName)],
                    ['LIKE', "CONCAT($userTable.FirstName, ' ', $userTable.LastName)", str_replace('ي', 'ی', $this->creatorName)],
                    ['LIKE', "CONCAT($userTable.FirstName, ' ', $userTable.LastName)", str_replace('ک', 'ك', $this->creatorName)],
                    ['LIKE', "CONCAT($userTable.FirstName, ' ', $userTable.LastName)", str_replace('ك', 'ک', $this->creatorName)],
                ]);

        
        return $dataProvider;
    }

    public function totalAmount($params) {
        //نوشته شده توسط مهندس کمالی 
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
//       این دستور شرط گرفته شده در گرید اعمال میکند
        $activeQuery = \app\components\ActiveModel::find("\app\modules\invoice\models\InvoiceEdit")
                ->innerJoin($contractTable, "$contractTable.ContractID=$invoiceTable.ContractID")
                ->leftJoin($contractTypeTable, "$contractTable.ContractType=$contractTypeTable.ContractTypeID")
                ->leftJoin($invoiceTypeTable, "$invoiceTypeTable.InvoiceTypeID=$invoiceTable.InvoiceType")
                ->innerJoin($userObsEntityTable, "$userObsEntityTable.entityId=$contractTable.ContractID")
                ->leftJoin("$personTable as subcontractor", "subcontractor.PersonID=$contractTable.SubContractorPersonIDRef")
                ->leftJoin($procesEntityTable, "$procesEntityTable.InvoiceIDRef=$invoiceTable.InvoiceID")
                ->leftJoin($actionTable, "$procesEntityTable.LastActionIdRef=$actionTable.ActionID")
                ->andWhere("$userObsEntityTable.userIdRef=:uid and $userObsEntityTable.obsIdRef=:obp and $userObsEntityTable.accessType='InvoiceList' and $userObsEntityTable.entityType='contract'")
                ->addParams([':uid'=>$uid,':obp'=>$obp])
                ->addSelect([
            InvoiceEdit::tableName() . ".InvoiceID"
        ]);



        if (($this->load($params)) and $this->validate()) {
            $activeQuery
                    ->andFilterWhere(["LIKE", "$contractTable.ContractNo", $this->contractNo])
                    ->andFilterWhere(["or", ["LIKE", "$contractTable.ContractSubject", str_replace("ی", "ي", $this->contractSubject)]
                        , ["LIKE", "$contractTable.ContractSubject", str_replace("ي", "ی", $this->contractSubject)]
                        , ["LIKE", "$contractTable.ContractSubject", str_replace("ک", "ك", $this->contractSubject)]
                        , ["LIKE", "$contractTable.ContractSubject", str_replace("ك", "ک", $this->contractSubject)]
                    ])
                    ->andFilterWhere(["or", ["LIKE", "subcontractor.PersonTitle", str_replace("ی", "ي", $this->subContractorTitle)]
                        , ["LIKE", "subcontractor.PersonTitle", str_replace("ي", "ی", $this->subContractorTitle)]
                        , ["LIKE", "subcontractor.PersonTitle", str_replace("ک", "ك", $this->subContractorTitle)]
                        , ["LIKE", "subcontractor.PersonTitle", str_replace("ك", "ک", $this->subContractorTitle)]
                    ])
                    ->andFilterWhere(["LIKE", "$contractTypeTable.ContractTypeTitle", $this->contractTypeTitle])
                    ->andFilterWhere(["LIKE", "Title", $this->Title])
                    ->andFilterWhere(["LIKE", "$actionTable.ActionTitle2", $this->lastActionTitle])
                    ->andFilterWhere(["LIKE", "CutOffDateHijri", $this->CutOffDateHijri])
                    ->andFilterWhere(["LIKE", "$invoiceTypeTable.InvoiceType", $this->invoiceTypeTitle])
                    ->andFilterWhere(['LIKE', "$contractTable.subProjectList", $this->subProjectList])
                    ->andFilterWhere(['LIKE', $procesEntityTable . '.LastActionDateHijri', $this->lastActionDateHijri]);
        }

        $subQuery1 = (new \yii\db\Query())
                ->from($invoiceAmountTable)
                ->innerJoin($invoiceTable, "$invoiceTable.InvoiceID=$invoiceAmountTable.InvoiceID")
                ->select(["Currency1 as Currency", "sum($invoiceAmountTable.AmountApproved1) as AmountApproved", "sum($invoiceAmountTable.AmountRequested1) as AmountRequested", "(0) as Income", "(0) as Penalty"])
                ->where(["AmountType" => 1])
                ->andWhere(["IN", "$invoiceTable.InvoiceID", $activeQuery])
                ->groupBy("$invoiceAmountTable.Currency1")
        ;


        $subQuery2 = (new \yii\db\Query())
                ->from($invoiceAmountTable)
                ->innerJoin($invoiceTable, "$invoiceTable.InvoiceID=$invoiceAmountTable.InvoiceID")
                ->select(["Currency2 as Currency", "sum($invoiceAmountTable.AmountApproved2) as AmountApproved", "sum($invoiceAmountTable.AmountRequested2) as AmountRequested", "(0) as Income", "(0) as Penalty"])
                ->where(["AmountType" => 1])
                ->andWhere(["IN", "$invoiceTable.InvoiceID", $activeQuery])
                ->groupBy("$invoiceAmountTable.Currency2")
        ;

//        Income
        $subQuery3 = (new \yii\db\Query())
                ->from($invoiceAmountTable)
                ->innerJoin($invoiceTable, "$invoiceTable.InvoiceID=$invoiceAmountTable.InvoiceID")
                ->select(["Currency1 as Currency", "(0) as AmountApproved", "(0) as AmountRequested", "sum($invoiceAmountTable.Income1) as Income", "(0) as Penalty"])
                ->where(["IN", "$invoiceTable.InvoiceID", $activeQuery])
                ->groupBy("$invoiceAmountTable.Currency1")
        ;

        $subQuery4 = (new \yii\db\Query())
                ->from($invoiceAmountTable)
                ->innerJoin($invoiceTable, "$invoiceTable.InvoiceID=$invoiceAmountTable.InvoiceID")
                ->select(["Currency2 as Currency", "(0) as AmountApproved", "(0) as AmountRequested", "sum($invoiceAmountTable.Income2) as Income,(0) as Penalty"])
                ->where(["IN", "$invoiceTable.InvoiceID", $activeQuery])
                ->groupBy("$invoiceAmountTable.Currency2")
        ;
//        Penalty
        $subQuery5 = (new \yii\db\Query())
                ->from($invoiceAmountTable)
                ->innerJoin($invoiceTable, "$invoiceTable.InvoiceID=$invoiceAmountTable.InvoiceID")
                ->select(["Currency1 as Currency", "(0) as AmountApproved", "(0) as AmountRequested", "(0) as Income,sum($invoiceAmountTable.AmountApproved1) as Penalty"])
                ->where(["AmountType" => 3])
                ->andWhere(["IN", "$invoiceTable.InvoiceID", $activeQuery])
                ->groupBy("$invoiceAmountTable.Currency1")
        ;
        $subQuery6 = (new \yii\db\Query())
                ->from($invoiceAmountTable)
                ->innerJoin($invoiceTable, "$invoiceTable.InvoiceID=$invoiceAmountTable.InvoiceID")
                ->select(["Currency2 as Currency", "(0) as AmountApproved", "(0) as AmountRequested", "(0) as Income,sum($invoiceAmountTable.AmountApproved1) as Penalty"])
                ->where(["AmountType" => 3])
                ->andWhere(["IN", "$invoiceTable.InvoiceID", $activeQuery])
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
