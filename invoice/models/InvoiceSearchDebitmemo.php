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

class InvoiceSearchDebitmemo extends InvoiceEdit {

    public $invoiceType;
    public $approved;
    public $currency;

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return [
            [['Title', 'invoiceType', 'approved', 'currency', 'CutOffDateHijri', 'LastUpdateHijri'], 'safe', 'on' => 'search'],
        ];
    }

    public static function getDb() {
        return \app\components\Utility::db();
    }

    public function search($params, $debitmemoId) {

        $activeQuery = \app\components\ActiveModel::find('\app\modules\invoice\models\InvoiceEdit')->debitmemo($debitmemoId);

        $dataProvider = new ActiveDataProvider([
            'query' => $activeQuery,
            'pagination' => [
                'pageSize' => Yii::$app->params['pageSize'],
            ],
        ]);

        $dataProvider->sort->defaultOrder = ['CutOffDateHijri' => SORT_DESC, 'CutOffDateHijri' => SORT_DESC];

        if ((!$this->load($params)) and $this->validate()) {
            $res['dataProvider'] = $dataProvider;
            $res['totalDataProvider'] = $this->totalDataProvider($params, $debitmemoId);
            return $res;
        }
        $activeQuery
                ->andFilterWhere(['LIKE', 'Title', $this->Title])
                ->andFilterWhere(['LIKE', 'CutOffDateHijri', $this->CutOffDateHijri])
                ->andFilterWhere(['=', 'InvoiceType', $this->invoiceType]);
        $res['dataProvider'] = $dataProvider;
        $res['totalDataProvider'] = $this->totalDataProvider($params, $debitmemoId);
        return $res;
    }

    private function totalDataProvider($params, $debitmemoId) {
        $invoiceTable = \app\modules\invoice\models\InvoiceEdit::tableName();
        $invoiceAmountTable = \app\modules\invoice\models\InvoiceAmountEdit::tableName();
        $debitmemoAmountTable = \app\modules\debitmemo\models\DebitmemoAmount::tableName();
        $currencyTable = \app\modules\currency\models\Currency::tableName();
        $activeQuery = \app\components\ActiveModel::find('\app\modules\invoice\models\InvoiceEdit')
                ->innerJoin($invoiceAmountTable, "$invoiceAmountTable.InvoiceID=$invoiceTable.InvoiceID")
                ->innerJoin("$currencyTable as cur1", "$invoiceAmountTable.Currency1=cur1.CurrencyID")
                ->leftJoin("$currencyTable as cur2", "$invoiceAmountTable.Currency2=cur2.CurrencyID")
                ->debitmemo($debitmemoId)
                ->addSelect([
            "$invoiceAmountTable.AmountRequested1",
            "$invoiceAmountTable.AmountRequested2",
            "$invoiceAmountTable.AmountApproved1",
            "$invoiceAmountTable.AmountApproved2",
            "Currency1Title" => "cur1.CurrencyTitle",
            "Currency2Title" => "cur2.CurrencyTitle",
        ]);
        $activeQuery
                ->andFilterWhere(['LIKE', 'Title', $this->Title])
                ->andFilterWhere(['LIKE', 'CutOffDateHijri', $this->CutOffDateHijri])
                ->andFilterWhere(['=', 'InvoiceType', $this->invoiceType]);


        $totalQuery1 = (new \yii\db\Query)->select([
                    'CurrencyTitle' => "Currency1Title",
                    'requested' => "sum(AmountRequested1)",
                    'approved' => "sum(AmountApproved1)",
                ])->from(['total1' => $activeQuery])
                ->groupBy(['Currency1Title']);

        $totalQuery2 = (new \yii\db\Query)->select([
                    'CurrencyTitle' => "Currency2Title",
                    'requested' => "sum(AmountRequested2)",
                    'approved' => "sum(AmountApproved2)",
                ])->from(['total2' => $activeQuery])
                ->where("Currency2Title<>''")
                ->groupBy(['Currency2Title']);

        $totalQuery = (new \yii\db\Query())->select([
                    'CurrencyTitle',
                    'requested',
                    'approved',
                ])->from(['total' => $totalQuery1])
                ->union($totalQuery2);

        $totalDataProvider = new ActiveDataProvider([
            'query' => $totalQuery,
            'pagination' => false,
        ]);

        return $totalDataProvider;
    }

}
