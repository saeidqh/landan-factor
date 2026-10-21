<?php

namespace app\modules\invoice\models;

use Yii;

/**
 * This is the model class for table "tblInvoiceDocumentType".
 *
 * @property integer $InvoiceDocumentTypeID
 * @property string $InvoiceDocumentTypeTitle
 */
class InvoiceDocumentType extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        if (Yii::$app->params['dmbs'] == 'mysql') {
            return 'invoice_table_invoice_document_type';
        } else {
            return 'tblInvoiceDocumentType';
        }
    }

    public static function primaryKey() {
        return ['InvoiceDocumentTypeID'];
    }

    public static function getDb() {
        return \app\components\Utility::centralDb();
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['InvoiceDocumentTypeTitle'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'InvoiceDocumentTypeID' => 'Invoice Document Type ID',
            'InvoiceDocumentTypeTitle' => 'Invoice Document Type Title',
        ];
    }

}
