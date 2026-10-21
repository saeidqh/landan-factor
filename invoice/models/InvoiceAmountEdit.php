<?php

namespace app\modules\invoice\models;

use Yii;
use app\modules\contract\models\Contract;
use yii\helpers\Html;

/**
 * This is the model class for table "tblInvoiceAmounts".
 *
 * The followings are the available columns in table 'tblInvoiceAmounts':
 * @property integer $ID
 * @property integer $InvoiceID
 * @property integer $AmountType
 * @property string $CostCode
 * @property integer $Currency1
 * @property double $AmountRequested1
 * @property double $AmountApproved1
 * @property integer $Currency2
 * @property double $AmountRequested2
 * @property double $AmountApproved2
 * @property double $Approved1Dollar
 * @property double $Approved2Dollar
 * @property double $Approved1Rial
 * @property double $Approved2Rial
 * @property double $Requested1Dollar
 * @property double $Requested2Dollar
 * @property double $Requested1Rial
 * @property double $Requested2Rial
 * @property double $Income1
 * @property double $Income2
 * @property double $Income1Dollar
 * @property double $Income2Dollar
 * @property double $Income1Rial
 * @property double $Income2Rial
 * @property double $Approved1DollarManually
 * @property double $Approved2DollarManually
 * @property double $Approved1RialManually
 * @property double $Approved2RialManually
 * @property double $Approved1EuroManually
 * @property double $Approved2EuroManually
 * @property integer $OwnerContractIDRef
 * @property integer $hasChange
 */
class InvoiceAmountEdit extends \yii\db\ActiveRecord {

    public $Requested1cumulative;
    public $Requested2cumulative;
    public $Approved1cumulative;
    public $Approved2cumulative;
    public $Income1cumulative;
    public $Income2cumulative;
    private $_uniqueNodeID;

    public function setUniqueNodeID($val) {
        if (empty($this->OwnerContractIDRef) && !empty($val)) {
            $this->_uniqueNodeID = $val . $this->invoice->contract->ContractID;
        } else {
            $this->_uniqueNodeID = $val . $this->OwnerContractIDRef;
        }
    }

    public function getUniqueNodeID() {
        return $this->_uniqueNodeID;
    }

    /**
     * @return string the associated database table name
     */
    public static function tableName() {
        if (Yii::$app->params['dmbs'] == 'mysql') {
            return 'invoice_table_invoice_amount';
        } else {
            return 'tblInvoiceAmounts';
        }
    }

    public static function primaryKey() {
        return ['ID'];
    }

    public static function getDb() {
        return \app\components\Utility::centralDb();
    }

    public function scenarios() {
        $scnario = parent::scenarios();
        $scnario['maincontract-create'] = ['AmountApproved1', 'CostCode','AmountRequested1','AmountRequested2','AmountApproved2','Requested2cumulative','Requested1cumulative','approved2cumulative','Approved1cumulative'];
        return $scnario;
    }
    
