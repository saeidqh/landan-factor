<?php

namespace app\modules\invoice\models;

use Yii;

/**
 * This is the model class for table "tblInvoiceDocument".
 *
 * The followings are the available columns in table 'tblInvoiceDocument':
 * @property integer $InvoiceDocumentID
 * @property integer $InvoiceIDref
 * @property integer $originalNumber
 * @property integer $copyNumber
 * @property integer $InvoiceDocumentTypeIDref
 * @property string $FileName
 * @property string $Description
 * @property integer $InvoiceActionIDRef
 * @property integer $UserIDRef
 * @property string $InsertDateHijri
 * @property integer $hasChange
 * @property integer $isDeleted
 */
class InvoiceDocument extends \yii\db\ActiveRecord {
    use \app\components\DocumentTraitPro;
    
    public function getConfigDoc() {
        return [
            'folder' => 'invoice',
            'files'=>[
                'file'=>null,
            ],
        ];
    }
    
    public $file;
	
    /**
     * @return string the associated database table name
     */
    public static function tableName() {
        if (Yii::$app->params['dmbs'] == 'mysql') {
            return 'invoice_table_invoice_document';
        } else {
            return 'tblInvoiceDocument';
        }
    }

    public static function primaryKey() {
        return ['InvoiceDocumentID'];
    }

    public static function getDb() {
        return \app\components\Utility::centralDb();
    }

    /**
     * @return array validation rules for model attributes.
    */
    
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            [['InvoiceIDref','originalNumber','copyNumber', 'InvoiceDocumentTypeIDref', 'InvoiceActionIDRef', 'UserIDRef', 'hasChange', 'isDeleted'], 'integer'],
            [['FileName', 'Description'], 'string', 'max' => 255],
            ['InsertDateHijri', 'string', 'max' => 50],
            [['Description','InvoiceDocumentTypeIDref'], 'validateDescription', 'skipOnEmpty' => false, 'on' => 'add'],
            [['InvoiceDocumentTypeIDref', 'FileName'], 'required'],
            ['file', 'file', 'skipOnEmpty' => false, 'on' => 'add'],
        ];
    }
    
    public function validateDescription($attribute, $params) {
        if ($this->InvoiceDocumentTypeIDref == 17 && (!isset($this->Description) || empty($this->Description))) {
                $this->addError($attribute, 'You should Fill Description in "Other" Type');
        }
    }
    
    /**
     * @return array customized attribute labels (name=>label)
    */
    
    public function attributeLabels() {
        return [
            'InvoiceDocumentID' => Yii::t('invoice' , 'ID'),
            'InvoiceIDref' => Yii::t('invoice' , 'Invoice Idref'),
            'InvoiceDocumentTypeIDref' => Yii::t('invoice' , 'Document Type'),
            'FileName' => Yii::t('invoice' , 'File Name'),
            'Description' => Yii::t('invoice' , 'Description'),
            'InvoiceActionIDRef' => Yii::t('invoice' , 'Invoice Action Idref'),
            'UserIDRef' => Yii::t('invoice' , 'User Idref'),
            'InsertDateHijri' => Yii::t('invoice' , 'Insert Date Hijri'),
            'hasChange' => Yii::t('invoice' , 'Has Change'),
            'userName' => Yii::t('invoice' , 'User Name'),
            'documentType' => Yii::t('invoice' , 'Document Type'),
            'file' => Yii::t('invoice' , 'File'),
            'actionTitle' => Yii::t('invoice' , 'Action Title'),
            'originalNumber' => Yii::t('invoice' , 'Original Number'),
            'copyNumber' => Yii::t('invoice' , 'Copy Number'),
            
        ];
    }

    public static function find() {
        return new InvoiceDocumentQuery(get_called_class());
    }

    public function getUser() {
        return $this->hasOne(\app\modules\account\models\User::className(), ['UserID' => 'UserIDRef']);
    }

    public function getAction() {
        return $this->hasOne(\app\modules\workflow\models\Action::className(), ['ActionID' => 'InvoiceActionIDRef']);
    }

    public function getType() {
        return $this->hasOne(\app\modules\invoice\models\InvoiceDocumentType::className(), ['InvoiceDocumentTypeID' => 'InvoiceDocumentTypeIDref']);
    }

    public function getInvoice() {
        return $this->hasOne(\app\modules\invoice\models\InvoiceEdit::className(), ['InvoiceID' => 'InvoiceIDref']);
    }

}
