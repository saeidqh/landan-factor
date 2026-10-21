<?php

namespace app\modules\invoice\models;

use Yii;
use app\components\Utility;
use yii\db\Query;
use yii\data\ArrayDataProvider;
use app\components\jDateTime;
use app\modules\currency\models\Currency;

/**
 * This is the model class for table "tblInvoices".
 *
 * @property integer $InvoiceID
 * @property integer $ContractID
 * @property integer $InvoiceType
 * @property string $Title
 * @property integer $RevisionNumber
 * @property integer $ParrentID
 * @property string $CutOffDateHijri
 * @property string $CutOffDate
 * @property string $Description
 * @property integer $Currency1IDRef
 * @property string $Currency1Title
 * @property integer $Currency2IDRef
 * @property string $Currency2Title
 * @property integer $AmountStatus
 * @property string $financialDocNumber
 * @property string $financialDocDateHijri
 * @property double $approved1UsdF2
 * @property double $approved2UsdF2
 * @property double $approved1UsdF3
 * @property double $approved2UsdF3
 * @property double $approved1IrrF2
 * @property double $approved2IrrF2
 * @property double $approved1IrrF3
 * @property double $approved2IrrF3
 * @property integer $UserIDRef1
 * @property string $InsertDateHijri
 * @property integer $UserIDRef2
 * @property string $LastUpdateHijri
 * @property integer $hasChange
 */
class InvoiceEdit extends \yii\db\ActiveRecord {

    public $AMOUNT_STATUS_ITEMS = [
        'Approved Or Checking' => 'Approved Or Checking',
        'Approved' => 'Approved',
        'Checking' => 'Checking',
        'Held Or Rejected' => 'Held Or Rejected',
    ];

    /**
     * @return string the associated database table name
     */
    public static function tableName() {
        if (Yii::$app->params['dmbs'] == 'mysql') {
            return 'invoice_table_invoice';
        } else {
            return 'tblInvoices';
        }
    }

    public static function primaryKey() {
        return ['InvoiceID'];
    }

    public static function getDb() {
        return \app\components\Utility::centralDb();
    }