    /**
     * @return array validation rules for model attributes.
     */
    
    
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        
        return [
            [['InvoiceID', 'AmountType', 'Currency1', 'Currency2', 'OwnerContractIDRef', 'hasChange'], 'integer'],
            [['CostCode', 'AmountRequested1', 'AmountApproved1'], 'string'],
            [['Income1', 'Income2', 'Income1Dollar', 'Income2Dollar', 'Income1Rial', 'Income2Rial', 'approved1UsdF1', 'approved1UsdF2', 'approved1UsdF3', 'approved2UsdF1', 'approved2UsdF2', 'approved2UsdF3', 'approved1IrrF1', 'approved1IrrF2', 'approved1IrrF3', 'approved2IrrF1', 'approved2IrrF2', 'approved2IrrF3'], 'MoneyValidation', 'skipOnEmpty' => false,],
            [['InvoiceID', 'AmountType', 'Currency1', 'AmountApproved1', 'CostCode'], 'required'],
            [['CostCode'], 'costCodeValidation', 'skipOnEmpty' => false],
            [['Income1'], 'IncomeValidation', 'skipOnEmpty' => false],
            [['AmountApproved1'], 'AmountApproved1Validation', 'skipOnEmpty' => false],
            [['AmountApproved2'], 'AmountApproved2Validation', 'skipOnEmpty' => false],
//            [['CostCode'], 'unique', 'targetAttribute' => ['OwnerContractIDRef', 'CostCode', 'AmountType', 'InvoiceID'], 'message' => 'You Can\'t save on this cost code and this owner twise'],
            [['uniqueNodeID'], 'unique', 'targetAttribute' => ['OwnerContractIDRef', 'CostCode', 'AmountType', 'InvoiceID'], 'message' => 'You Can\'t save on this cost code and this owner twise'],
            [['uniqueNodeID'], 'required'],
            [['AmountRequested1', 'AmountApproved1', 'AmountRequested2', 'AmountApproved2'], 'MoneyValidation', 'skipOnEmpty' => false],
            [['CostCode', 'AmountRequested1', 'AmountApproved1'], 'trim'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return [
            'ID' => Yii::t('invoice' , 'ID'),
            'InvoiceID' => Yii::t('invoice' , 'Invoice'),
            'AmountType' => Yii::t('invoice' , 'Amount Type'),
            'amountType' => Yii::t('invoice' , 'Amount Type'),
            'CostCode' => Yii::t('invoice' , 'Cost Code'),
            'costcode' => Yii::t('invoice' , 'Cost Code'),
            'Currency1' => Yii::t('invoice' , 'Currency1'),
            'AmountRequested1' => Yii::t('invoice' , 'Requested1'),
            'AmountApproved1' => Yii::t('invoice' , 'Amount Approved1'),
            'Currency2' => Yii::t('invoice' , 'Currency2'),
            'AmountRequested2' => Yii::t('invoice' , 'Requested2'),
            'AmountApproved2' => Yii::t('invoice' , 'Amount Approved2'),
            'Approved1Dollar' => Yii::t('invoice' , 'Approved1 Dollar'),
            'Approved2Dollar' => Yii::t('invoice' , 'Approved2 Dollar'),
            'Approved1Rial' => Yii::t('invoice' , 'Approved1 Rial'),
            'Approved2Rial' => Yii::t('invoice' , 'Approved2 Rial'),
            'Requested1Dollar' => Yii::t('invoice' , 'Requested1 Dollar'),
            'Requested2Dollar' => Yii::t('invoice' , 'Requested2 Dollar'),
            'Requested1Rial' => Yii::t('invoice' , 'Requested1 Rial'),
            'Requested2Rial' => Yii::t('invoice' , 'Requested2 Rial'),
            'Income1' => Yii::t('invoice' , 'Income1'),
            'Income2' => Yii::t('invoice' , 'Income2'),
            'Income1Dollar' => Yii::t('invoice' , 'Income1 Dollar'),
            'Income2Dollar' => Yii::t('invoice' , 'Income2 Dollar'),
            'Income1Rial' => Yii::t('invoice' , 'Income1 Rial'),
            'Income2Rial' => Yii::t('invoice' , 'Income2 Rial'),
            'Approved1DollarManually' => Yii::t('invoice' , 'Approved1 Dollar Manually'),
            'Approved2DollarManually' => Yii::t('invoice' , 'Approved2 Dollar Manually'),
            'Approved1RialManually' => Yii::t('invoice' , 'Approved1 Rial Manually'),
            'Approved2RialManually' => Yii::t('invoice' , 'Approved2 Rial Manually'),
            'Approved1EuroManually' => Yii::t('invoice' , 'Approved1 Euro Manually'),
            'Approved2EuroManually' => Yii::t('invoice' , 'Approved2 Euro Manually'),
            'OwnerContractIDRef' => Yii::t('invoice' , 'Owner Contract Idref'),
            'hasChange' => Yii::t('invoice' , 'Has Change'),
            'currency' => Yii::t('invoice' , 'Currency'),
        ];
    }

    public function MoneyValidation($attribute) {
        if (!empty($this->$attribute)) {
            $value = str_replace(',', '', $this->$attribute);
            if (is_numeric($value)) {
                $this->$attribute = $value;
            } else {
                $this->addError($attribute, $attribute . ' must be number');
            }
        }
    }

    //-----------------validations------------------
    public function IncomeValidation($attribute, $params) {
        if (!empty($this->OwnerContractIDRef)) {
            if ($this->Income1 == '') {
                $this->addError($attribute, 'required feild');
            }
        }
    }

    public function AmountApproved1Validation($attribute, $params) {
        $invoiceAmountModel = static::find()->where(['ID' => $this->ID])->one();
        $invoiceEditModel = InvoiceEdit::find()
                ->where(['InvoiceID'=>$this->InvoiceID])
                ->one();

        $this->AmountApproved1= trim($this->AmountApproved1);
        $invoiceTable = \app\modules\invoice\models\InvoiceEdit::tableName();
        if (($invoiceEditModel->InvoiceType == 1 || $invoiceEditModel->InvoiceType == 3 || $invoiceEditModel->InvoiceType == 5) && $this->AmountType == 1 && $invoiceEditModel->contract->ContractType != 13) {
            $cumulativeApprovedTotal = \app\modules\invoice\models\InvoiceAmountEdit::find()->joinWith(['invoice'])->where(["$invoiceTable.ContractID" => $invoiceEditModel->ContractID, "$invoiceTable.AmountStatus" => [1, 5], "$invoiceTable.InvoiceType" => [1, 3, 5], 'AmountType' => 1])
                            ->andWhere("$invoiceTable.CutOffDateHijri<='$invoiceEditModel->CutOffDateHijri'")->sum('AmountApproved1');
            $approvedTotal = \app\modules\invoice\models\InvoiceAmountEdit::find()->joinWith(['invoice'])->where(["$invoiceTable.ContractID" => $invoiceEditModel->ContractID, "$invoiceTable.AmountStatus" => [1, 5], "$invoiceTable.InvoiceType" => [1, 3, 5], 'AmountType' => 1])
                            ->sum('AmountApproved1');
            $contractPrice = $invoiceEditModel->contract->lastRider->Price1;
            if ($this->isNewRecord) {
                if ($contractPrice < ($this->AmountApproved1 + $approvedTotal) || $contractPrice < ($this->AmountApproved1 + $cumulativeApprovedTotal)) {
                    $this->addError($attribute, 'Total Approved amount is more than contract price new');
                }
            } else {
                if ($contractPrice < ($this->AmountApproved1 + $approvedTotal - $invoiceAmountModel->AmountApproved1) || $contractPrice < ($this->AmountApproved1 + $cumulativeApprovedTotal - $invoiceAmountModel->AmountApproved1)) {
                    $this->addError($attribute, 'Total Approved amount is more than contract price old');
                }
            }
        }
    }

    public function AmountApproved2Validation($attribute, $params) {
        $invoiceTable = \app\modules\invoice\models\InvoiceEdit::tableName();
        $this->AmountApproved2= trim($this->AmountApproved2);
        if (!empty($this->AmountApproved2)) {
            $invoiceAmountModel = static::find()->where(['ID' => $this->ID])->one();
            $invoiceEditModel = InvoiceEdit::find()
                    ->where(['InvoiceID'=>$this->InvoiceID])
                    ->one();
            if (($invoiceEditModel->InvoiceType == 1 || $invoiceEditModel->InvoiceType == 3 || $invoiceEditModel->InvoiceType == 5) && $this->AmountType == 1 && $invoiceEditModel->contract->ContractType != 13) {
                $approvedTotal = \app\modules\invoice\models\InvoiceAmountEdit::find()->joinWith(['invoice'])->where(["$invoiceTable.ContractID" => $invoiceEditModel->ContractID, "$invoiceTable.AmountStatus" => [1, 5], "$invoiceTable.InvoiceType" => [1, 3, 5], 'AmountType' => 1])
                                ->andWhere("$invoiceTable.CutOffDateHijri<='$invoiceEditModel->CutOffDateHijri'")->sum('AmountApproved2');
                if ($this->isNewRecord) {
                    if ($invoiceEditModel->contract->lastRider->Price2 < ($this->AmountApproved2 + $approvedTotal)) {
                        $this->addError($attribute, 'Total Approved amount is more than contract price');
                    }
                } else {
                    if ($invoiceEditModel->contract->lastRider->Price2 < ($this->AmountApproved2 + $approvedTotal - $invoiceAmountModel->AmountApproved2)) {
                        $this->addError($attribute, 'Total Approved amount is more than contract price');
                    }
                }
            }
        }
    }
    
    public function costCodeValidation($attribute) {
        if($this->scenario != 'maincontract-create'){
            $invoiceAmountTable = InvoiceAmountEdit::tableName();
            $contractNodeBudgetModel = \app\modules\contract\models\ContractNodeBudget::find()->where(['ExecuterContractIDRef' => $this->invoice->ContractID, 'isActive' => 1])->one();
            if (!$contractNodeBudgetModel) {
                $this->addError('uniqueNodeID', 'Invalid CostCode');
            }
            if ($this->invoice->InvoiceType > 5) {
                if (!empty($this->OwnerContractIDRef)) {
                    $this->addError('uniqueNodeID', "Invalid costcode for this invoice type");
                }
            }
            if ($this->isNewRecord) {
                if ($this->OwnerContractIDRef > 0) {
                    $sql = "select count(*) from $invoiceAmountTable where InvoiceID=" . $this->InvoiceID . " and AmountType=" . $this->AmountType . " and CostCode='" . $this->CostCode . "' and OwnerContractIDRef=" . $this->OwnerContractIDRef;
                } else {
                    $sql = "select count(*) from $invoiceAmountTable where InvoiceID=" . $this->InvoiceID . " and AmountType=" . $this->AmountType . " and CostCode='" . $this->CostCode . "' and OwnerContractIDRef is null";
                }
            } else {
                if ($this->OwnerContractIDRef > 0) {
                    $sql = "select count(*) from $invoiceAmountTable where ID<>" . $this->ID . " and InvoiceID=" . $this->InvoiceID . " and AmountType=" . $this->AmountType . " and CostCode='" . $this->CostCode . "' and OwnerContractIDRef=" . $this->OwnerContractIDRef;
                } else {
                    $sql = "select count(*) from $invoiceAmountTable where ID<>" . $this->ID . " and InvoiceID=" . $this->InvoiceID . " and AmountType=" . $this->AmountType . " and CostCode='" . $this->CostCode . "' and OwnerContractIDRef is null";
                }
            }
            if (Yii::$app->dbTehran->createCommand($sql)->queryScalar() > 0) {
                $this->addError('uniqueNodeID', 'Duplicate CostCode');
            }
        }else{
            $arrInvoiceAmount = self::find()
                    ->where(['InvoiceID'=>$this->InvoiceID])
                    ->andWhere(['CostCode'=>$this->CostCode])
                    ->andWhere($this->isNewRecord?'':'ID <> '.$this->ID)
                    ->asArray()->all();
            if(count($arrInvoiceAmount)>0){
                $this->addError('CostCode', 'Duplicate CostCode!');
            }
        }
    }

    public function costCodeValidation2($attribute) {
        if($this->scenario != 'maincontract-create'){
            $invoiceAmountTable = InvoiceAmountEdit::tableName();
            $contractNodeBudgetModel = \app\modules\contract\models\ContractNodeBudget::find()
                    ->where(['ExecuterContractIDRef' => $this->invoice->ContractID, 'isActive' => 1])->one();
            if (!$contractNodeBudgetModel) {
                $this->addError('uniqueNodeID', 'Invalid CostCode');
            }
            if ($this->invoice->InvoiceType > 5) {
                if (!empty($this->OwnerContractIDRef)) {
                    $this->addError('uniqueNodeID', "Invalid costcode for this invoice type");
                }
            }
            if ($this->isNewRecord) {
                if ($this->OwnerContractIDRef > 0) {
                    $sql = "select count(*) from $invoiceAmountTable where InvoiceID=:InvoiceID  and AmountType=:AmountType and CostCode=:CostCode and OwnerContractIDRef=:OwnerContractIDRef";
                } else {
                    $sql = "select count(*) from $invoiceAmountTable where InvoiceID=:InvoiceID  and AmountType=:AmountType and CostCode=:CostCode and OwnerContractIDRef is null";
                }
            } else {
                if ($this->OwnerContractIDRef > 0) {
                    $sql = "select count(*) from $invoiceAmountTable where ID<>:ID and InvoiceID=:InvoiceID and AmountType=:AmountType and CostCode=:CostCode and OwnerContractIDRef=:OwnerContractIDRef";
                } else {
                    $sql = "select count(*) from $invoiceAmountTable where ID<>:ID and InvoiceID=:InvoiceID and AmountType=:AmountType and CostCode=:CostCode and OwnerContractIDRef is null";
                }
            }
            if (Yii::$app->dbTehran->createCommand($sql)
                    ->bindValue(':ID',$this->ID)
                    ->bindValue(':InvoiceID',$this->InvoiceID)
                    ->bindValue(':AmountType',$this->AmountType)
                    ->bindValue(':CostCode',$this->CostCode)
                    ->bindValue(':OwnerContractIDRef',$this->OwnerContractIDRef)
                    ->queryScalar() > 0) {
                $this->addError('uniqueNodeID', 'Duplicate CostCode');
            }
        }else{
            $arrInvoiceAmount = self::find()
                    ->where(['InvoiceID'=>$this->InvoiceID])
                    ->andWhere(['CostCode'=>$this->CostCode])
                    ->andWhere($this->isNewRecord?'':'ID <> '.$this->ID)
                    ->asArray()->all();
            if(count($arrInvoiceAmount)>0){
                $this->addError('CostCode', 'Duplicate CostCode!');
            }
        }
    }


    /**
     * @inheritdoc
     * @return InvoiceAmountsQuery the active query used by this AR class.
     */
    public static function find() {
        return new InvoiceAmountQuery(get_called_class());
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

    public function getInvoice() {
        return $this->hasOne(\app\modules\invoice\models\InvoiceEdit::className(), ['InvoiceID' => 'InvoiceID']);
    }

    public function getOwner() {
        return $this->hasOne(\app\modules\contract\models\Contract::className(), ['ContractID' => 'OwnerContractIDRef']);
    }

    public function getType() {
        return $this->hasOne(\app\modules\invoice\models\InvoiceAmountTypes::className(), ['ID' => 'AmountType']);
    }

}
