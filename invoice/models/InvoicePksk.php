<?php

namespace app\modules\invoice\models;

use Yii;

/**
 * This is the model class for table "dbo.tblInvoicePKSKInfo".
 *
 * @property integer $ID
 * @property integer $InvoiceIDRef
 * @property string $CI_No
 * @property string $PL_No
 * @property string $MSR_No
 * @property string $MSR_Description
 * @property double $CI_Amount1
 * @property integer $CI_Currency1IDRef
 * @property double $CI_Amount2
 * @property integer $CI_Currency2IDRef
 */
class InvoicePksk extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        if (Yii::$app->params['dmbs'] == 'mysql') {
            return 'invoice_table_invoice_pksk_info';
        } else {
            return 'tblInvoicePKSKInfo';
        }
    }

    public static function primaryKey() {
        return ['ID'];
    }

    public static function getDb() {
        return \app\components\Utility::centralDb();
    }

    public function rules() {
        return [
            [['InvoiceIDRef', 'CI_Amount1', 'CI_No', 'PL_No'/*, 'MSR_No'*/], 'required', 'on' => 'normal'],
            [['InvoiceIDRef', 'CI_Amount1', 'CI_No',], 'required', 'on' => 'aditional'],
            [['InvoiceIDRef', 'CI_Currency1IDRef', 'CI_Currency2IDRef'], 'integer'],
            [['CI_No', 'PL_No', 'MSR_No', 'MSR_Description'], 'string'],
//            [['CI_Amount1', 'CI_Amount2'], 'number'],
            ['MSR_No', 'validateMSR_No', 'on' => 'normal'],
        ];
    }

    public function scenarios() {
        $scnario = parent::scenarios();
        $scnario['normal'] = ['ID', 'InvoiceIDRef', 'CI_Amount1', 'CI_Amount2', 'CI_No', 'PL_No', 'MSR_No', 'CI_Currency1IDRef', 'CI_Currency2IDRef', 'MSR_Description'];
        $scnario['aditional'] = ['ID', 'InvoiceIDRef', 'CI_Amount1', 'CI_Amount2', 'CI_No', 'PL_No', 'CI_Currency1IDRef', 'CI_Currency2IDRef', 'MSR_Description'];
        return $scnario;
    }

    public function validateMSR_No() {
        if (isset($this->ID)) {
            $id = $this->ID;
        } else {
            $id = 0;
        }

        $signer = $this->invoice->contract->SignerPersonIDRef;

        $invoicePkskModel = $this->find()
                        ->leftJoin(InvoiceEdit::tableName(), InvoiceEdit::tableName() . '.InvoiceID=' . InvoicePksk::tableName() . '.InvoiceIDRef')
                        ->leftJoin(\app\modules\contract\models\Contract::tableName(), InvoiceEdit::tableName() . '.ContractID=' . \app\modules\contract\models\Contract::tableName() . '.ContractID')
                        ->where(['MSR_No' => $this->MSR_No, \app\modules\contract\models\Contract::tableName() . '.SignerPersonIDRef' => $signer])
                         ->andWhere(['<>' ,'ID', $id])
                        ->one();
        if ($invoicePkskModel) {
            $this->addError('MSR_No', 'Duplicate MSR No in PO:' . $invoicePkskModel->invoice->contract->ContractNo . ' and Invoice No. ' . $invoicePkskModel->invoice->Title);
        }
    }

    public function attributeLabels() {
        return [
            'ID' => 'ID',
            'InvoiceIDRef' => 'Invoice Idref',
            'CI_No' => 'Ci  No',
            'PL_No' => 'Pl  No',
            'MSR_No' => 'Msr  No',
            'MSR_Description' => 'Msr  Description',
            'CI_Amount1' => 'Ci  Amount1',
            'CI_Currency1IDRef' => 'Ci  Currency1 Idref',
            'CI_Amount2' => 'Ci  Amount2',
            'CI_Currency2IDRef' => 'Ci  Currency2 Idref',
        ];
    }

    /**
     * @inheritdoc
     * @return InvoicePkskQuery the active query used by this AR class.
     */
    public static function find() {
        return new InvoicePkskQuery(get_called_class());
    }

    public function getInvoice() {
        return $this->hasOne(\app\modules\invoice\models\InvoiceEdit::className(), ['InvoiceID' => 'InvoiceIDRef']);
    }

    public function getCurrency1() {
        return $this->hasOne(\app\modules\currency\models\Currency::className(), ['CurrencyID' => 'CI_Currency1IDRef']);
    }

    public function getCurrency2() {
        return $this->hasOne(\app\modules\currency\models\Currency::className(), ['CurrencyID' => 'CI_Currency2IDRef']);
    }

}
