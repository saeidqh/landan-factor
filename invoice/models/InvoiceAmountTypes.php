<?php

namespace app\modules\invoice\models;

use Yii;

/**
 * This is the model class for table "tblInvoiceAmountTypes".
 *
 * @property integer $ID
 * @property string $InvoiceAmountType
 */
class InvoiceAmountTypes extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        if (Yii::$app->params['dmbs'] == 'mysql') {
            return 'invoice_table_invoice_amount_type';
        } else {
            return 'tblInvoiceAmountTypes';
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
            [['InvoiceAmountType'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'ID' => 'ID',
            'InvoiceAmountType' => 'Invoice Amount Type',
        ];
    }

}
