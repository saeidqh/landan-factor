<?php

namespace app\modules\invoice\models;

use Yii;
use yii\data\ActiveDataProvider;
use app\modules\currency\models\Currency;

/**
 * This is the model class for table "log.tblInvoiceAmounts".
 *
 * @property integer $ID
 * @property integer $InvoiceID
 * @property integer $amountId
 * @property integer $AmountType
 * @property string $CostCode
 * @property integer $Currency1
 * @property double $AmountRequested1
 * @property double $AmountApproved1
 * @property integer $Currency2
 * @property double $AmountRequested2
 * @property double $AmountApproved2
 * @property double $Income1
 * @property double $Income2
 * @property integer $OwnerContractIDRef
 * @property integer $userIdRef
 * @property integer $actionIDRef
 * @property string $updateHijri
 */
class InvoiceAmountLog extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        if (Yii::$app->params['dmbs'] == 'mysql') {
            return 'invoice_table_invoice_amount_log';
        } else {
            return 'log.tblInvoiceAmounts';
        }
    }

    public static function primaryKey() {
        return ['ID'];
    }

    public static function getDb() {
        return \app\components\Utility::centralDb();
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['InvoiceID', 'amountId', 'AmountType', 'Currency1', 'Currency2', 'OwnerContractIDRef', 'userIdRef', 'actionIDRef'], 'integer'],
            [['CostCode', 'updateHijri'], 'string'],
            [['AmountRequested1', 'AmountApproved1', 'AmountRequested2', 'AmountApproved2', 'Income1', 'Income2'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'ID' => 'ID',
            'InvoiceID' => 'Invoice ID',
            'amountId' => 'Amount ID',
            'AmountType' => 'Amount Type',
            'CostCode' => 'Cost Code',
            'Currency1' => 'Currency1',
            'AmountRequested1' => 'Amount Requested1',
            'AmountApproved1' => 'Amount Approved1',
            'Currency2' => 'Currency2',
            'AmountRequested2' => 'Amount Requested2',
            'AmountApproved2' => 'Amount Approved2',
            'Income1' => 'Income1',
            'Income2' => 'Income2',
            'OwnerContractIDRef' => 'Owner Contract Idref',
            'userIdRef' => 'User Id Ref',
            'actionIDRef' => 'Action Idref',
            'updateHijri' => 'Update Hijri',
        ];
    }

    public static function invoiceAmountLogDataProvider($invid) {
        $invoiceAmountLogTable= \app\modules\invoice\models\InvoiceAmountLog::tableName();
        $currencyTable= \app\modules\currency\models\Currency::tableName();
        $activeQuery = static::find()->joinWith(['user', 'action', 'type', 'costCode', 'owner'])
                ->leftJoin("$currencyTable as curr1", "curr1.CurrencyID=$invoiceAmountLogTable.Currency1")
                ->leftJoin("$currencyTable as curr2", "curr2.CurrencyID=$invoiceAmountLogTable.Currency2")
                ->where(['InvoiceID' => $invid]);


        $dataProvider = new ActiveDataProvider([
            'db' => \app\components\Utility::db(),
            'query' => $activeQuery,
            'pagination' => [
                'pageSize' => Yii::$app->params['pageSize'],
            ],
        ]);
        return $dataProvider;
    }

    public function getCurrency1() {
        return $this->hasOne(\app\modules\currency\models\Currency::className(), ['CurrencyID' => 'Currency1']);
    }

    public function getCurrency2() {
        return $this->hasOne(\app\modules\currency\models\Currency::className(), ['CurrencyID' => 'Currency2']);
    }

    public function getCostCode() {
        return $this->hasOne(\app\modules\wbs\models\Costcodenew::className(), ['NodeID' => 'CostCode']);
    }

    public function getOwner() {
        return $this->hasOne(\app\modules\contract\models\Contract::className(), ['ContractID' => 'OwnerContractIDRef']);
    }

    public function getType() {
        return $this->hasOne(\app\modules\invoice\models\InvoiceAmountTypes::className(), ['ID' => 'AmountType']);
    }

    public function getUser() {
        return $this->hasOne(\app\modules\account\models\User::className(), ['UserID' => 'userIdRef']);
    }

    public function getAction() {
        return $this->hasOne(\app\modules\workflow\models\Action::className(), ['ActionID' => 'actionIDRef']);
    }

}