    public $total_dropdown_items = [
        'Approved Or Checking' => 'Approved Or Checking',
        'Approved' => 'Approved',
        'Checking' => 'Checking',
        'Held Or Rejected' => 'Held Or Rejected',
    ];

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return [
            [['InvoiceID', 'InvoiceType', 'Title', 'CutOffDateHijri', 'Currency1IDRef'], 'required'],
            [['InvoiceID', 'ContractID', 'InvoiceType', 'ParrentID', 'Currency1IDRef', 'Currency2IDRef', 'AmountStatus', 'UserIDRef1', 'UserIDRef2', 'hasChange'], 'number', 'integerOnly' => true],
            [['approved1UsdF2', 'approved2UsdF2', 'approved1UsdF3', 'approved2UsdF3', 'approved1IrrF2', 'approved2IrrF2', 'approved1IrrF3', 'approved2IrrF3'], 'number'],
            [['Title', 'Description'], 'string', 'max' => 350],
            [['CutOffDateHijri', 'fromCutOffDateHijri'], 'string', 'max' => 20],
            [['InsertDateHijri', 'LastUpdateHijri', 'financialDocDateHijri'], 'string', 'max' => 50],
            [['Currency1Title', 'Currency2Title'], 'string', 'max' => 10],
            [['CutOffDate', 'fromCutOffDate'], 'safe'],
            ['InvoiceType', 'validateInvoiceType'],
            ['Description', 'validateDescription', 'skipOnEmpty' => false],
            [['Currency2IDRef'], 'compare', 'compareAttribute' => 'Currency1IDRef', 'operator' => '!='],
            ['Currency1IDRef', 'validateCurrency1'],
            ['Currency2IDRef', 'validateCurrency2'],
            ['ParrentID', 'validateParrentID'],
            ['RevisionNumber', 'number',
                'integerOnly' => true,
                'min' => 0,
                'max' => 5,
            ],
            [["Currency1IDRef", "financialDocNumber"], 'integer'],
            ["CutOffDateHijri", "validateCutOffDateHijri"],
            ["CutOffDateHijri", "validateCutOffDateHijriForBudget"],
            ["fromCutOffDateHijri", "validateFromCutOffDateHijri"],
            [['InvoiceID', 'ContractID', 'InvoiceType', 'Title', 'RevisionNumber', 'ParrentID', 'CutOffDateHijri', 'CutOffDate', 'fromCutOffDateHijri', 'fromCutOffDate', 'Description', 'Currency1IDRef', 'Currency1Title', 'Currency2IDRef', 'Currency2Title', 'AmountStatus', 'UserIDRef1', 'InsertDateHijri', 'UserIDRef2', 'LastUpdateHijri'], 'safe', 'on' => 'search'],
            [['Title'], 'TitleValidation', 'skipOnEmpty' => false],
        ];
    }

    public function validateDescription($attribute, $params) {
        if ($this->contract->ContractType == 18) {  // 18 :: call out Type
            if ($this->Description == NULL) {
                $this->addError($attribute, "This Contract Type has 'Call Out' and you should fill Discription");
            }
        }
    }

    public function TitleValidation($attribute) {
        $invoiceModel = InvoiceEdit::find();
        if ($this->isNewRecord) {
            $exist = $invoiceModel->where([
                        'ContractID' => $this->ContractID,
                        'Title' => $this->$attribute,
                    ])
                    ->andWhere(['!=', 'AmountStatus', '0'])
                    ->andWhere(['InvoiceType' => $this->InvoiceType])
                    ->exists();
        } else {
            $exist = $invoiceModel->where([
                        'ContractID' => $this->ContractID,
                        'Title' => $this->$attribute,
                    ])
                    ->andWhere(['!=', 'invoiceID', $this->InvoiceID])
                    ->andWhere(['!=', 'AmountStatus', '0'])
                    ->andWhere(['InvoiceType' => $this->InvoiceType])
                    ->exists();
        }
        if ($exist) {
            $this->addError($attribute, "Duplicate Invoice Number!");
        }
    }

    public function validateParrentID($attribute, $params) {
        if ($this->$attribute > 0) {
            $invoiceModel = static::findOne($this->$attribute);
            if (!$invoiceModel) {
                $this->addError($attribute, 'Invalid parent invoice');
            }
        }
    }

    public function validateCurrency1($attribute, $params) {
        $currencyModel = new Currency();
        $currencyList = \yii\helpers\ArrayHelper::map(\app\modules\currency\models\Currency::find()
                                ->asArray()
                                ->all(), 'CurrencyID', 'CurrencyTitle');

        if (!in_array($this->$attribute, array_keys($currencyList)) && $this->$attribute > 0) {
            $this->addError($attribute, 'Invalid currency');
        }

        if ($this->InvoiceType <= 5) {
            if ($this->contract->lastRider->Price1Unit != $this->$attribute && $this->$attribute > 0) {
                $this->addError($attribute, 'Invalid currency for this type of invoice');
            }
        }
    }

    public function validateCurrency2($attribute, $params) {
        $currencyModel = new Currency();
        $currencyList = \yii\helpers\ArrayHelper::map(\app\modules\currency\models\Currency::find()
                                ->asArray()
                                ->all(), 'CurrencyID', 'CurrencyTitle');

        if (!in_array($this->$attribute, array_keys($currencyList)) && $this->$attribute > 0) {
            $this->addError($attribute, 'Invalid currency');
        }


        if ($this->$attribute == $this->Currency1IDRef) {
            $this->addError($attribute, 'Currency rate 1 and 2 are same');
        }

        if ($this->InvoiceType <= 5) {
            if ($this->contract->lastRider->Price2Unit != $this->$attribute && $this->$attribute > 0) {
                $this->addError($attribute, 'Invalid currency for this type of invoice');
            }
        }
    }

    public static function hasCertainInvoice($cid){
        $hasCertainInvoice = \app\modules\invoice\models\InvoiceEdit::find()
            ->where(['ContractID' => $cid])
            ->andWhere(['InvoiceType' => 3])
            ->exists();
        return $hasCertainInvoice;
    }

    public function validateInvoiceType($attribute, $params) {
        $obsProcessActionTable = \app\modules\OBS\models\ObsProcessAction::tableName();
        $invoiceTypes = $this->getInvoiceType($this->ContractID);
        if (!isset($invoiceTypes)) {
            $this->addError($attribute, 'Invalid invoice type for this contract type and contract status!');
        } elseif (!in_array($this->$attribute, array_keys($invoiceTypes))) {
            $this->addError($attribute, 'Invalid invoice type for this contract type and contract status!');
        }

//old Code
//        if ($this->contract->hasAdjustment == 0 && ($this->$attribute == 2 || $this->$attribute == 4)) {
//            $this->addError($attribute, 'This contract has not adjustment and you can not select adjustment invoice type');
//        }


        if ($this->$attribute == 2 || $this->$attribute == 4) {
            $contractRevisionModel = \app\modules\contract\models\ContractRevision::find()
                    ->andWhere(["ContractID" => $this->contract->ContractID])
                    ->andWhere(["hasAdjustment" => 1])
                    ->orderBy(["RevisionDate" => SORT_DESC])
                    ->one();

            if (!isset($contractRevisionModel)) {
                $this->addError($attribute, 'This contract has not adjustment and you can not select adjustment invoice type');
                return false;
            }

            if ($this->CutOffDate < substr($contractRevisionModel->AdjustmentStartDate, 0, 10) ||
                    $this->CutOffDate > substr($contractRevisionModel->AdjustmentFinishDate, 0, 10)) {
                $this->addError($attribute, 'This invocie Cutoffdata should be between adjustment date!!');
//                var_dump($this->CutOffDate);
//                var_dump(substr($contractRevisionModel->AdjustmentStartDate, 0, 10));
//                var_dump(substr($contractRevisionModel->AdjustmentFinishDate, 0, 10));
//                die();
                return false;
            }
        }

        $contractModel = \app\modules\contract\models\Contract::find()
                ->maincontract('update')
                ->andWhere(['ContractID' => $this->ContractID]);

        if ($this->isNewRecord) {
            if (!$contractModel->exists()) {
                $processType = \app\modules\workflow\models\ContractInvoiceTypeProcess::find()
                        ->where(['ContractIDRef' => $this->ContractID, 'InvoiceTypeIDRef' => $this->$attribute])
                        ->andWhere(\app\modules\workflow\models\ContractInvoiceTypeProcess::tableName() . '.isDeleted is null '
                                . 'or ' . \app\modules\workflow\models\ContractInvoiceTypeProcess::tableName() . '.isDeleted != 1')
                        ->one($db = Utility::db());

                if (empty($processType)) {
                    $this->addError($attribute, 'There is no Invoice workflow for this inoice type of contract');
                } else {
                    $pid = $processType->ProcessIDRef;
                }
            } else {
                $pid = 63;
            }

            if (isset($pid)) {
                $firstActionId = \app\modules\workflow\models\Action::find()
                                ->where(['ProcessIDRef' => $pid, 'isStartAction' => 1])
                                ->one()->ActionID;
                $obsProcessAction = \app\modules\OBS\models\ObsProcessAction::find()
                        ->where(['OBSCategory3IDRef' => Yii::$app->session->get('ObsPosition'), 'ActionIDRef' => $firstActionId])
                        ->andWhere("COALESCE($obsProcessActionTable.isDeleted,0)<>1")
                        ->one();
                if (!$obsProcessAction) {
                    $this->addError($attribute, 'Limited Access for this type in ActionId: ' . $firstActionId);
                }
            }
        }
    }

    public function validateCutOffDateHijriForBudget($attribute, $params) {
        if (!(jDateTime::mCheckdate(trim($this->$attribute)) > 0)) {
            $this->addError($attribute, 'Invalid Hijri Date');
            return FALSE;
        }
        $budgetTable = \app\modules\budget\models\Budget::tableName();
        $annualBudgetTable = \app\modules\budget\models\AnnualBudget::tableName();
        $year = substr($this->CutOffDateHijri, 0, 4);

        if (!\app\modules\contract\models\Contract::find()->maincontract('ContractView')
                        ->where(["ContractID" => $this->ContractID])
                        ->exists()) {
            $annualBudgetModelExists = \app\modules\budget\models\Budget::find()
                    ->innerJoin($annualBudgetTable, "$annualBudgetTable.AnnualBudgetID=$budgetTable.AnnualBudgetIDRef")
                    ->andWhere("ContractIDRef=$this->ContractID and $annualBudgetTable.`Year`='$year'")
                    ->exists();

            if (!$annualBudgetModelExists) {
                $annualBudgetModel = \app\modules\budget\models\AnnualBudget::find()
                        ->andWhere(["OBSCategory3CompanyIDRef" => $this->contract->SignerPersonIDRef])
                        ->orderBy(["AnnualBudgetID" => SORT_DESC])
                        ->one();

                if (!isset($annualBudgetModel)) {
                    if ($annualBudgetModel->invoiceProcessType == 1) {
                        $this->addError($attribute, "For this Cut Off Date Years don't have any annual Budget!!!");
                        return FALSE;
                    } else {
                        Yii::$app->session->setFlash('warning', "this Contract don't have any annualBudget!!!");
                        return false;
                    }
                } else {
                    Yii::$app->session->setFlash('warning', "this Contract don't have any annualBudget!!!");
                    return false;
                }
            }
        }
    }

    public function validateCutOffDateHijri($attribute, $params) {

        if (!(jDateTime::mCheckdate($this->$attribute) > 0))
            $this->addError($attribute, 'Invalid Hijri Date');


        if ($this->contract->ContractDateHijri > $this->$attribute)
            $this->addError($attribute, 'CutOffDate if before start date');

        $contractFinishDateHijri = $this->contract->lastRider->FinishDateHijri;
        if ($this->CutOffDateHijri > $contractFinishDateHijri) {
            $managerMessageModel = \app\modules\account\models\ManagerMessage::findOne(1);
            if ($managerMessageModel->type == 'error') {
                $this->addError($attribute, $managerMessageModel->Message);
            } else {
                Yii::$app->session->setFlash('warning', $managerMessageModel->Message);
            }
        }

        $strTime = explode('/', $this->$attribute);
        if (is_array($strTime) && count($strTime) == 3) {
            if (strlen($strTime[0]) > 4 || strlen($strTime[1]) > 2 || strlen($strTime[2]) > 2) {
                $this->addError($attribute, 'invalid format');
            } else {
                $time = jDateTime::mktime(0, 0, 0, $strTime[1], $strTime[2], $strTime[0], true);
                if ($time > time()) {
                    $this->addError($attribute, 'CutOffDate is after today');
                }
            }
        }

        //اگر قرارداد کاست پلاس یا پی او بود کاری انجام ندهد
        if (($this->contract->ContractType != 13) && ($this->contract->ContractCategory != 13)) {

            if ($this->isNewRecord) {
                $invoiceModel = InvoiceEdit::find()->select(['CutOffDateHijri'])
                        ->where(['ContractID' => $this->ContractID])
                        ->andWhere(['<', 'CutOffDateHijri', $this->$attribute])
                        ->andWhere(['!=', 'AmountStatus', 0])
                        ->limit('1')
                        ->orderBy('CutOffDateHijri desc')
                        ->one();

                $lastCutOffDateHijriBefore = '';
                if (!isset($invoiceModel->CutOffDateHijri)) {
                    $lastCutOffDateHijriBefore = $this->contract->ContractDateHijri;
                } else {
                    $lastCutOffDateHijriBefore = $invoiceModel->CutOffDateHijri;
                }

                if ($lastCutOffDateHijriBefore != '') {
                    $countYear = \app\modules\workflow\models\Day::find()
                            ->andWhere(['and', ['>', 'pDate', $lastCutOffDateHijriBefore], ['<=', 'pDate', $this->$attribute]])
                            ->addSelect(['year' => "left(pDate,4)"])
                            ->distinct()
                            ->count();
                    if ($countYear > 1) {
                        $this->addError($attribute, 'Invoice period is more than one year');
                        return;
                    }
                }
            } else {

                $invoiceModel = InvoiceEdit::find()->select('CutOffDateHijri')
                        ->where(['ContractID' => $this->ContractID])
                        ->andWhere(['<', 'CutOffDateHijri', $this->$attribute])
                        ->andWhere("InvoiceID<>" . $this->InvoiceID)
                        ->andWhere(['!=', 'AmountStatus', 0])
                        ->limit('1')
                        ->orderBy('CutOffDateHijri desc')
                        ->one();

                $lastCutOffDateHijriBefore = '';
                if (!isset($invoiceModel->CutOffDateHijri)) {
                    $lastCutOffDateHijriBefore = $this->contract->ContractDateHijri;
                } else {
                    $lastCutOffDateHijriBefore = $invoiceModel->CutOffDateHijri;
                }

                if ($lastCutOffDateHijriBefore != '') {
                    $countYear = \app\modules\workflow\models\Day::find()
                            ->andWhere(['and', ['>', 'pDate', $lastCutOffDateHijriBefore], ['<=', 'pDate', $this->$attribute]])
                            ->addSelect(['year' => "left(pDate,4)"])
                            ->distinct()
                            ->count();
                    if ($countYear > 1) {
                        $this->addError($attribute, 'Invoice period is more than one year');
                        return;
                    }
                }
            }
        }
    }

    public function validateFromCutOffDateHijri($attribute, $params) {

        if (!(jDateTime::mCheckdate($this->$attribute) > 0))
            $this->addError($attribute, 'Invalid Hijri Date');


        if ($this->contract->ContractDateHijri > $this->$attribute)
            $this->addError($attribute, 'fromCutOffDate if before start date');

        if ($this->fromCutOffDateHijri > $this->CutOffDateHijri) {
            $this->addError($attribute, 'fromCutOffDate if before CutOffDate');
        }
    }

    public function getInvoiceType($contractId) {
        $contractModel = \app\components\ActiveModel::find('\app\modules\contract\models\Contract')
                ->where(['ContractID' => $contractId])
                ->one();
        $invoiceType = \app\components\ActiveModel::find('\app\modules\invoice\models\InvoiceTypes');
        if ($contractModel->ContractStatusIDRef != 12 and $contractModel->ContractStatusIDRef != 13) {
            if ($contractModel->ContractType != 15) {
                $invoiceType->andWhere('InvoiceTypeID<=5');
            }
        } else {
            if ($contractModel->ContractType == 15) {
                $invoiceType->andWhere('InvoiceTypeID>5');
            } else {
                return null;
            }
        }
        $invoiceTypeArray = \yii\helpers\ArrayHelper::map($invoiceType->asArray()->all(), 'InvoiceTypeID', 'InvoiceType');

        return $invoiceTypeArray;
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return [
            'InvoiceID' => Yii::t('invoice', 'Invoice No'),
            'ContractID' => Yii::t('invoice', 'Contract'),
            'InvoiceType' => Yii::t('invoice', 'Invoice Type'),
            'invoiceTypeTitle' => Yii::t('invoice', 'Invoice Type'),
            'Title' => Yii::t('invoice', 'Invoice Number'),
            'RevisionNumber' => Yii::t('invoice', 'Revision Number'),
            'ParrentID' => Yii::t('invoice', 'Parrent'),
            'CutOffDateHijri' => Yii::t('invoice', 'Cut Off Date'),
            'CutOffDate' => Yii::t('invoice', 'Cut Off Date'),
            'Description' => Yii::t('invoice', 'Description'),
            'Currency1IDRef' => Yii::t('invoice', 'Currency1'),
            'Currency1Title' => Yii::t('invoice', 'Currency1 Title'),
            'Currency2IDRef' => Yii::t('invoice', 'Currency2'),
            'Currency2Title' => Yii::t('invoice', 'Currency2 Title'),
            'AmountStatus' => Yii::t('invoice', 'Amount Status'),
            'UserIDRef1' => Yii::t('invoice', 'User Idref1'),
            'InsertDateHijri' => Yii::t('invoice', 'Insert Date Hijri'),
            'UserIDRef2' => Yii::t('invoice', 'User Idref2'),
            'LastUpdateHijri' => Yii::t('invoice', 'Last Update Hijri'),
            'hasChange' => Yii::t('invoice', 'Has Change'),
            'LastActionMessage' => Yii::t('invoice', 'Last Action'),
            'lastActionTitle' => Yii::t('invoice', 'Last Action Title'),
            'lastActionDateHijri' => Yii::t('invoice', 'Last Action Date'),
            'approved' => Yii::t('invoice', 'Approved'),
            'approved1UsdF2' => Yii::t('invoice', 'Approved1 USD(F2)'),
            'approved1IrrF2' => Yii::t('invoice', 'Approved1 IRR(F2)'),
            'approved1UsdF3' => Yii::t('invoice', 'Approved1 USD(F3)'),
            'approved1IrrF3' => Yii::t('invoice', 'Approved1 IRR(F3)'),
            'financialDocNumber' => Yii::t('invoice', 'Financial Doc Number'),
            'financialDocDateHijri' => Yii::t('invoice', 'Financial Doc Date Hijri'),
            'NodeFullCode' => Yii::t('invoice', 'Cost Code'),
            'amountType' => Yii::t('invoice', 'Amount Type'),
            'fromCutOffDate' => Yii::t('invoice', 'From Date'),
            'fromCutOffDateHijri' => Yii::t('invoice', 'From Date'),
        ];
    }

    public static function documentRequireModel($invoiceid, $actionid) {
        $documentTypeTable = InvoiceDocumentType::tableName();
        $DocumentRequireModelTable = InvoiceDocumentRequireModel::tableName();
        $requiredDocumentQuery = (new Query())
                ->select([
                    'ID',
                    'DocumentRequireModel',
                    'InvoiceDocumentTypeID',
                    'InvoiceDocumentTypeTitle',
                    'RequireType'
                ])
                ->from(InvoiceDocumentRequireModel::tableName())
                ->innerJoin($documentTypeTable . ' documentType', "documentType.InvoiceDocumentTypeID=$DocumentRequireModelTable.InvoiceDocumentTypeIDRef")
                ->addSelect([
                    'NumberOfDocuments' => "(select COUNT(*) from " . InvoiceDocument::tableName() . " where invoiceidref=$invoiceid and InvoiceDocumentTypeIDRef=documentType.InvoiceDocumentTypeID and InvoiceActionIDRef >0)-(SELECT COUNT(*) FROM " . \app\modules\workflow\models\ActionEntity::tableName() . " inner join " . \app\modules\workflow\models\ProcessEntity::tableName() . " on ProcessEntityIDRef=ProcessEntityID where InvoiceIDRef=$invoiceid and ActionIDRef=$actionid)"
                ])
                ->where(['IN', 'DocumentRequireModel', \app\modules\workflow\models\Action::find()
            ->select('DocumentRequireModel')
            ->where(['ActionID' => $actionid])])
        ;

        return new ArrayDataProvider([
            'allModels' => $requiredDocumentQuery->all(),
            'totalCount' => $requiredDocumentQuery->count(),
            'pagination' => [
                'pageSize' => 50,
            ],
        ]);
    }

    /**
     * @inheritdoc
     * @return InvoiceQuery the active query used by this AR class.
     */
    public static function find() {
        return new InvoiceQuery(get_called_class());
    }

    public function cumulativeRequested1($amountStatus) {
        $invoiceTable = \app\modules\invoice\models\InvoiceEdit::tableName();
        $invoiceAmountTable = \app\modules\invoice\models\InvoiceAmountEdit::tableName();
        $amountStatusArray = [];
        switch ($amountStatus) {
            case 0:
                $amountStatusArray = [0];
                break;
            case 1:
                $amountStatusArray = [1];
                break;
            case 5:
                $amountStatusArray = [5];
                break;
            case 6:
                $amountStatusArray = [1, 5];
                break;
        }

        switch ($this->InvoiceType) {
            case 1:
            case 3:
            case 5:
                $res = \app\modules\invoice\models\InvoiceAmountEdit::find()->joinWith(['invoice'])->where([$invoiceTable . '.ContractID' => $this->ContractID, $invoiceTable . '.AmountStatus' => $amountStatusArray, $invoiceTable . '.InvoiceType' => [1, 3, 5], 'AmountType' => 1])->andWhere("$invoiceTable.CutOffDateHijri <='" . $this->CutOffDateHijri . '\'')->sum('AmountRequested1');
                break;
            case 2:
            case 4:
                $res = \app\modules\invoice\models\InvoiceAmountEdit::find()->joinWith(['invoice'])->where(["$invoiceTable.ContractID" => $this->ContractID, "$invoiceTable.AmountStatus" => $amountStatusArray, "$invoiceTable.InvoiceType" => [2, 4], 'AmountType' => 1])->andWhere("$invoiceTable.CutOffDateHijri <='" . $this->CutOffDateHijri . "'")->sum('AmountRequested1');
                break;
            case 17:
            case 18:
            case 19:
            case 20:
            case 21:
            case 22:
                $res1 = \app\modules\invoice\models\InvoiceAmountEdit::find()->joinWith(['invoice'])->where(["$invoiceTable.ContractID" => $this->ContractID, $invoiceTable . '.AmountStatus' => $amountStatusArray, $invoiceTable . '.InvoiceType' => $this->InvoiceType, 'AmountType' => 1, $invoiceAmountTable . '.Currency1' => $this->Currency1IDRef])->andWhere("$invoiceTable.CutOffDateHijri <='" . $this->CutOffDateHijri . "'")->sum('AmountRequested1');
                $res2 = \app\modules\invoice\models\InvoiceAmountEdit::find()->joinWith(['invoice'])->where(["$invoiceTable.ContractID" => $this->ContractID, $invoiceTable . '.AmountStatus' => $amountStatusArray, $invoiceTable . '.InvoiceType' => $this->InvoiceType, 'AmountType' => 1, $invoiceAmountTable . '.Currency2' => $this->Currency1IDRef])->andWhere("$invoiceTable.CutOffDateHijri <='" . $this->CutOffDateHijri . "'")->sum('AmountRequested2');
                $res = $res1 + $res2;
                break;
        }
        return $res;
    }

    public function cumulativeRequested2($amountStatus) {
        $invoiceTable = \app\modules\invoice\models\InvoiceEdit::tableName();
        $invoiceAmountTable = \app\modules\invoice\models\InvoiceAmountEdit::tableName();
        if (!($this->Currency2IDRef > 0)) {
            return null;
        }
        $amountStatusArray = [];
        switch ($amountStatus) {
            case 0:
                $amountStatusArray = [0];
                break;
            case 1:
                $amountStatusArray = [1];
                break;
            case 5:
                $amountStatusArray = [5];
                break;
            case 6:
                $amountStatusArray = [1, 5];
                break;
        }

        switch ($this->InvoiceType) {
            case 1:
            case 3:
            case 5:
                $res = \app\modules\invoice\models\InvoiceAmountEdit::find()->joinWith(['invoice'])->where([InvoiceEdit::tableName() . '.ContractID' => $this->ContractID, InvoiceEdit::tableName() . '.AmountStatus' => $amountStatusArray, InvoiceEdit::tableName() . '.InvoiceType' => [1, 3, 5], 'AmountType' => 1])->andWhere(InvoiceEdit::tableName() . '.CutOffDateHijri <=\'' . $this->CutOffDateHijri . '\'')->sum('AmountRequested2');
                break;
            case 2:
            case 4:
                $res = \app\modules\invoice\models\InvoiceAmountEdit::find()->joinWith(['invoice'])->where([InvoiceEdit::tableName() . '.ContractID' => $this->ContractID, InvoiceEdit::tableName() . '.AmountStatus' => $amountStatusArray, InvoiceEdit::tableName() . '.InvoiceType' => [2, 4], 'AmountType' => 1])->andWhere(InvoiceEdit::tableName() . '.CutOffDateHijri <=\'' . $this->CutOffDateHijri . '\'')->sum('AmountRequested2');
                break;
            case 17:
            case 18:
            case 19:
            case 20:
            case 21:
            case 22:
                $res1 = \app\modules\invoice\models\InvoiceAmountEdit::find()
                        ->joinWith(['invoice'])
                        ->where([InvoiceEdit::tableName() . '.ContractID' => $this->ContractID, InvoiceEdit::tableName() . '.AmountStatus' => $amountStatusArray, InvoiceEdit::tableName() . '.InvoiceType' => $this->InvoiceType, 'AmountType' => 1, $invoiceAmountTable . '.Currency1' => $this->Currency2IDRef])->andWhere(InvoiceEdit::tableName() . '.CutOffDateHijri <=\'' . $this->CutOffDateHijri . '\'')
                        ->sum('AmountRequested1');
                $res2 = \app\modules\invoice\models\InvoiceAmountEdit::find()
                        ->joinWith(['invoice'])
                        ->where([InvoiceEdit::tableName() . '.ContractID' => $this->ContractID, InvoiceEdit::tableName() . '.AmountStatus' => $amountStatusArray, InvoiceEdit::tableName() . '.InvoiceType' => $this->InvoiceType, 'AmountType' => 1, $invoiceAmountTable . '.Currency2' => $this->Currency2IDRef])->andWhere(InvoiceEdit::tableName() . '.CutOffDateHijri <=\'' . $this->CutOffDateHijri . '\'')
                        ->sum('AmountRequested2');
                $res = $res1 + $res2;
                break;
        }
        return $res;
    }

    public function cumulativeApproved1($amountStatus) {
        $amountStatusArray = [];
        switch ($amountStatus) {
            case 0:
                $amountStatusArray = [0];
                break;
            case 1:
                $amountStatusArray = [1];
                break;
            case 5:
                $amountStatusArray = [5];
                break;
            case 6:
                $amountStatusArray = [1, 5];
                break;
        }

        switch ($this->InvoiceType) {
            case 1:
            case 3:
            case 5:
                $res = \app\modules\invoice\models\InvoiceAmountEdit::find()
                        ->joinWith(['invoice'])
                        ->where([InvoiceEdit::tableName() . '.ContractID' => $this->ContractID, InvoiceEdit::tableName() . '.AmountStatus' => $amountStatusArray, InvoiceEdit::tableName() . '.InvoiceType' => [1, 3, 5], 'AmountType' => 1])->andWhere(InvoiceEdit::tableName() . '.CutOffDateHijri <=\'' . $this->CutOffDateHijri . '\'')
                        ->sum('AmountApproved1');
                break;
            case 2:
            case 4:
                $res = \app\modules\invoice\models\InvoiceAmountEdit::find()->joinWith(['invoice'])->where([InvoiceEdit::tableName() . '.ContractID' => $this->ContractID, InvoiceEdit::tableName() . '.AmountStatus' => $amountStatusArray, InvoiceEdit::tableName() . '.InvoiceType' => [2, 4], 'AmountType' => 1])->andWhere(InvoiceEdit::tableName() . '.CutOffDateHijri <=\'' . $this->CutOffDateHijri . '\'')->sum('AmountApproved1');
                break;
            case 17:
            case 18:
            case 19:
            case 20:
            case 21:
            case 22:
                $res1 = \app\modules\invoice\models\InvoiceAmountEdit::find()->joinWith(['invoice'])->where([InvoiceEdit::tableName() . '.ContractID' => $this->ContractID, InvoiceEdit::tableName() . '.AmountStatus' => $amountStatusArray, InvoiceEdit::tableName() . '.InvoiceType' => $this->InvoiceType, 'AmountType' => 1, InvoiceAmountEdit::tableName() . '.Currency1' => $this->Currency1IDRef])->andWhere(InvoiceEdit::tableName() . '.CutOffDateHijri <=\'' . $this->CutOffDateHijri . '\'')->sum('AmountApproved1');
                $res2 = \app\modules\invoice\models\InvoiceAmountEdit::find()->joinWith(['invoice'])->where([InvoiceEdit::tableName() . '.ContractID' => $this->ContractID, InvoiceEdit::tableName() . '.AmountStatus' => $amountStatusArray, InvoiceEdit::tableName() . '.InvoiceType' => $this->InvoiceType, 'AmountType' => 1, InvoiceAmountEdit::tableName() . '.Currency2' => $this->Currency1IDRef])->andWhere(InvoiceEdit::tableName() . '.CutOffDateHijri <=\'' . $this->CutOffDateHijri . '\'')->sum('AmountApproved2');
                $res = $res1 + $res2;
                break;
        }
        return $res;
    }

    public function cumulativeApproved2($amountStatus) {
        $invoiceTable = \app\modules\invoice\models\InvoiceEdit::tableName();
        $invoiceAmountTable = \app\modules\invoice\models\InvoiceAmountEdit::tableName();
        if (!($this->Currency2IDRef > 0)) {
            return null;
        }
        $amountStatusArray = [];
        switch ($amountStatus) {
            case 0:
                $amountStatusArray = [0];
                break;
            case 1:
                $amountStatusArray = [1];
                break;
            case 5:
                $amountStatusArray = [5];
                break;
            case 6:
                $amountStatusArray = [1, 5];
                break;
        }

        switch ($this->InvoiceType) {
            case 1:
            case 3:
            case 5:
                $res = \app\modules\invoice\models\InvoiceAmountEdit::find()->joinWith(['invoice'])->where(["$invoiceTable.ContractID" => $this->ContractID, "$invoiceTable.AmountStatus" => $amountStatusArray, "$invoiceTable.InvoiceType" => [1, 3, 5], 'AmountType' => 1])->andWhere("$invoiceTable.CutOffDateHijri <='" . $this->CutOffDateHijri . '\'')->sum('AmountApproved2');
                break;
            case 2:
            case 4:
                $res = \app\modules\invoice\models\InvoiceAmountEdit::find()->joinWith(['invoice'])->where(["$invoiceTable.ContractID" => $this->ContractID, "$invoiceTable.AmountStatus" => $amountStatusArray, "$invoiceTable.InvoiceType" => [2, 4], 'AmountType' => 1])->andWhere("$invoiceTable.CutOffDateHijri <='" . $this->CutOffDateHijri . '\'')->sum('AmountApproved2');
                break;
            case 17:
            case 18:
            case 19:
            case 20:
            case 21:
            case 22:
                $res1 = \app\modules\invoice\models\InvoiceAmountEdit::find()->joinWith(['invoice'])->where(["$invoiceTable.ContractID" => $this->ContractID, "$invoiceTable.AmountStatus" => $amountStatusArray, "$invoiceTable.InvoiceType" => $this->InvoiceType, 'AmountType' => 1, "$invoiceAmountTable.Currency1" => $this->Currency2IDRef])->andWhere("$invoiceTable.CutOffDateHijri <='" . $this->CutOffDateHijri . '\'')->sum('AmountApproved1');
                $res2 = \app\modules\invoice\models\InvoiceAmountEdit::find()->joinWith(['invoice'])->where(["$invoiceTable.ContractID" => $this->ContractID, "$invoiceTable.AmountStatus" => $amountStatusArray, "$invoiceTable.InvoiceType" => $this->InvoiceType, 'AmountType' => 1, "$invoiceAmountTable.Currency2" => $this->Currency2IDRef])->andWhere("$invoiceTable.CutOffDateHijri <='" . $this->CutOffDateHijri . '\'')->sum('AmountApproved2');
                $res = $res1 + $res2;
                break;
        }
        return $res;
    }

    public function cumulativeAdjustment1($amountStatus) {
        $invoiceTable = \app\modules\invoice\models\InvoiceEdit::tableName();
        $amountStatusArray = [];
        switch ($amountStatus) {
            case 0:
                $amountStatusArray = [0];
                break;
            case 1:
                $amountStatusArray = [1];
                break;
            case 5:
                $amountStatusArray = [5];
                break;
            case 6:
                $amountStatusArray = [1, 5];
                break;
        }

        $res = \app\modules\invoice\models\InvoiceAmountEdit::find()->joinWith(['invoice'])->where([$invoiceTable . '.ContractID' => $this->ContractID, $invoiceTable . '.AmountStatus' => $amountStatusArray, $invoiceTable . '.InvoiceType' => [2, 4], 'AmountType' => 1])->andWhere($invoiceTable . '.CutOffDateHijri <=\'' . $this->CutOffDateHijri . '\'')->sum('AmountApproved1');
        return $res;
    }

    public function cumulativeAdjustment2($amountStatus) {
        $invoiceTable = \app\modules\invoice\models\InvoiceEdit::tableName();
        $amountStatusArray = [];
        switch ($amountStatus) {
            case 0:
                $amountStatusArray = [0];
                break;
            case 1:
                $amountStatusArray = [1];
                break;
            case 5:
                $amountStatusArray = [5];
                break;
            case 6:
                $amountStatusArray = [1, 5];
                break;
        }

        $res = \app\modules\invoice\models\InvoiceAmountEdit::find()->joinWith(['invoice'])->where([InvoiceEdit::tableName() . '.ContractID' => $this->ContractID, InvoiceEdit::tableName() . '.AmountStatus' => $amountStatusArray, InvoiceEdit::tableName() . '.InvoiceType' => [2, 4], 'AmountType' => 1])->andWhere(InvoiceEdit::tableName() . '.CutOffDateHijri <=\'' . $this->CutOffDateHijri . '\'')->sum('AmountApproved2');
        return $res;
    }

    public function cumulativeIncome1($amountStatus) {
        $amountStatusArray = [];
        switch ($amountStatus) {
            case 0:
                $amountStatusArray = [0];
                break;
            case 1:
                $amountStatusArray = [1];
                break;
            case 5:
                $amountStatusArray = [5];
                break;
            case 6:
                $amountStatusArray = [1, 5];
                break;
        }

        $res = \app\modules\invoice\models\InvoiceAmountEdit::find()->joinWith(['invoice'])->where([InvoiceEdit::tableName() . '.ContractID' => $this->ContractID, InvoiceEdit::tableName() . '.AmountStatus' => $amountStatusArray, InvoiceEdit::tableName() . '.InvoiceType' => [1, 2, 3, 4, 5], 'AmountType' => 1])->andWhere(InvoiceEdit::tableName() . '.CutOffDateHijri <=\'' . $this->CutOffDateHijri . '\'')->sum('Income1');
        return $res;
    }

    public function cumulativeIncome2($amountStatus) {
        $amountStatusArray = [];
        switch ($amountStatus) {
            case 0:
                $amountStatusArray = [0];
                break;
            case 1:
                $amountStatusArray = [1];
                break;
            case 5:
                $amountStatusArray = [5];
                break;
            case 6:
                $amountStatusArray = [1, 5];
                break;
        }

        $res = \app\modules\invoice\models\InvoiceAmountEdit::find()->joinWith(['invoice'])->where([InvoiceEdit::tableName() . '.ContractID' => $this->ContractID, InvoiceEdit::tableName() . '.AmountStatus' => $amountStatusArray, InvoiceEdit::tableName() . '.InvoiceType' => [1, 2, 3, 4, 5], 'AmountType' => 1])->andWhere(InvoiceEdit::tableName() . '.CutOffDateHijri <=\'' . $this->CutOffDateHijri . '\'')->sum('Income2');
        return $res;
    }

    public function cumulativePenalty1($amountStatus) {
        $amountStatusArray = [];
        switch ($amountStatus) {
            case 0:
                $amountStatusArray = [0];
                break;
            case 1:
                $amountStatusArray = [1];
                break;
            case 5:
                $amountStatusArray = [5];
                break;
            case 6:
                $amountStatusArray = [1, 5];
                break;
        }

        $res = \app\modules\invoice\models\InvoiceAmountEdit::find()->joinWith(['invoice'])->where([InvoiceEdit::tableName() . '.ContractID' => $this->ContractID, InvoiceEdit::tableName() . '.AmountStatus' => $amountStatusArray, InvoiceEdit::tableName() . '.InvoiceType' => [1, 2, 3, 4, 5], 'AmountType' => 3])->andWhere(InvoiceEdit::tableName() . '.CutOffDateHijri <=\'' . $this->CutOffDateHijri . '\'')->sum('AmountApproved1');
        return $res;
    }

    public function cumulativePenalty2($amountStatus) {
        $amountStatusArray = [];
        switch ($amountStatus) {
            case 0:
                $amountStatusArray = [0];
                break;
            case 1:
                $amountStatusArray = [1];
                break;
            case 5:
                $amountStatusArray = [5];
                break;
            case 6:
                $amountStatusArray = [1, 5];
                break;
        }

        $res = \app\modules\invoice\models\InvoiceAmountEdit::find()->joinWith(['invoice'])->where([InvoiceEdit::tableName() . '.ContractID' => $this->ContractID, InvoiceEdit::tableName() . '.AmountStatus' => $amountStatusArray, InvoiceEdit::tableName() . '.InvoiceType' => [1, 2, 3, 4, 5], 'AmountType' => 3])->andWhere(InvoiceEdit::tableName() . '.CutOffDateHijri <=\'' . $this->CutOffDateHijri . '\'')->sum('AmountApproved2');
        return $res;
    }

    public static function amountByOwnerAndProject($invoiceId) {
        $costcodeTable = \app\modules\wbs\models\Costcodenew::tableName();
        $invoiceTable = \app\modules\invoice\models\InvoiceEdit::tableName();
        $invoiceAmountTable = \app\modules\invoice\models\InvoiceAmountEdit::tableName();

        $invoiceModel = \app\modules\invoice\models\InvoiceEdit::find()
                ->where(['InvoiceID' => $invoiceId])
                ->one();

        $projectIdMainOwnerIdNodeIdArray = \app\modules\wbs\models\Costcodenew::find()
                        ->select([
                            'projectId' => "$costcodeTable.ProjectIDRef",
                            'mainOwnerId' => "$costcodeTable.ownerCompanyIdRef",
                            'nodeId' => "Left($costcodeTable.NodeID,6)",
                        ])->innerJoin($invoiceAmountTable, "$invoiceAmountTable.CostCode=$costcodeTable.NodeID")
                        ->innerJoin($invoiceTable, "$invoiceTable.InvoiceID=$invoiceAmountTable.InvoiceID")
                        ->andWhere("$invoiceTable.ContractID=$invoiceModel->ContractID")
                        ->groupBy(["$costcodeTable.ProjectIDRef", "$costcodeTable.ownerCompanyIdRef", "Left($costcodeTable.NodeID,6)"])
                        ->asArray()->all()
        ;

        $currency1Array = \app\modules\invoice\models\InvoiceAmountEdit::find()
                        ->select([
                            'currencyId' => "$invoiceAmountTable.Currency1",
                        ])->innerJoin($invoiceTable, "$invoiceTable.InvoiceID=$invoiceAmountTable.InvoiceID")
                        ->andWhere("$invoiceTable.ContractID=$invoiceModel->ContractID")
                        ->distinct()->asArray()->all();
        $currency2Array = \app\modules\invoice\models\InvoiceAmountEdit::find()
                        ->select([
                            'currencyId' => "$invoiceAmountTable.Currency2",
                        ])->innerJoin($invoiceTable, "$invoiceTable.InvoiceID=$invoiceAmountTable.InvoiceID")
                        ->andWhere("$invoiceTable.ContractID=$invoiceModel->ContractID")
                        ->andWhere("$invoiceAmountTable.Currency2>0")
                        ->distinct()->asArray()->all();

        $currencyArray = array_merge($currency1Array, $currency2Array);

        $result = [];
        $i = 0;
        foreach ($projectIdMainOwnerIdNodeIdArray as $projectIdMainOwnerIdNodeIdItem) {
            foreach ($currencyArray as $currencyItem) {
                $nodeText = \app\modules\wbs\models\Costcodenew::find()->where(["NodeID" => $projectIdMainOwnerIdNodeIdItem['nodeId']])->select(["NodeShortText"])->asArray()->one();
                $result[$i]['projectId'] = $projectIdMainOwnerIdNodeIdItem['projectId'];
                $result[$i]['mainOwnerId'] = $projectIdMainOwnerIdNodeIdItem['mainOwnerId'];
                $result[$i]['mainOwnerTitle'] = \app\modules\OBS\models\Person::findOne($projectIdMainOwnerIdNodeIdItem['mainOwnerId'])->PersonTitle;
                $result[$i]['nodeId'] = $projectIdMainOwnerIdNodeIdItem['nodeId'];
                $result[$i]['nodeText'] = $nodeText['NodeShortText'];
                $result[$i]['currencyId'] = $currencyItem['currencyId'];
                $result[$i]['currencyTitle'] = \app\modules\currency\models\Currency::findOne($currencyItem['currencyId'])->CurrencyTitle;
                $i++;
            }
        }


        switch ($invoiceModel->InvoiceType) {
            case 1:
            case 3:
            case 5:
                $invoiceTypeString = '(1,3,5)';
                break;
            case 2:
            case 4:
                $invoiceTypeString = '(2,4)';
                break;
            case 17:
            case 18:
            case 19:
            case 20:
            case 21:
                $invoiceTypeString = "($invoiceModel->InvoiceType)";
                break;
        }


        foreach ($result as &$resultItem) {
            $res1 = \app\modules\wbs\models\Costcodenew::find()
                    ->innerJoin($invoiceAmountTable, "$invoiceAmountTable.CostCode=$costcodeTable.NodeID")
                    ->andWhere("$invoiceAmountTable.InvoiceID=$invoiceModel->InvoiceID")
                    ->andWhere("$invoiceAmountTable.AmountType=1")
                    ->andWhere("$invoiceAmountTable.Currency1=" . $resultItem['currencyId'])
                    ->andWhere("$invoiceAmountTable.CostCode like '" . $resultItem['nodeId'] . "%'")
                    ->andWhere("$costcodeTable.ownerCompanyIdRef=" . $resultItem['mainOwnerId'])
                    ->sum("$invoiceAmountTable.AmountApproved1");
            $res2 = \app\modules\wbs\models\Costcodenew::find()
                    ->innerJoin($invoiceAmountTable, "$invoiceAmountTable.CostCode=$costcodeTable.NodeID")
                    ->andWhere("$invoiceAmountTable.InvoiceID=$invoiceModel->InvoiceID")
                    ->andWhere("$invoiceAmountTable.AmountType=1")
                    ->andWhere("$invoiceAmountTable.Currency2=" . $resultItem['currencyId'])
                    ->andWhere("$invoiceAmountTable.CostCode like '" . $resultItem['nodeId'] . "%'")
                    ->andWhere("$costcodeTable.ownerCompanyIdRef=" . $resultItem['mainOwnerId'])
                    ->sum("$invoiceAmountTable.AmountApproved2");
            $resultItem['approved'] = $res1 + $res2;

            $res1Cum = \app\modules\wbs\models\Costcodenew::find()
                    ->innerJoin($invoiceAmountTable, "$invoiceAmountTable.CostCode=$costcodeTable.NodeID")
                    ->innerJoin($invoiceTable, "$invoiceTable.InvoiceID=$invoiceAmountTable.InvoiceID")
                    ->andWhere("$invoiceTable.ContractID=$invoiceModel->ContractID")
                    ->andWhere("$invoiceTable.AmountStatus in (1,5)")
                    ->andWhere("$invoiceTable.InvoiceType in (1,3,5)")
                    ->andWhere("$invoiceAmountTable.AmountType=1")
                    ->andWhere("$invoiceAmountTable.Currency1=" . $resultItem['currencyId'])
                    ->andWhere("$invoiceTable.CutOffDateHijri<='$invoiceModel->CutOffDateHijri'")
                    ->andWhere("$invoiceAmountTable.CostCode like '" . $resultItem['nodeId'] . "%'")
                    ->andWhere("$costcodeTable.ownerCompanyIdRef=" . $resultItem['mainOwnerId'])
                    ->sum("$invoiceAmountTable.AmountApproved1");
            $res2Cum = \app\modules\wbs\models\Costcodenew::find()
                    ->innerJoin($invoiceAmountTable, "$invoiceAmountTable.CostCode=$costcodeTable.NodeID")
                    ->innerJoin($invoiceTable, "$invoiceTable.InvoiceID=$invoiceAmountTable.InvoiceID")
                    ->andWhere("$invoiceTable.ContractID=$invoiceModel->ContractID")
                    ->andWhere("$invoiceTable.AmountStatus in (1,5)")
                    ->andWhere("$invoiceTable.InvoiceType in (1,3,5)")
                    ->andWhere("$invoiceAmountTable.AmountType=1")
                    ->andWhere("$invoiceAmountTable.Currency2=" . $resultItem['currencyId'])
                    ->andWhere("$invoiceTable.CutOffDateHijri<='$invoiceModel->CutOffDateHijri'")
                    ->andWhere("$invoiceAmountTable.CostCode like '" . $resultItem['nodeId'] . "%'")
                    ->andWhere("$costcodeTable.ownerCompanyIdRef=" . $resultItem['mainOwnerId'])
                    ->sum("$invoiceAmountTable.AmountApproved2");

            $resultItem['cumulativeApproved'] = $res1Cum + $res2Cum;
        }

        return $result;
    }

    //از این تابع در نامه ها استفاده شده
    public function cumulativeApproved1ByCostCode($amountStatus, $nodeId) {
        $amountStatusArray = [];
        switch ($amountStatus) {
            case 0:
                $amountStatusArray = [0];
                break;
            case 1:
                $amountStatusArray = [1];
                break;
            case 5:
                $amountStatusArray = [5];
                break;
            case 6:
                $amountStatusArray = [1, 5];
                break;
        }

        switch ($this->InvoiceType) {
            case 1:
            case 3:
            case 5:
                $res = \app\modules\invoice\models\InvoiceAmountEdit::find()->joinWith(['invoice'])->where([InvoiceEdit::tableName() . '.ContractID' => $this->ContractID, InvoiceEdit::tableName() . '.AmountStatus' => $amountStatusArray, InvoiceEdit::tableName() . '.InvoiceType' => [1, 3, 5], 'AmountType' => 1])->andWhere(InvoiceEdit::tableName() . '.CutOffDateHijri <=\'' . $this->CutOffDateHijri . '\'')->andWhere(InvoiceAmountEdit::tableName() . ".CostCode like '$nodeId%'")->sum('AmountApproved1');
                break;
            case 2:
            case 4:
                $res = \app\modules\invoice\models\InvoiceAmountEdit::find()->joinWith(['invoice'])->where([InvoiceEdit::tableName() . '.ContractID' => $this->ContractID, InvoiceEdit::tableName() . '.AmountStatus' => $amountStatusArray, InvoiceEdit::tableName() . '.InvoiceType' => [2, 4], 'AmountType' => 1])->andWhere(InvoiceEdit::tableName() . '.CutOffDateHijri <=\'' . $this->CutOffDateHijri . '\'')->andWhere(InvoiceAmountEdit::tableName() . ".CostCode like '$nodeId%'")->sum('AmountApproved1');
                break;
            case 17:
            case 18:
            case 19:
            case 20:
            case 21:
                $res1 = \app\modules\invoice\models\InvoiceAmountEdit::find()->joinWith(['invoice'])->where([InvoiceEdit::tableName() . '.ContractID' => $this->ContractID, InvoiceEdit::tableName() . '.AmountStatus' => $amountStatusArray, InvoiceEdit::tableName() . '.InvoiceType' => $this->InvoiceType, 'AmountType' => 1, InvoiceAmountEdit::tableName() . '.Currency1' => $this->Currency1IDRef])->andWhere(InvoiceEdit::tableName() . '.CutOffDateHijri <=\'' . $this->CutOffDateHijri . '\'')->andWhere(InvoiceAmountEdit::tableName() . ".CostCode like '$nodeId%'")->sum('AmountApproved1');
                $res2 = \app\modules\invoice\models\InvoiceAmountEdit::find()->joinWith(['invoice'])->where([InvoiceEdit::tableName() . '.ContractID' => $this->ContractID, InvoiceEdit::tableName() . '.AmountStatus' => $amountStatusArray, InvoiceEdit::tableName() . '.InvoiceType' => $this->InvoiceType, 'AmountType' => 1, InvoiceAmountEdit::tableName() . '.Currency2' => $this->Currency1IDRef])->andWhere(InvoiceEdit::tableName() . '.CutOffDateHijri <=\'' . $this->CutOffDateHijri . '\'')->andWhere(InvoiceAmountEdit::tableName() . ".CostCode like '$nodeId%'")->sum('AmountApproved2');
                $res = $res1 + $res2;
                break;
        }
        return $res;
    }

    public function cumulativeApproved2ByCostCode($amountStatus, $nodeId) {
        $invoiceTable = \app\modules\invoice\models\InvoiceEdit::tableName();
        if (!($this->Currency2IDRef > 0)) {
            return null;
        }
        $amountStatusArray = [];
        switch ($amountStatus) {
            case 0:
                $amountStatusArray = [0];
                break;
            case 1:
                $amountStatusArray = [1];
                break;
            case 5:
                $amountStatusArray = [5];
                break;
            case 6:
                $amountStatusArray = [1, 5];
                break;
        }
        switch ($this->InvoiceType) {
            case 1:
            case 3:
            case 5:
                $res = \app\modules\invoice\models\InvoiceAmountEdit::find()->joinWith(['invoice'])->where(["$invoiceTable.ContractID" => $this->ContractID, "$invoiceTable.AmountStatus" => $amountStatusArray, "$invoiceTable.InvoiceType" => [1, 3, 5], 'AmountType' => 1])->andWhere("$invoiceTable.CutOffDateHijri <='" . $this->CutOffDateHijri . '\'')->andWhere(InvoiceAmountEdit::tableName() . ".CostCode like '$nodeId%'")->sum('AmountApproved2');
                break;
            case 2:
            case 4:
                $res = \app\modules\invoice\models\InvoiceAmountEdit::find()->joinWith(['invoice'])->where(["$invoiceTable.ContractID" => $this->ContractID, "$invoiceTable.AmountStatus" => $amountStatusArray, "$invoiceTable.InvoiceType" => [2, 4], 'AmountType' => 1])->andWhere("$invoiceTable.CutOffDateHijri <='" . $this->CutOffDateHijri . '\'')->andWhere(InvoiceAmountEdit::tableName() . ".CostCode like '$nodeId%'")->sum('AmountApproved2');
                break;
            case 17:
            case 18:
            case 19:
            case 20:
            case 21:
                $res1 = \app\modules\invoice\models\InvoiceAmountEdit::find()->joinWith(['invoice'])->where([InvoiceEdit::tableName() . '.ContractID' => $this->ContractID, InvoiceEdit::tableName() . '.AmountStatus' => $amountStatusArray, InvoiceEdit::tableName() . '.InvoiceType' => $this->InvoiceType, 'AmountType' => 1, InvoiceAmountEdit::tableName() . '.Currency1' => $this->Currency2IDRef])->andWhere(InvoiceEdit::tableName() . '.CutOffDateHijri <=\'' . $this->CutOffDateHijri . '\'')->andWhere(InvoiceAmountEdit::tableName() . ".CostCode like '$nodeId%'")->sum('AmountApproved1');
                $res2 = \app\modules\invoice\models\InvoiceAmountEdit::find()->joinWith(['invoice'])->where([InvoiceEdit::tableName() . '.ContractID' => $this->ContractID, InvoiceEdit::tableName() . '.AmountStatus' => $amountStatusArray, InvoiceEdit::tableName() . '.InvoiceType' => $this->InvoiceType, 'AmountType' => 1, InvoiceAmountEdit::tableName() . '.Currency2' => $this->Currency2IDRef])->andWhere(InvoiceEdit::tableName() . '.CutOffDateHijri <=\'' . $this->CutOffDateHijri . '\'')->andWhere(InvoiceAmountEdit::tableName() . ".CostCode like '$nodeId%'")->sum('AmountApproved2');
                $res = $res1 + $res2;
                break;
        }
        return $res;
    }

    public function cumulativeRequested1ByCostCode($amountStatus, $nodeId) {
        $amountStatusArray = [];
        switch ($amountStatus) {
            case 0:
                $amountStatusArray = [0];
                break;
            case 1:
                $amountStatusArray = [1];
                break;
            case 5:
                $amountStatusArray = [5];
                break;
            case 6:
                $amountStatusArray = [1, 5];
                break;
        }

        switch ($this->InvoiceType) {
            case 1:
            case 3:
            case 5:
                $res = \app\modules\invoice\models\InvoiceAmountEdit::find()->joinWith(['invoice'])->where([InvoiceEdit::tableName() . '.ContractID' => $this->ContractID, InvoiceEdit::tableName() . '.AmountStatus' => $amountStatusArray, InvoiceEdit::tableName() . '.InvoiceType' => [1, 3, 5], 'AmountType' => 1])->andWhere(InvoiceEdit::tableName() . '.CutOffDateHijri <=\'' . $this->CutOffDateHijri . '\'')->andWhere(InvoiceAmountEdit::tableName() . ".CostCode like '$nodeId%'")->sum('AmountRequested1');
                break;
            case 2:
            case 4:
                $res = \app\modules\invoice\models\InvoiceAmountEdit::find()->joinWith(['invoice'])->where([InvoiceEdit::tableName() . '.ContractID' => $this->ContractID, InvoiceEdit::tableName() . '.AmountStatus' => $amountStatusArray, InvoiceEdit::tableName() . '.InvoiceType' => [2, 4], 'AmountType' => 1])->andWhere(InvoiceEdit::tableName() . '.CutOffDateHijri <=\'' . $this->CutOffDateHijri . '\'')->andWhere(InvoiceAmountEdit::tableName() . ".CostCode like '$nodeId%'")->sum('AmountRequested1');
                break;
            case 17:
            case 18:
            case 19:
            case 20:
            case 21:
                $res1 = \app\modules\invoice\models\InvoiceAmountEdit::find()->joinWith(['invoice'])->where([InvoiceEdit::tableName() . '.ContractID' => $this->ContractID, InvoiceEdit::tableName() . '.AmountStatus' => $amountStatusArray, InvoiceEdit::tableName() . '.InvoiceType' => $this->InvoiceType, 'AmountType' => 1, InvoiceAmountEdit::tableName() . '.Currency1' => $this->Currency1IDRef])->andWhere(InvoiceEdit::tableName() . '.CutOffDateHijri <=\'' . $this->CutOffDateHijri . '\'')->andWhere(InvoiceAmountEdit::tableName() . ".CostCode like '$nodeId%'")->sum('AmountRequested1');
                $res2 = \app\modules\invoice\models\InvoiceAmountEdit::find()->joinWith(['invoice'])->where([InvoiceEdit::tableName() . '.ContractID' => $this->ContractID, InvoiceEdit::tableName() . '.AmountStatus' => $amountStatusArray, InvoiceEdit::tableName() . '.InvoiceType' => $this->InvoiceType, 'AmountType' => 1, InvoiceAmountEdit::tableName() . '.Currency2' => $this->Currency1IDRef])->andWhere(InvoiceEdit::tableName() . '.CutOffDateHijri <=\'' . $this->CutOffDateHijri . '\'')->andWhere(InvoiceAmountEdit::tableName() . ".CostCode like '$nodeId%'")->sum('AmountRequested2');
                $res = $res1 + $res2;
                break;
        }
        return $res;
    }

    public function cumulativeRequested2ByCostCode($amountStatus, $nodeId) {
        $invoiceTable = \app\modules\invoice\models\InvoiceEdit::tableName();
        if (!($this->Currency2IDRef > 0)) {
            return null;
        }
        $amountStatusArray = [];
        switch ($amountStatus) {
            case 0:
                $amountStatusArray = [0];
                break;
            case 1:
                $amountStatusArray = [1];
                break;
            case 5:
                $amountStatusArray = [5];
                break;
            case 6:
                $amountStatusArray = [1, 5];
                break;
        }
        switch ($this->InvoiceType) {
            case 1:
            case 3:
            case 5:
                $res = \app\modules\invoice\models\InvoiceAmountEdit::find()->joinWith(['invoice'])->where(["$invoiceTable.ContractID" => $this->ContractID, "$invoiceTable.AmountStatus" => $amountStatusArray, "$invoiceTable.InvoiceType" => [1, 3, 5], 'AmountType' => 1])->andWhere("$invoiceTable.CutOffDateHijri <='" . $this->CutOffDateHijri . '\'')->andWhere(InvoiceAmountEdit::tableName() . ".CostCode like '$nodeId%'")->sum('AmountRequested2');
                break;
            case 2:
            case 4:
                $res = \app\modules\invoice\models\InvoiceAmountEdit::find()->joinWith(['invoice'])->where(["$invoiceTable.ContractID" => $this->ContractID, "$invoiceTable.AmountStatus" => $amountStatusArray, "$invoiceTable.InvoiceType" => [2, 4], 'AmountType' => 1])->andWhere("$invoiceTable.CutOffDateHijri <='" . $this->CutOffDateHijri . '\'')->andWhere(InvoiceAmountEdit::tableName() . ".CostCode like '$nodeId%'")->sum('AmountRequested2');
                break;
            case 17:
            case 18:
            case 19:
            case 20:
            case 21:
                $res1 = \app\modules\invoice\models\InvoiceAmountEdit::find()->joinWith(['invoice'])->where([InvoiceEdit::tableName() . '.ContractID' => $this->ContractID, InvoiceEdit::tableName() . '.AmountStatus' => $amountStatusArray, InvoiceEdit::tableName() . '.InvoiceType' => $this->InvoiceType, 'AmountType' => 1, InvoiceAmountEdit::tableName() . '.Currency1' => $this->Currency2IDRef])->andWhere(InvoiceEdit::tableName() . '.CutOffDateHijri <=\'' . $this->CutOffDateHijri . '\'')->andWhere(InvoiceAmountEdit::tableName() . ".CostCode like '$nodeId%'")->sum('AmountRequested1');
                $res2 = \app\modules\invoice\models\InvoiceAmountEdit::find()->joinWith(['invoice'])->where([InvoiceEdit::tableName() . '.ContractID' => $this->ContractID, InvoiceEdit::tableName() . '.AmountStatus' => $amountStatusArray, InvoiceEdit::tableName() . '.InvoiceType' => $this->InvoiceType, 'AmountType' => 1, InvoiceAmountEdit::tableName() . '.Currency2' => $this->Currency2IDRef])->andWhere(InvoiceEdit::tableName() . '.CutOffDateHijri <=\'' . $this->CutOffDateHijri . '\'')->andWhere(InvoiceAmountEdit::tableName() . ".CostCode like '$nodeId%'")->sum('AmountRequested2');
                $res = $res1 + $res2;
                break;
        }
        return $res;
    }

    public function approved1ByCostCode($nodeId) {
        return $activeQuery = InvoiceAmountEdit::find()->where(['InvoiceID' => $this->InvoiceID, 'AmountType' => 1])->andWhere(InvoiceAmountEdit::tableName() . ".CostCode like '$nodeId%'")->sum('AmountApproved1');
    }

    public function approved2ByCostCode($nodeId) {
        return ($this->Currency2IDRef > 0) ? $activeQuery = InvoiceAmountEdit::find()->where(['InvoiceID' => $this->InvoiceID, 'AmountType' => 1])->andWhere(InvoiceAmountEdit::tableName() . ".CostCode like '$nodeId%'")->sum('AmountApproved2') : null;
    }

    public function requested1ByCostCode($nodeId) {
        return $activeQuery = InvoiceAmountEdit::find()->where(['InvoiceID' => $this->InvoiceID, 'AmountType' => 1])->andWhere(InvoiceAmountEdit::tableName() . ".CostCode like '$nodeId%'")->sum('AmountRequested1');
    }

    public function requested2ByCostCode($nodeId) {
        return ($this->Currency2IDRef > 0) ? $activeQuery = InvoiceAmountEdit::find()->where(['InvoiceID' => $this->InvoiceID, 'AmountType' => 1])->andWhere(InvoiceAmountEdit::tableName() . ".CostCode like '$nodeId%'")->sum('AmountRequested2') : null;
    }

    public Static function cumulativeAditionalCost($invoiceId) {
        // هزینه جانبی  
        //نوشته شده توسط مهندس کمالی برای نامه های ورد


        $invoiceModel = InvoiceEdit::find()
                ->where(['InvoiceID' => $invoiceId])
                ->one();

        $subQuery1 = (new Query())
                ->from(InvoiceAmountEdit::tableName())
                ->select([InvoiceAmountEdit::tableName() . '.Currency1 as Currency', 'Sum(AmountApproved1) as Approved1cumulative'])
                ->innerJoin(InvoiceEdit::tableName(), InvoiceEdit::tableName() . '.InvoiceID=' . InvoiceAmountEdit::tableName() . '.InvoiceID')
                ->where([InvoiceEdit::tableName() . '.ContractID' => $invoiceModel->ContractID, InvoiceEdit::tableName() . '.AmountStatus' => [1, 5], InvoiceEdit::tableName() . '.InvoiceType' => $invoiceModel->InvoiceType, 'AmountType' => 1])
                ->andWhere(InvoiceEdit::tableName() . '.CutOffDateHijri <=\'' . $invoiceModel->CutOffDateHijri . '\'')
                ->groupBy(InvoiceAmountEdit::tableName() . '.Currency1')
        ;
        $subQuery2 = (new Query())
                ->from(InvoiceAmountEdit::tableName())
                ->select([InvoiceAmountEdit::tableName() . '.Currency2 as Currency', 'Sum(AmountApproved2) as Approved1cumulative'])
                ->innerJoin(InvoiceEdit::tableName(), InvoiceEdit::tableName() . '.InvoiceID=' . InvoiceAmountEdit::tableName() . '.InvoiceID')
                ->where([InvoiceEdit::tableName() . '.ContractID' => $invoiceModel->ContractID, InvoiceEdit::tableName() . '.AmountStatus' => [1, 5], InvoiceEdit::tableName() . '.InvoiceType' => $invoiceModel->InvoiceType, 'AmountType' => 1])
                ->andWhere(InvoiceEdit::tableName() . '.CutOffDateHijri <=\'' . $invoiceModel->CutOffDateHijri . '\'')
                ->groupBy(InvoiceAmountEdit::tableName() . '.Currency2')
        ;

        $mainQuery1 = (new Query())
                ->from(['temptable' => $subQuery1->union($subQuery2)])
                ->select(['Currency', 'Sum(temptable.Approved1cumulative) as Approved1cumulative'])
                ->groupBy('Currency')
                ->having(['and',
                    ['not', ['temptable.Currency' => null]],
                    ['not', ['temptable.Currency' => 0]],
                ])
                ->all();

        return $mainQuery1;
    }

    public function getHasDocument() {
        return $activeQuery = InvoiceDocument::find()->where(['InvoiceIDRef' => $this->InvoiceID])->andWhere('isDeleted is null or isDeleted != 1')->exists();
    }
    
    public function getRequested1() {
        return $activeQuery = InvoiceAmountEdit::find()->where(['InvoiceID' => $this->InvoiceID, 'AmountType' => 1])->sum('AmountRequested1');
    }

    public function getRequested2() {
        return ($this->Currency2IDRef > 0) ? $activeQuery = InvoiceAmountEdit::find()->where(['InvoiceID' => $this->InvoiceID, 'AmountType' => 1])->sum('AmountRequested2') : null;
    }

    public function getApproved1() {
        return $activeQuery = InvoiceAmountEdit::find()->where(['InvoiceID' => $this->InvoiceID, 'AmountType' => 1])->sum('AmountApproved1');
    }

    public function getApproved2() {
        return ($this->Currency2IDRef > 0) ? $activeQuery = InvoiceAmountEdit::find()->where(['InvoiceID' => $this->InvoiceID, 'AmountType' => 1])->sum('AmountApproved2') : null;
    }

    public function approved1InProject($projectKeys) {
        if ($projectKeys == '') {
            $projectKeys = '0';
        }
        $invoiceAmountTable = \app\modules\invoice\models\InvoiceAmountEdit::tableName();
        $costcodeTable = \app\modules\wbs\models\Costcodenew::tableName();
        return \app\components\ActiveModel::find('\app\modules\invoice\models\InvoiceAmountEdit')
                        ->innerJoin("$costcodeTable", "$costcodeTable.NodeID=$invoiceAmountTable.CostCode")
                        ->andWhere([
                            "$invoiceAmountTable.InvoiceID" => $this->InvoiceID,
                            "$invoiceAmountTable.AmountType" => 1,
                        ])
                        ->andWhere(['in', "$costcodeTable.ProjectIDRef", $projectKeys])
                        ->sum('AmountApproved1');
    }

    public function approved2InProject($projectKeys) {
        if ($projectKeys == '') {
            $projectKeys = '0';
        }
        $invoiceAmountTable = \app\modules\invoice\models\InvoiceAmountEdit::tableName();
        $costcodeTable = \app\modules\wbs\models\Costcodenew::tableName();
        return \app\components\ActiveModel::find('\app\modules\invoice\models\InvoiceAmountEdit')
                        ->innerJoin("$costcodeTable", "$costcodeTable.NodeID=$invoiceAmountTable.CostCode")
                        ->andWhere([
                            "$invoiceAmountTable.InvoiceID" => $this->InvoiceID,
                            "$invoiceAmountTable.AmountType" => 1,
                        ])
                        ->andWhere(['in', "$costcodeTable.ProjectIDRef", $projectKeys])
                        ->sum('AmountApproved2');
    }

    public function getHasDebitmemo() {
        // بررسی میکند که برای صورت وضعیت اعلام بدهی در حال بررسی یا تایید شده وجود دارد یا خیر
        $invoiceAmountTable = \app\modules\invoice\models\InvoiceAmountEdit::tableName();
        $debitmemoAmountTable = \app\modules\debitmemo\models\DebitmemoAmount::tableName();
        $debitmemoTable = \app\modules\debitmemo\models\Debitmemo::tableName();
        $processEntityTable = \app\modules\workflow\models\ProcessEntity::tableName();
        $count = InvoiceAmountEdit::find()->innerJoin($debitmemoAmountTable, "$debitmemoAmountTable.invoiceAmountIdRef=$invoiceAmountTable.ID")
                ->innerJoin($debitmemoTable, "$debitmemoTable.id=$debitmemoAmountTable.debitmemoIdRef")
                ->innerJoin($processEntityTable, "$debitmemoTable.id=$processEntityTable.debitmemoIdRef")
                ->where(["$invoiceAmountTable.InvoiceID" => $this->InvoiceID])
                ->andWhere(['not in', "$processEntityTable.LastActionIDRef", \app\modules\debitmemo\models\Debitmemo::rejectedActionArray()])
                ->count();
        if ($count > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getIncome1() {
        return $activeQuery = InvoiceAmountEdit::find()
                ->where(['InvoiceID' => $this->InvoiceID, 'AmountType' => 1])
                ->sum('Income1');
    }

    public function getIncome2() {
        return ($this->Currency2IDRef > 0) ? $activeQuery = InvoiceAmountEdit::find()
                ->where(['InvoiceID' => $this->InvoiceID, 'AmountType' => 1])
                ->sum('Income2') : null;
    }

    public function getPenalty1() {
        return $activeQuery = InvoiceAmountEdit::find()
                ->where(['InvoiceID' => $this->InvoiceID, 'AmountType' => 3])
                ->sum('AmountApproved1');
    }

    public function getPenalty2() {
        return ($this->Currency2IDRef > 0) ? $activeQuery = InvoiceAmountEdit::find()
                ->where(['InvoiceID' => $this->InvoiceID, 'AmountType' => 3])
                ->sum('AmountApproved2') : null;
    }

    public function getDebitmemoStatus() {
        if ($this->Currency2IDRef > 0) {
            $sum1 = $this->debitmemoApproved1 + $this->debitmemoInprogress1;
            $sum2 = $this->debitmemoApproved2 + $this->debitmemoInprogress2;
            if (($sum1 > $this->Approved1 && $this->Approved1 >= 0) && ($sum2 > $this->Approved2 && $this->Approved2 >= 0)) {
                return "red";
            } else if (($sum1 == $this->Approved1 ) && ($sum2 == $this->Approved2 )) {
                return "gray";
            } else if ($sum1 == 0 && $sum2 == 0) {
                return "green";
            } else {
                return "orange";
            }
        } else {
            $sum1 = $this->debitmemoApproved1 + $this->debitmemoInprogress1;
            if ($sum1 > $this->Approved1 && $this->Approved1 >= 0) {
                return "red";
            } else if ($sum1 == $this->Approved1) {
                return "gray";
            } else if ($sum1 == 0) {
                return "green";
            } else {
                return "orange";
            }
        }
    }

    public function getDebitmemoCumulativeApproved1() {
        $invoiceAmountTable = \app\modules\invoice\models\InvoiceAmountEdit::tableName();
        $debitmemoAmountTable = \app\modules\debitmemo\models\DebitmemoAmount::tableName();
        $debitmemoTable = \app\modules\debitmemo\models\Debitmemo::tableName();
        $processEntityTable = \app\modules\workflow\models\ProcessEntity::tableName();

        $res = InvoiceAmountEdit::find()->innerJoin($debitmemoAmountTable, "$debitmemoAmountTable.invoiceAmountIdRef=$invoiceAmountTable.ID")
                ->innerJoin($debitmemoTable, "$debitmemoTable.id=$debitmemoAmountTable.debitmemoIdRef")
                ->innerJoin($processEntityTable, "$debitmemoTable.id=$processEntityTable.debitmemoIdRef")
                ->where(["$debitmemoTable.contractIdRef" => $this->ContractID, "$debitmemoAmountTable.amountType" => 'cost'])
                ->andWhere(["$processEntityTable.LastActionIDRef" => array_merge(\app\modules\debitmemo\models\Debitmemo::approvedActionArray(), \app\modules\debitmemo\models\Debitmemo::inprogressActionArray())])
                ->andWhere("$debitmemoTable.debitmemoDateHijri<='" . $this->CutOffDateHijri . "'")
                ->sum("$debitmemoAmountTable.approved1");
        return $res;
    }

    public function getDebitmemoCumulativeApproved2() {
        $invoiceAmountTable = \app\modules\invoice\models\InvoiceAmountEdit::tableName();
        $debitmemoAmountTable = \app\modules\debitmemo\models\DebitmemoAmount::tableName();
        $debitmemoTable = \app\modules\debitmemo\models\Debitmemo::tableName();
        $processEntityTable = \app\modules\workflow\models\ProcessEntity::tableName();
        $res = InvoiceAmountEdit::find()->innerJoin($debitmemoAmountTable, "$debitmemoAmountTable.invoiceAmountIdRef=$invoiceAmountTable.ID")
                ->innerJoin($debitmemoTable, "$debitmemoTable.id=$debitmemoAmountTable.debitmemoIdRef")
                ->innerJoin($processEntityTable, "$debitmemoTable.id=$processEntityTable.debitmemoIdRef")
                ->where(["$debitmemoTable.contractIdRef" => $this->ContractID, "$debitmemoAmountTable.amountType" => 'cost'])
                ->andWhere(["$processEntityTable.LastActionIDRef" => array_merge(\app\modules\debitmemo\models\Debitmemo::approvedActionArray(), \app\modules\debitmemo\models\Debitmemo::inprogressActionArray())])
                ->andWhere("$debitmemoTable.debitmemoDateHijri<='" . $this->CutOffDateHijri . "'")
                ->sum("$debitmemoAmountTable.approved2");
        return $res;
    }

    public function getDebitmemoApproved1() {
        $invoiceAmountTable = \app\modules\invoice\models\InvoiceAmountEdit::tableName();
        $debitmemoAmountTable = \app\modules\debitmemo\models\DebitmemoAmount::tableName();
        $debitmemoTable = \app\modules\debitmemo\models\Debitmemo::tableName();
        $processEntityTable = \app\modules\workflow\models\ProcessEntity::tableName();
        $res = InvoiceAmountEdit::find()->innerJoin($debitmemoAmountTable, "$debitmemoAmountTable.invoiceAmountIdRef=$invoiceAmountTable.ID")
                ->innerJoin($debitmemoTable, "$debitmemoTable.id=$debitmemoAmountTable.debitmemoIdRef")
                ->innerJoin($processEntityTable, "$debitmemoTable.id=$processEntityTable.debitmemoIdRef")
                ->where(["$invoiceAmountTable.InvoiceID" => $this->InvoiceID, "$debitmemoAmountTable.amountType" => 'cost'])
                ->andWhere(["$processEntityTable.LastActionIDRef" => \app\modules\debitmemo\models\Debitmemo::approvedActionArray()])
                ->sum("$debitmemoAmountTable.approved1");
        return $res;
    }

    public function getDebitmemoApproved2() {
        $invoiceAmountTable = \app\modules\invoice\models\InvoiceAmountEdit::tableName();
        $debitmemoAmountTable = \app\modules\debitmemo\models\DebitmemoAmount::tableName();
        $debitmemoTable = \app\modules\debitmemo\models\Debitmemo::tableName();
        $processEntityTable = \app\modules\workflow\models\ProcessEntity::tableName();
        $res = InvoiceAmountEdit::find()->innerJoin($debitmemoAmountTable, "$debitmemoAmountTable.invoiceAmountIdRef=$invoiceAmountTable.ID")
                ->innerJoin($debitmemoTable, "$debitmemoTable.id=$debitmemoAmountTable.debitmemoIdRef")
                ->innerJoin($processEntityTable, "$debitmemoTable.id=$processEntityTable.debitmemoIdRef")
                ->where(["$invoiceAmountTable.InvoiceID" => $this->InvoiceID, "$debitmemoAmountTable.amountType" => 'cost'])
                ->andWhere(["$processEntityTable.LastActionIDRef" => \app\modules\debitmemo\models\Debitmemo::approvedActionArray()])
                ->sum("$debitmemoAmountTable.approved2");
        return $res;
    }

    public function getDebitmemoInprogress1() {
        $invoiceAmountTable = \app\modules\invoice\models\InvoiceAmountEdit::tableName();
        $debitmemoAmountTable = \app\modules\debitmemo\models\DebitmemoAmount::tableName();
        $debitmemoTable = \app\modules\debitmemo\models\Debitmemo::tableName();
        $processEntityTable = \app\modules\workflow\models\ProcessEntity::tableName();
        $res = InvoiceAmountEdit::find()->innerJoin($debitmemoAmountTable, "$debitmemoAmountTable.invoiceAmountIdRef=$invoiceAmountTable.ID")
                ->innerJoin($debitmemoTable, "$debitmemoTable.id=$debitmemoAmountTable.debitmemoIdRef")
                ->innerJoin($processEntityTable, "$debitmemoTable.id=$processEntityTable.debitmemoIdRef")
                ->where(["$invoiceAmountTable.InvoiceID" => $this->InvoiceID, "$debitmemoAmountTable.amountType" => 'cost'])
                ->andWhere(["$processEntityTable.LastActionIDRef" => \app\modules\debitmemo\models\Debitmemo::inprogressActionArray()])
                ->sum("$debitmemoAmountTable.approved1");
        return $res;
    }

    public function getDebitmemoInprogress2() {
        $invoiceAmountTable = \app\modules\invoice\models\InvoiceAmountEdit::tableName();
        $debitmemoAmountTable = \app\modules\debitmemo\models\DebitmemoAmount::tableName();
        $debitmemoTable = \app\modules\debitmemo\models\Debitmemo::tableName();
        $processEntityTable = \app\modules\workflow\models\ProcessEntity::tableName();
        $res = InvoiceAmountEdit::find()->innerJoin($debitmemoAmountTable, "$debitmemoAmountTable.invoiceAmountIdRef=$invoiceAmountTable.ID")
                ->innerJoin($debitmemoTable, "$debitmemoTable.id=$debitmemoAmountTable.debitmemoIdRef")
                ->innerJoin($processEntityTable, "$debitmemoTable.id=$processEntityTable.debitmemoIdRef")
                ->where(["$invoiceAmountTable.InvoiceID" => $this->InvoiceID, "$debitmemoAmountTable.amountType" => 'cost'])
                ->andWhere(["$processEntityTable.LastActionIDRef" => \app\modules\debitmemo\models\Debitmemo::inprogressActionArray()])
                ->sum("$debitmemoAmountTable.approved2");
        return $res;
    }

    public function getContract() {
        return $this->hasOne(\app\modules\contract\models\Contract::className(), ['ContractID' => 'ContractID']);
    }

    public function getProcessEntity() {
        return $this->hasOne(\app\modules\workflow\models\ProcessEntity::className(), ['InvoiceIDRef' => 'InvoiceID']);
    }

    public function getPreviousInvocie() {
        $invoiceModel = InvoiceEdit::find()
                ->andWhere(["ContractID" => $this->ContractID])
                ->andWhere("CutOffDate<'" . $this->CutOffDate . "'")
                ->andWhere("InvoiceID!=" . $this->InvoiceID)
                ->orderBy(["CutOffDate" => SORT_DESC])
                ->one()
        ;

        return $invoiceModel;
    }

    public function getCurrency1() {
        return $this->hasOne(\app\modules\currency\models\Currency::className(), ['CurrencyID' => 'Currency1IDRef']);
    }

    public function getCurrency2() {
        return $this->hasOne(\app\modules\currency\models\Currency::className(), ['CurrencyID' => 'Currency2IDRef']);
    }

    public function getType() {
        return $this->hasOne(InvoiceTypes::className(), ['InvoiceTypeID' => 'InvoiceType']);
    }

    public function getInvoiceAmount() {
        return $this->hasMany(InvoiceAmountEdit::className(), ['InvoiceID' => 'InvoiceID']);
    }

    public function cumulativeApproved1AfterThisInvoice($amountStatus) {
        $amountStatusArray = [];
        switch ($amountStatus) {
            case 0:
                $amountStatusArray = [0];
                break;
            case 1:
                $amountStatusArray = [1];
                break;
            case 5:
                $amountStatusArray = [5];
                break;
            case 6:
                $amountStatusArray = [1, 5];
                break;
        }

        switch ($this->InvoiceType) {
            case 1:
            case 3:
            case 5:
                $res = \app\modules\invoice\models\InvoiceAmountEdit::find()->joinWith(['invoice'])->where([InvoiceEdit::tableName() . '.ContractID' => $this->ContractID, InvoiceEdit::tableName() . '.AmountStatus' => $amountStatusArray, InvoiceEdit::tableName() . '.InvoiceType' => [1, 3, 5], 'AmountType' => 1])->andWhere(InvoiceEdit::tableName() . '.CutOffDateHijri <\'' . $this->CutOffDateHijri . '\'')->sum('AmountApproved1');
                break;
            case 2:
            case 4:
                $res = \app\modules\invoice\models\InvoiceAmountEdit::find()->joinWith(['invoice'])->where([InvoiceEdit::tableName() . '.ContractID' => $this->ContractID, InvoiceEdit::tableName() . '.AmountStatus' => $amountStatusArray, InvoiceEdit::tableName() . '.InvoiceType' => [2, 4], 'AmountType' => 1])->andWhere(InvoiceEdit::tableName() . '.CutOffDateHijri <\'' . $this->CutOffDateHijri . '\'')->sum('AmountApproved1');
                break;
            case 17:
            case 18:
            case 19:
            case 20:
            case 21:
                $res1 = \app\modules\invoice\models\InvoiceAmountEdit::find()->joinWith(['invoice'])->where([InvoiceEdit::tableName() . '.ContractID' => $this->ContractID, InvoiceEdit::tableName() . '.AmountStatus' => $amountStatusArray, InvoiceEdit::tableName() . '.InvoiceType' => $this->InvoiceType, 'AmountType' => 1, InvoiceAmountEdit::tableName() . '.Currency1' => $this->Currency1IDRef])->andWhere(InvoiceEdit::tableName() . '.CutOffDateHijri <\'' . $this->CutOffDateHijri . '\'')->sum('AmountApproved1');
                $res2 = \app\modules\invoice\models\InvoiceAmountEdit::find()->joinWith(['invoice'])->where([InvoiceEdit::tableName() . '.ContractID' => $this->ContractID, InvoiceEdit::tableName() . '.AmountStatus' => $amountStatusArray, InvoiceEdit::tableName() . '.InvoiceType' => $this->InvoiceType, 'AmountType' => 1, InvoiceAmountEdit::tableName() . '.Currency2' => $this->Currency1IDRef])->andWhere(InvoiceEdit::tableName() . '.CutOffDateHijri <\'' . $this->CutOffDateHijri . '\'')->sum('AmountApproved2');
                $res = $res1 + $res2;
                break;
        }
        return $res;
    }

    public function cumulativeApproved2AfterThisInvoice($amountStatus) {
        $invoiceTable = \app\modules\invoice\models\InvoiceEdit::tableName();
        $invoiceAmountTable = \app\modules\invoice\models\InvoiceAmountEdit::tableName();

        if (!($this->Currency2IDRef > 0)) {
            return null;
        }
        $amountStatusArray = [];
        switch ($amountStatus) {
            case 0:
                $amountStatusArray = [0];
                break;
            case 1:
                $amountStatusArray = [1];
                break;
            case 5:
                $amountStatusArray = [5];
                break;
            case 6:
                $amountStatusArray = [1, 5];
                break;
        }

        switch ($this->InvoiceType) {
            case 1:
            case 3:
            case 5:
                $res = \app\modules\invoice\models\InvoiceAmountEdit::find()->joinWith(['invoice'])->where(["$invoiceTable.ContractID" => $this->ContractID, "$invoiceTable.AmountStatus" => $amountStatusArray, "$invoiceTable.InvoiceType" => [1, 3, 5], 'AmountType' => 1])->andWhere("$invoiceTable.CutOffDateHijri <'" . $this->CutOffDateHijri . '\'')->sum('AmountApproved2');
                break;
            case 2:
            case 4:
                $res = \app\modules\invoice\models\InvoiceAmountEdit::find()->joinWith(['invoice'])->where(["$invoiceTable.ContractID" => $this->ContractID, "$invoiceTable.AmountStatus" => $amountStatusArray, "$invoiceTable.InvoiceType" => [2, 4], 'AmountType' => 1])->andWhere("$invoiceTable.CutOffDateHijri <'" . $this->CutOffDateHijri . '\'')->sum('AmountApproved2');
                break;
            case 17:
            case 18:
            case 19:
            case 20:
            case 21:
                $res1 = \app\modules\invoice\models\InvoiceAmountEdit::find()->joinWith(['invoice'])->where(["$invoiceTable.ContractID" => $this->ContractID, "$invoiceTable.AmountStatus" => $amountStatusArray, "$invoiceTable.InvoiceType" => $this->InvoiceType, 'AmountType' => 1, "$invoiceAmountTable.Currency1" => $this->Currency2IDRef])->andWhere("$invoiceTable.CutOffDateHijri <'" . $this->CutOffDateHijri . '\'')->sum('AmountApproved1');
                $res2 = \app\modules\invoice\models\InvoiceAmountEdit::find()->joinWith(['invoice'])->where(["$invoiceTable.ContractID" => $this->ContractID, "$invoiceTable.AmountStatus" => $amountStatusArray, "$invoiceTable.InvoiceType" => $this->InvoiceType, 'AmountType' => 1, "$invoiceAmountTable.Currency2" => $this->Currency2IDRef])->andWhere("$invoiceTable.CutOffDateHijri <'" . $this->CutOffDateHijri . '\'')->sum('AmountApproved2');
                $res = $res1 + $res2;
                break;
        }
        return $res;
    }
    public function getCreatorUser() {
    return $this->hasOne(\app\modules\account\models\User::className(), ['UserID' => 'UserIDRef1']);
}

}
