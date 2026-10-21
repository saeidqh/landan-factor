<?php

namespace app\modules\invoice\models;

use Yii;

/**
 * This is the model class for table "dbo.tblInvoiceTypes".
 *
 * @property integer $InvoiceTypeID
 * @property string $InvoiceType
 */
class InvoiceTypes extends \yii\db\ActiveRecord {

    const GHATI_TYPE = "3";
    /**
     * @inheritdoc
     */
    public static function tableName() {
        if (Yii::$app->params['dmbs'] == 'mysql') {
            return 'invoice_table_invoice_type';
        } else {
            return 'tblInvoiceTypes';
        }
    }

    public static function primaryKey() {
        return ['InvoiceTypeID'];
    }

    public static function getDb() {
        return \app\components\Utility::centralDb();
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['InvoiceType'], 'string'],
            [['InvoiceTypeID'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'InvoiceTypeID' => 'Invoice Type ID',
            'InvoiceType' => 'Invoice Type',
        ];
    }

}
