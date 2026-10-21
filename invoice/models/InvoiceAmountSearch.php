<?php

namespace app\modules\invoice\models;

use Yii;
use yii\data\ActiveDataProvider;
use app\modules\contract\models\Contract;
use app\modules\OBS\models\Person;
use app\components\ActiveModel;
use app\modules\invoice\models\InvoiceEdit;
use app\modules\invoice\models\InvoiceAmountEdit;

class InvoiceAmountSearch extends InvoiceAmountEdit {

    public $costcode;
    public $amountType;
    public $currency;
    //executer
    public $executerContractNo;
    public $executerContractSubject;
    public $executerSubcontractor;
    public $executerInvoiceNo;
    public $executerCutOffDateHijri;
    public $executerCostCode;
    //owner
    public $ownerContractNo;
    public $ownerContractSubject;
    public $ownerSubcontractor;
    public $ownerInvoiceNo;
    public $ownerCutOffDateHijri;
    public $ownerCostCode;

    public static function getDb() {
        return \app\components\Utility::db();
    }

    public function rules() {
        return [
            [['costcode', 'amountType', 'currency', 'ownerContractNo'
            , 'executerContractNo', 'executerContractSubject', 'executerSubcontractor', 'executerInvoiceNo', 'executerCutOffDateHijri', 'executerCostCode'
            , 'ownerContractNo', 'ownerContractSubject', 'ownerSubcontractor', 'ownerInvoiceNo', 'ownerCutOffDateHijri', 'ownerCostCode', 'AmountRequested1', 'AmountApproved1', 'Income1'
                ], 'safe', 'on' => 'search'],
        ];
    }

    public $AMOUNT_STATUS_VALUES = array(
        'Approved' => 'Approved',
        'Checking' => 'Checking',
        'Approved Or Checking' => 'Approved Or Checking',
    );

public function searchInvoice($params, $invid) {
        $currencyTable = \app\modules\currency\models\Currency::tableName();
        $InvoiceAmountEditTable = InvoiceAmountEdit::tableName();
        $activeQuery = \app\components\ActiveModel::find('\app\modules\invoice\models\InvoiceAmountEdit')->invoice($invid)->joinWith(['currency1', 'costCode', 'owner', 'type'])
                ->leftJoin("$currencyTable as currency2", 'currency2.CurrencyID=' . InvoiceAmountEdit::tableName() . '.Currency2');


        $dataProvider = new ActiveDataProvider([
            'query' => $activeQuery,
            'pagination' => [
                'pageSize' => Yii::$app->params['pageSize'],
            ],
        ]);
        $this->load($params);

        if ((!$this->load($params)) and $this->validate()) {
            return $dataProvider;
        }
        
        $activeQuery
                ->andFilterWhere(['LIKE', \app\modules\wbs\models\Costcodenew::tableName() . '.NodeFullCode', $this->costcode])
                ->andFilterWhere(['LIKE', InvoiceAmountTypes::tableName() . '.InvoiceAmountType', $this->amountType])
                ->andFilterWhere(['LIKE', Contract::tableName() . '.ContractNo', $this->ownerContractNo])
                ->andFilterWhere(['or', 
                                 ['LIKE', "$InvoiceAmountEditTable.AmountRequested1", $this->AmountRequested1], 
                                 ['LIKE', "$InvoiceAmountEditTable.AmountRequested2", $this->AmountRequested1]]
                )
                ->andFilterWhere(['or', 
                                 ['LIKE', "$InvoiceAmountEditTable.AmountApproved1", $this->AmountApproved1], 
                                 ['LIKE', "$InvoiceAmountEditTable.AmountApproved2", $this->AmountApproved1]]
                )
                ->andFilterWhere(['or', 
                                 ['LIKE', "$InvoiceAmountEditTable.Income1", $this->Income1], 
                                 ['LIKE', "$InvoiceAmountEditTable.Income2", $this->Income1]]
                );
                
        return $dataProvider;
    }

