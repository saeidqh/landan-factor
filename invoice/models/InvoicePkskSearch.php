<?php

namespace app\modules\invoice\models;

use Yii;
use yii\data\ActiveDataProvider;

class InvoicePkskSearch extends InvoicePksk {

    public $costcode;
    public $amountType;
    public $currency;
    public $ownerContractNo;
    
    /**
     * in searchContract
     * @var type 
     */
    public $invoiceNumber;

    public static function getDb() {
        return \app\components\Utility::db();
    }

    public function rules() {
        return [
            [['costcode', 'amountType', 'currency', 'ownerContractNo', 'CI_No', 'PL_No', 'MSR_No', 'MSR_Description', 'CI_Amount1', 'CI_Amount2', 'invoiceNumber'], 'safe', 'on' => 'search'],
        ];
    }

    public function searchInvoice($params, $invid) {
        
        $activeQuery = \app\components\ActiveModel::find('\app\modules\invoice\models\InvoicePksk')->invoice($invid)->joinWith(['currency1'])
                ->leftJoin(\app\modules\currency\models\Currency::tableName(). ' as currency2', 'currency2.CurrencyID='.InvoicePksk::tableName().'.CI_Currency2IDRef');


        $dataProvider = new ActiveDataProvider([
            'query' => $activeQuery,
            'pagination' => [
                'pageSize' => Yii::$app->params['pageSize'],
            ],
        ]);

        if ((!$this->load($params)) and $this->validate()) {
            return $dataProvider;
        }
        
        return $dataProvider;
    }
    
    
    
    public function searchContarct($params, $cid) {
        $invoiceTable = InvoiceEdit::tableName();
        $invoiceQuery = (new \yii\db\Query())->from($invoiceTable)->select('InvoiceID')
                ->where(['ContractID' => $cid]);

        $activeQuery = InvoicePksk::find()->where(['IN', 'InvoiceIDRef', $invoiceQuery])
                ->joinWith(['currency1', 'invoice'])
                ->leftJoin(\app\modules\currency\models\Currency::tableName(). ' as currency2', 'currency2.CurrencyID='.InvoicePksk::tableName().'.CI_Currency2IDRef');


        $dataProvider = new ActiveDataProvider([
            'query' => $activeQuery,
            'pagination' => [
                'pageSize' => Yii::$app->params['pageSize'],
            ],
        ]);

        if ((!$this->load($params)) and $this->validate()) {
            return $dataProvider;
        }
        $activeQuery->andFilterWhere(['LIKE', "$invoiceTable.title", $this->invoiceNumber])
                ->andFilterWhere(['LIKE', "CI_No", $this->CI_No])
                ->andFilterWhere(['LIKE', "PL_No", $this->PL_No])
                ->andFilterWhere(['LIKE', "MSR_No", $this->MSR_No])
                ->andFilterWhere(['LIKE', "MSR_Description", $this->MSR_Description])
                ->andFilterWhere(['LIKE', "CI_Amount1", $this->CI_Amount1])
                 ->andFilterWhere(['LIKE', "CI_Amount2", $this->CI_Amount2]);
        return $dataProvider;
    }

}
