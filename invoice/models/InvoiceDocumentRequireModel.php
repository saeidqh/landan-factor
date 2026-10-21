<?php

namespace app\modules\invoice\models;

use Yii;

/**
 * This is the model class for table "tblInvoiceDocumentRequireModel".
 *
 * @property integer $ID
 * @property integer $DocumentRequireModel
 * @property integer $InvoiceDocumentTypeIDRef
 * @property string $RequireType
 */
class InvoiceDocumentRequireModel extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'invoice_table_invoice_document_require_model';
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
            [['DocumentRequireModel', 'InvoiceDocumentTypeIDRef'], 'integer'],
            [['RequireType'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'ID' => 'ID',
            'DocumentRequireModel' => 'Document Require Model',
            'InvoiceDocumentTypeIDRef' => 'Invoice Document Type Idref',
            'RequireType' => 'Require Type',
        ];
    }

    public function getType() {
        return $this->hasOne(\app\modules\invoice\models\InvoiceDocumentType::className(), ['InvoiceDocumentTypeID' => 'InvoiceDocumentTypeIDRef']);
    }

}