    public function searchContractDebtor($params, $cid, $amountStatus) {

        $activeQuery = ActiveModel::find('\app\modules\invoice\models\InvoiceAmountEdit')->joinWith(['type', 'costCode', 'owner', 'invoice'])
                ->innerJoin(Contract::tableName() . ' as ownerContract', InvoiceEdit::tableName() . '.ContractID=ownerContract.ContractID')
                ->innerJoin(Person::tableName() . ' as subContractor', Contract::tableName() . '.SubContractorPersonIDRef=subContractor.PersonID')
                ->contractDebtor($cid, $amountStatus);


        $dataProvider = new ActiveDataProvider([
            'query' => $activeQuery,
            'sort' => false,
            'pagination' => [
                'pageSize' => Yii::$app->params['pageSize'],
            ],
        ]);
        $this->load($params);

        if ((!$this->load($params)) and $this->validate()) {
            $res['dataProvider'] = $dataProvider;
            $reTotal = $this->totalDataProvider($activeQuery);
            $res['incomeEquivalent'] = $reTotal['incomeEquivalent'];
            $res['totalDataProvider'] = $reTotal['totalDataProvider'];
            return $res;
        }

        $activeQuery
                ->andFilterWhere(['LIKE', 'ownerContract.ContractNo', $this->ownerContractNo])
                ->andFilterWhere(['LIKE', 'ownerContract.ContractSubject', $this->ownerContractSubject])
                ->andFilterWhere(['LIKE', InvoiceEdit::tableName() . '.Title', $this->ownerInvoiceNo])
                ->andFilterWhere(['LIKE', \app\modules\wbs\models\Costcodenew::tableName() . '.NodeFullCode', $this->ownerCostCode])
                ->andFilterWhere(['or', ['LIKE', InvoiceAmountEdit::tableName() . '.Income1', $this->Income1], ['LIKE', InvoiceAmountEdit::tableName() . '.Income2', $this->Income1]])
                ->andFilterWhere(['LIKE', InvoiceEdit::tableName() . '.CutOffDateHijri', $this->ownerCutOffDateHijri]);
        $res['dataProvider'] = $dataProvider;
        $reTotal = $this->totalDataProvider($activeQuery);
        $res['incomeEquivalent'] = $reTotal['incomeEquivalent'];
        $res['totalDataProvider'] = $reTotal['totalDataProvider'];
        return $res;
    }

    public function searchContractCreditor($params, $cid, $amountStatus) {
        $activeQuery = \app\components\ActiveModel::find('\app\modules\invoice\models\InvoiceAmountEdit')->joinWith(['type', 'costCode', 'owner', 'invoice'])
                ->innerJoin(\app\modules\OBS\models\Person::tableName() . ' as subContractor', \app\modules\contract\models\Contract::tableName() . '.SubContractorPersonIDRef=subContractor.PersonID')
                ->contractCreditor($cid, $amountStatus);

        $dataProvider = new ActiveDataProvider([
            'query' => $activeQuery,
            'pagination' => [
                'pageSize' => Yii::$app->params['pageSize'],
            ],
        ]);
        $this->load($params);

        if ((!$this->load($params)) and $this->validate()) {
            $res['dataProvider'] = $dataProvider;
            $reTotal = $this->totalDataProvider($activeQuery);
            $res['incomeEquivalent'] = $reTotal['incomeEquivalent'];
            $res['totalDataProvider'] = $reTotal['totalDataProvider'];
            return $res;
        }
        $activeQuery
                ->andFilterWhere(['LIKE', 'ContractNo', $this->executerContractNo])
                ->andFilterWhere(['LIKE', 'subContractor.PersonTitle', $this->executerSubcontractor])
                ->andFilterWhere(['LIKE', InvoiceEdit::tableName() . '.Title', $this->executerInvoiceNo])
                ->andFilterWhere(['LIKE', InvoiceEdit::tableName() . '.CutOffDateHijri', $this->executerCutOffDateHijri])
                ->andFilterWhere(['LIKE', \app\modules\wbs\models\Costcodenew::tableName() . '.NodeFullCode', $this->executerCostCode]);
        $res['dataProvider'] = $dataProvider;
        $reTotal = $this->totalDataProvider($activeQuery);
        $res['incomeEquivalent'] = $reTotal['incomeEquivalent'];
        $res['totalDataProvider'] = $reTotal['totalDataProvider'];
        return $res;
    }

