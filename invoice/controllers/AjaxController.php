<?php

namespace app\modules\invoice\controllers;

use Yii;
use yii\web\Controller;
use app\modules\invoice\models\InvoiceAmountEdit;
use yii\helpers\Html;

class AjaxController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $defaultAction = null;
    public $access = null;

    function behaviors() {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['convert-invoice-amount', 'invoice-view-read-only'],
                'rules' => [
                    // allow authenticated users
                    [
                        'actions' => ['convert-invoice-amount'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['invoice-view-read-only'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function($rule, $action, $exteraData = []) {
                            $invid = isset($exteraData['invid']) ? $exteraData['invid'] : Yii::$app->request->get('invid');
                            return \app\access\AccessControl::isAccess([
                                        'contract',
                                        'accessType' => \app\access\ContractAccess::TYPE_INVOICE_LIST,
                                        'invid' => $invid
                            ]);
                        }
                    ],
                // everything else is denied
                ],
            ],
            'verbs' => [
                'class' => \yii\filters\VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionConvertInvoiceAmount($invid, $Id, $nodeId, $amountType, $amountTitle, $formStatus, $enteredValue, $ownerContractId = 0) {
        $invoiceTable = \app\modules\invoice\models\InvoiceEdit::tableName();
        $invoiceAmountTable = \app\modules\invoice\models\InvoiceAmountEdit::tableName();
        
        $nodeId = Html::encode($nodeId);
        $amountTitle = Html::encode($amountTitle);
        $Id = Html::encode($Id);

        $amountTitleBeforeChange = $amountTitle;
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $invoiceModel = \app\components\ActiveModel::find(\app\modules\invoice\models\InvoiceEdit::className())
                ->where(['InvoiceID'=>$invid])
                ->one();
        
        if (!in_array($amountTitle, array('requested1', 'requested2', 'approved1', 'approved2', 'income1', 'income2', 'requestedcum1', 'requestedcum2', 'approvedcum1', 'approvedcum2', 'incomecum1', 'incomecum2'))) {
            return FALSE;
        }

        $currency = substr($amountTitle, strlen($amountTitle) - 1, 1);

        if ($currency == '1') {
            $currencyId = $invoiceModel->Currency1IDRef;
        } else {
            $currencyId = $invoiceModel->Currency2IDRef;
        }


        (substr($amountTitle, strlen($amountTitle) - 4, 3) == 'cum') ? $isCumulative = true : $isCumulative = false;
        if ($isCumulative) {
            $amountTitle = substr($amountTitle, 0, strlen($amountTitle) - 4);
        } else {
            $amountTitle = substr($amountTitle, 0, strlen($amountTitle) - 1);
        }


        $sql = '';

        if ($amountTitle == 'income') {
            $res = \app\modules\invoice\models\InvoiceAmountEdit::find()
                    ->joinWith(['invoice'])
                    ->where(["$invoiceTable.ContractID" => $invoiceModel->ContractID, "$invoiceTable.AmountStatus" => [1, 5], "$invoiceTable.InvoiceType" => [1, 2, 3, 4, 5], 'AmountType' => 1])
                    ->andWhere("$invoiceTable.CutOffDateHijri <='" . $invoiceModel->CutOffDateHijri . "'")
                    ->andWhere(["$invoiceAmountTable.CostCode"=>$nodeId])
                    ->andWhere("$invoiceAmountTable.OwnerContractIDRef=$ownerContractId")
                    ->sum("Income" . $currency);
        } else {
            switch ($invoiceModel->InvoiceType) {
                case 1:
                case 3:
                case 5:
                    if (substr($amountTitle, 0, 3) == 'req') {
                        $res = \app\modules\invoice\models\InvoiceAmountEdit::find()
                                ->joinWith(['invoice'])
                                ->where(["$invoiceTable.ContractID" => $invoiceModel->ContractID, "$invoiceTable.AmountStatus" => [1, 5], "$invoiceTable.InvoiceType" => [1, 3, 5], 'AmountType' => 1])
                                ->andWhere("$invoiceTable.CutOffDateHijri <='" . $invoiceModel->CutOffDateHijri . "'")
                                ->andWhere(["$invoiceAmountTable.CostCode"=>$nodeId])
                                ->sum("AmountRequested" . $currency);
                        break;
                    } else {
                        $res = \app\modules\invoice\models\InvoiceAmountEdit::find()->joinWith(['invoice'])
                                ->where(["$invoiceTable.ContractID" => $invoiceModel->ContractID, "$invoiceTable.AmountStatus" => [1, 5], "$invoiceTable.InvoiceType" => [1, 3, 5], 'AmountType' => 1])
                                ->andWhere("$invoiceTable.CutOffDateHijri <='" . $invoiceModel->CutOffDateHijri . "'")
                                ->andWhere(["$invoiceAmountTable.CostCode"=>$nodeId])
                                ->sum("AmountApproved" . $currency);
                        break;
                    }
                case 2:
                case 4:
                    if (substr($amountTitle, 0, 3) == 'req') {
                        $res = \app\modules\invoice\models\InvoiceAmountEdit::find()
                                ->joinWith(['invoice'])
                                ->where(["$invoiceTable.ContractID" => $invoiceModel->ContractID, "$invoiceTable.AmountStatus" => [1, 5], "$invoiceTable.InvoiceType" => [2, 4], 'AmountType' => 1])
                                ->andWhere("$invoiceTable.CutOffDateHijri <='" . $invoiceModel->CutOffDateHijri . "'")
                                  ->andWhere(["$invoiceAmountTable.CostCode"=>$nodeId])
                                ->sum("AmountRequested" . $currency);
                        break;
                    } else {
                        $res = \app\modules\invoice\models\InvoiceAmountEdit::find()->joinWith(['invoice'])
                                ->where(["$invoiceTable.ContractID" => $invoiceModel->ContractID, "$invoiceTable.AmountStatus" => [1, 5], "$invoiceTable.InvoiceType" => [2, 4], 'AmountType' => 1])
                                ->andWhere("$invoiceTable.CutOffDateHijri <='" . $invoiceModel->CutOffDateHijri . "'")
                                   ->andWhere(["$invoiceAmountTable.CostCode"=>$nodeId])
                                ->sum("AmountApproved" . $currency);
                        break;
                    }

                case 17:
                case 18:
                case 19:
                case 20:
                case 21:
                    if (substr($amountTitle, 0, 3) == 'req') {
                        $res1 = \app\modules\invoice\models\InvoiceAmountEdit::find()
                                ->joinWith(['invoice'])
                                ->where(["$invoiceTable.ContractID" => $invoiceModel->ContractID, "$invoiceTable.AmountStatus" => [1, 5], "$invoiceTable.InvoiceType" => [1, 3, 5], 'AmountType' => 1])
                                ->andWhere("$invoiceTable.CutOffDateHijri <='" . $invoiceModel->CutOffDateHijri . "'")
                                    ->andWhere(["$invoiceAmountTable.CostCode"=>$nodeId])
                                ->andWhere(["$invoiceAmountTable.Currency1"=>$currencyId])
                                ->sum("AmountRequested1");
                        
                        $res2 = \app\modules\invoice\models\InvoiceAmountEdit::find()->joinWith(['invoice'])
                                ->where(["$invoiceTable.ContractID" => $invoiceModel->ContractID, "$invoiceTable.AmountStatus" => [1, 5], "$invoiceTable.InvoiceType" => [1, 3, 5], 'AmountType' => 1])
                                ->andWhere("$invoiceTable.CutOffDateHijri <='" . $invoiceModel->CutOffDateHijri . "'")
                                ->andWhere(["$invoiceAmountTable.CostCode"=>$nodeId])
                                ->andWhere(["$invoiceAmountTable.Currency2"=>$currencyId])
                                ->sum("AmountRequested2");
                        $res = $res1 + $res2;
                        break;
                    } else {
                        $res1 = \app\modules\invoice\models\InvoiceAmountEdit::find()->joinWith(['invoice'])
                                ->where(["$invoiceTable.ContractID" => $invoiceModel->ContractID, "$invoiceTable.AmountStatus" => [1, 5], "$invoiceTable.InvoiceType" => [1, 3, 5], 'AmountType' => 1])
                                ->andWhere("$invoiceTable.CutOffDateHijri <='" . $invoiceModel->CutOffDateHijri . "'")
                                ->andWhere(["$invoiceAmountTable.CostCode"=>$nodeId])
                                ->andWhere(["$invoiceAmountTable.Currency1"=>$currencyId])
                                ->sum("AmountApproved1");
                        $res2 = \app\modules\invoice\models\InvoiceAmountEdit::find()->joinWith(['invoice'])
                                ->where(["$invoiceTable.ContractID" => $invoiceModel->ContractID, "$invoiceTable.AmountStatus" => [1, 5], "$invoiceTable.InvoiceType" => [1, 3, 5], 'AmountType' => 1])
                                ->andWhere("$invoiceTable.CutOffDateHijri <='" . $invoiceModel->CutOffDateHijri . "'")
                                ->andWhere(["$invoiceAmountTable.CostCode"=>$nodeId])
                                ->andWhere(["$invoiceAmountTable.Currency2"=>$currencyId])
                                ->sum("AmountApproved2");
                        $res = $res1 + $res2;
                        break;
                    }
            }
        }

        if ($formStatus == 'new') {
            if ($isCumulative) {
                return $resault = $enteredValue - $res;
            } else {
                return $resault = $res + $enteredValue;
            }
        } elseif ($formStatus == 'edit') {
            $previousNodeId = Yii::$app->db->createCommand('SELECT CostCode FROM ' . InvoiceAmountEdit::tableName() . ' where ID=:id')
                    ->bindParam(':id',$Id)
                    ->queryScalar();
            if ($previousNodeId == $nodeId) {
                $invoiceAmountValue = InvoiceAmountEdit::find()
                        ->where(['ID'=>$Id])
                        ->one();    
                $amount = 0;
                switch ($amountTitleBeforeChange) {
                    case 'requested1':
                    case 'requestedcum1':
                        $amount = $invoiceAmountValue->AmountRequested1;
                        break;
                    case 'requested2':
                    case 'requestedcum2':
                        $amount = $invoiceAmountValue->AmountRequested2;
                        break;
                    case 'approved1':
                    case 'approvedcum1':
                        $amount = $invoiceAmountValue->AmountApproved1;
                        break;
                    case 'approved2':
                    case 'approvedcum2':
                        $amount = $invoiceAmountValue->AmountApproved2;
                        break;
                    case 'income1':
                    case 'incomecum1':
                        $amount = $invoiceAmountValue->Income1;
                        break;
                    case 'income2':
                    case 'incomecum2':
                        $amount = $invoiceAmountValue->Income2;
                        break;
                }

                if ($isCumulative) {
                    return $resault = $amount + $enteredValue - $res;
                } else {
                    return $resault = $res + $enteredValue - $amount;
                }
            } else {
                if ($isCumulative) {
                    return $resault = $enteredValue - $res;
                } else {
                    return $resault = $res - $enteredValue;
                }
            }
        }
    }

    public function actionInvoiceViewReadOnly($invid) {
        $invoiceModel = \app\components\ActiveModel::find('\app\modules\invoice\models\InvoiceEdit')
                        ->andWhere(['InvoiceID'=>$invid])->one();
        $invoiceAmountModel = new \app\modules\invoice\models\InvoiceAmountSearch();
        $invoiceAmountModel->setScenario('search');
        $invoiceAmountDataProvider = $invoiceAmountModel->searchInvoice(Yii::$app->request->queryParams, $invid);



        //---------------------------------------------
        $invoiceDocumentModel = new \app\modules\invoice\models\InvoiceDocumentSearch();
        $invoiceDocumentModel->setScenario('search');
        $invoiceDocumentDataProvider = $invoiceDocumentModel->searchInvoice(Yii::$app->request->queryParams, $invid);
        //---------------------------------------------
        $invoicePkskModel = new \app\modules\invoice\models\InvoicePkskSearch();
        $invoicePkskModel->setScenario('search');
        $invoicePkskDataProvider = $invoicePkskModel->searchInvoice(Yii::$app->request->queryParams, $invid);



        if (!empty($invoiceModel)) {
            return $this->renderAjax('invoiceViewReadOnly', [
                        'invoiceModel' => $invoiceModel,
                        'invoiceAmountModel' => $invoiceAmountModel,
                        'invoiceAmountDataProvider' => $invoiceAmountDataProvider,
                        'invoiceDocumentModel' => $invoiceDocumentModel,
                        'invoiceDocumentDataProvider' => $invoiceDocumentDataProvider,
                        'invoicePkskModel' => $invoicePkskModel,
                        'invoicePkskDataProvider' => $invoicePkskDataProvider
            ]);
        }
    }

}