    private function totalDataProvider($invoiceAmountQuery) {
        $incomeEquivalentQuery = clone($invoiceAmountQuery);
        $invoiceAmountTable = \app\modules\invoice\models\InvoiceAmountEdit::tableName();
        $currencyTable = \app\modules\currency\models\Currency::tableName();
        $amount1Query = static::find()
                ->innerJoin("$currencyTable", "$currencyTable.CurrencyID=$invoiceAmountTable.Currency1")
                ->innerJoin(['invoiceAmountQuery' => $invoiceAmountQuery], "invoiceAmountQuery.ID=$invoiceAmountTable.ID")
                ->addSelect([
            "CurrencyTitle" => "$currencyTable.CurrencyTitle",
            "requested" => "$invoiceAmountTable.AmountRequested1",
            "approved" => "$invoiceAmountTable.AmountApproved1",
            "income" => "$invoiceAmountTable.Income1",
        ]);


        $amount2Query = static::find()
                ->innerJoin("$currencyTable", "$currencyTable.CurrencyID=$invoiceAmountTable.Currency2")
                ->innerJoin(['invoiceAmountQuery' => $invoiceAmountQuery], "invoiceAmountQuery.ID=$invoiceAmountTable.ID")
                ->addSelect([
            "CurrencyTitle" => "$currencyTable.CurrencyTitle",
            "requested" => "$invoiceAmountTable.AmountRequested2",
            "approved" => "$invoiceAmountTable.AmountApproved2",
            "income" => "$invoiceAmountTable.Income2",
        ]);


        $amountQuery = (new \yii\db\Query())->select([
                    'CurrencyTitle',
                    'requested',
                    'approved',
                    'income',
                ])->from(['amount1Query' => $amount1Query])
                ->union($amount2Query, true);

        $totalQuery = (new \yii\db\Query())->select([
                    'CurrencyTitle' => "currencyTitle",
                    'requested' => "sum(requested)",
                    'approved' => "sum(approved)",
                    'income' => "sum(income)",
                ])->from(['amountQuery' => $amountQuery])
                ->groupBy(['currencyTitle']);

        $totalDataProvider = new ActiveDataProvider([
            'query' => $totalQuery,
            'pagination' => false,
        ]);
        $incomeData = $incomeEquivalentQuery->asArray()->all();
        $incomeEquivalent = [];
        $incomeEquivalent[0]['CurrencyTitle'] = 'IRR';
        $incomeEquivalent[0]['CurrencyID'] = 1;
        $incomeEquivalent[1]['CurrencyTitle'] = 'USD';
        $incomeEquivalent[1]['CurrencyID'] = 3;

        $incomeEquivalent[0]['income'] = 0;
        $incomeEquivalent[1]['income'] = 0;
        for ($i = 0; $i < count($incomeData); $i++) {
            // IRR
            $incomeEquivalent[0]['income'] += ($incomeData[$i]['Income1Rial'] + $incomeData[$i]['Income2Rial']);
            // USD
            $incomeEquivalent[1]['income'] += ($incomeData[$i]['Income1Dollar'] + $incomeData[$i]['Income2Dollar']);
        }

        return [
            'totalDataProvider' => $totalDataProvider,
            'incomeEquivalent' => $incomeEquivalent,
        ];
    }

}
