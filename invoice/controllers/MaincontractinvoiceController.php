<?php

namespace app\modules\invoice\controllers;

use Yii;
use yii\web\Controller;
use app\modules\invoice\models\InvoiceEdit;
use app\modules\invoice\models\InvoiceDocument;
use app\modules\workflow\models\ActionParam;
use app\modules\workflow\models\ProcessEntity;
use yii\helpers\Html;

class MaincontractinvoiceController extends Controller {

    public $defaultAction = "list";
    public $access = null;

    public function behaviors() {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['view', 'create', 'list', 'update'],
                'rules' => [
                    [
                        'actions' => [
                            'list',
                        ],
                        'roles' => ['@'],
                        'allow' => true,
                        'matchCallback' => function($rule, $action, $exteraData = []) {
                            return \app\access\AccessControl::isAccess([
                                        'or',
                                        ['obs', 'check' => 'isAdmin'],
                                        ['formAction', 'aid' => 149],
                            ]);
                        },
                    ],
                    [
                        'actions' => [
                            'create'
                        ],
                        'roles' => ['@'],
                        'allow' => true,
                        'matchCallback' => function($rule, $action, $exteraData = []) {
                            $cid = isset($exteraData['cid']) ? $exteraData['cid'] : Yii::$app->request->get('cid');
                            return \app\access\AccessControl::isAccess(['and',
                                        ['maincontract', 'cid' => $cid, 'accessType' => 'update'],
                                        ['processAction', 'aid' => 835],
                            ]);
                        },
                    ],
                    [
                        'actions' => [
                            'view'
                        ],
                        'roles' => ['@'],
                        'allow' => true,
                        'matchCallback' => function($rule, $action, $exteraData = []) {
                            $peid = isset($exteraData['peid']) ? $exteraData['peid'] : Yii::$app->request->get('peid');
                            return \app\access\AccessControl::isAccess([
                                'and',
                                ['maincontract', 'peid' => $peid, 'accessType' => 'view'],
                                ['formAction', 'aid' => 310]
                            ]);
                            return \app\access\AccessControl::maincontract(['peid' => $peid, 'accessType' => 'view'])
                                            ->isAccess();
                        },
                    ],
                    [
                        'actions' => [
                            'update'
                        ],
                        'roles' => ['@'],
                        'allow' => true,
                        'matchCallback' => function($rule, $action, $exteraData = []) {
                            $peid = isset($exteraData['peid']) ? $exteraData['peid'] : Yii::$app->request->get('peid');
                            $peid = Html::encode($peid);
                            $processEntityModel = \app\modules\workflow\models\ProcessEntity::find()
                                ->where(['ProcessEntityID' => $peid])
                                ->one();
                            return \app\access\AccessControl::isAccess([
                                /*'and',
                                ['maincontract', 'peid' => $peid, 'accessType' => 'update'],
                                ['formAction', 'aid' => 311]*/
                                'or',
                                ['formAction', 'aid' => 311],
                                ['obs', 'check' => 'isCandoAdmin'],
                                ['and',
                                    ['processEntity', 'peid' => $peid, 'type' => 'ToDo'],
                                    ['actionParam', 'aid' => $processEntityModel->LastActionIDRef, 'pt' => 'InvoiceAccessId', 'for' => 'EditInvoice'],
                                ],
                                ["and",
                                    ['obs', 'check' => 'isAdmin'],
                                    ['maincontract', 'peid' => $peid, 'accessType' => 'update'],
                                ],
                            ]);
                        },
                    ],
                ],
            ],
        ];
    }

    public function formFieldIsEnable($field, $actionParams) {
        foreach ($actionParams as $key => $row) {
            if ($key == $field) {
                if ($row['value2'] == 'true') {
                    return true;
                }
                return false;
            }
        }
        return false;
    }

    public function actionList() {
        $invoiceSearchModel = new \app\modules\invoice\models\InvoiceSearchMaincontract();
        $invoiceSearchModel->setScenario('search');
        $res = $invoiceSearchModel->search(Yii::$app->request->queryParams);
        $invoiceDataProvider = $res['dataProvider'];
        $totalInvoiceDataProvider = $res['totalDataProvider'];

        return $this->render('list', [
                    'invoiceSearchModel' => $invoiceSearchModel,
                    'invoiceDataProvider' => $invoiceDataProvider,
                    'totalInvoiceDataProvider' => $totalInvoiceDataProvider,
        ]);
    }

    public function actionView($peid) {

        $peid = \yii\helpers\Html::encode($peid);
        
        $processEntityModel = \app\modules\workflow\models\ProcessEntity::find()
                ->where(['ProcessEntityID'=>$peid])
                ->one();
        
        if (!$processEntityModel) {
            return $this->redirect(["/home/dashboard"]);
        }

        $invoiceModel = $processEntityModel->invoice;
        $invid = $processEntityModel->InvoiceIDRef;


        $contractInvoiceSearchModel = new \app\modules\invoice\models\InvoiceSearch();
        $contractInvoiceSearchModel->setScenario('search');
        $contractInvoiceDataProvider = $contractInvoiceSearchModel->searchContract(Yii::$app->request->queryParams, $invoiceModel->ContractID);
        $contractInvoiceDataProvider->sort->defaultOrder = ['CutOffDateHijri' => SORT_DESC];


        $invoice_amount_model = new \app\modules\invoice\models\InvoiceAmountSearch();
        $invoice_amount_model->setScenario('search');
        $invoiceAmountDataProvider = $invoice_amount_model->searchInvoice(Yii::$app->request->queryParams, $invid);


        //---------------------------------------------
        $invoiceDocumentModel = new \app\modules\invoice\models\InvoiceDocumentSearch();
        $invoiceDocumentModel->setScenario('search');
        $invoiceDocumentDataProvider = $invoiceDocumentModel->searchInvoice(Yii::$app->request->queryParams, $invid);
        //-------------------------------------------------
        //----------------------------------------
        $invoicePkskModel = new \app\modules\invoice\models\InvoicePkskSearch();
        $invoicePkskModel->setScenario('search');
        $invoicePkskDataProvider = $invoicePkskModel->searchInvoice(Yii::$app->request->queryParams, $invid);
        //----------------------------------------------------
        // contract revision
        //---------------------------------------------
        $contractRevisionModel = new \app\modules\contract\models\ContractRevisionSearch();
        $contractRevisionModel->setScenario('search');
        $contractRevisionDataProvider = $contractRevisionModel->searchContract(Yii::$app->request->queryParams, $invoiceModel->ContractID);
        //-------------------------------------------------
        // Contract Node Budget
        //-------------------------------------------------
        $contractNodeBudgetModel = new \app\modules\contract\models\ContractNodeBudgetSearch();
        $contractNodeBudgetModel->setScenario('search');
        $contractNodeBudgetProvider = $contractNodeBudgetModel->searchContract(Yii::$app->request->queryParams, $invoiceModel->ContractID);
        //-------------------------------------------------  
        // Contract Issue
        //-------------------------------------------------
        $contractIssueModel = new \app\modules\contract\models\ContractIssueSearch();
        $contractIssueModel->setScenario('search');
        $contractIssueProvider = $contractIssueModel->searchContract(Yii::$app->request->queryParams, $invoiceModel->ContractID);
        //-------------------------------------------------




        if (isset($invoiceModel->processEntity)) {//workflow
            $actionParamModel = \app\modules\workflow\models\ActionParam::find()
                    ->where(['actionIdRef' => $invoiceModel->processEntity->LastActionIDRef, 'paramTitle' => 'invoiceAccessId'])
                    ->one();
            if (!empty($actionParamModel)) {
                $access = array_map('intval', str_split($actionParamModel->value1));
            } else {
                $access = -1;
            }
        }
        //@TODO: to be delete
        $access = array_map('intval', str_split('333'));

//        else {//wowf notworkflow
//            if ($mode == 'wf') {
//                return $this->redirect(["/invoice/invoice/view", 'mode' => 'wowf', 'invid' => $invid]);
//            }
//        }
        //---------------
        //contract Amount Total
        $dataProviderContractAmount = \app\modules\contract\models\Contract::totalAmount($invoiceModel->ContractID, 'Approved Or Checking', $invoiceModel->CutOffDateHijri);
        $invoiceTypeArray = \yii\helpers\ArrayHelper::map(\app\components\ActiveModel::find('\app\modules\invoice\models\InvoiceTypes')
                ->asArray()
                ->all(), 'InvoiceTypeID', 'InvoiceType');

        $todoExists = \app\modules\workflow\models\ProcessEntity::find()->invoice('ToDo')
                ->andWhere(['InvoiceIDRef' => $invid])
                ->exists();

        return $this->render('view', [
                    'invoiceModel' => $invoiceModel,
                    'invoice_amount_model' => $invoice_amount_model,
                    'invoiceAmountDataProvider' => $invoiceAmountDataProvider,
                    'invoiceDocumentModel' => $invoiceDocumentModel,
                    'invoiceDocumentDataProvider' => $invoiceDocumentDataProvider,
                    'invoicePkskModel' => $invoicePkskModel,
                    'invoicePkskDataProvider' => $invoicePkskDataProvider,
                    'contractInvoiceDataProvider' => $contractInvoiceDataProvider,
                    'contractInvoiceSearchModel' => $contractInvoiceSearchModel,
                    'contractRevisionModel' => $contractRevisionModel,
                    'contractRevisionDataProvider' => $contractRevisionDataProvider,
                    'contractNodeBudgetModel' => $contractNodeBudgetModel,
                    'contractNodeBudgetProvider' => $contractNodeBudgetProvider,
                    'contractIssueModel' => $contractIssueModel,
                    'contractIssueProvider' => $contractIssueProvider,
                    'access' => empty($access) ? 'notworkflow' : $access,
                    'dataProviderContractAmount' => $dataProviderContractAmount,
                    'invoiceTypeArray' => $invoiceTypeArray,
                    'todoExists' => $todoExists,
        ]);
    }

    public function actionCreate($cid) {
        //@@ToDo کنترل شماره قرارداد مجاز برای درج صورت وضیعت
        $ip=Yii::$app->request->userIP;
        $parentInvoiceArray = \yii\helpers\ArrayHelper::map(InvoiceEdit::find()
                ->where(['ContractID' => $cid])
                ->asArray()
                ->all(), 'InvoiceID', 'Title');

        $invoiceEditModel = new InvoiceEdit();
        $invoiceEditModel->ContractID = $cid;
        $invoiceEditModel->hasChange = 1;

        $arrayCurrency = \yii\helpers\ArrayHelper::map(\app\modules\currency\models\Currency::find()
                ->asArray()
                ->all($db = \app\components\Utility::db()), 'CurrencyID', 'CurrencyTitle');
        if ($invoiceEditModel->load(Yii::$app->request->post())) {
            $params = Yii::$app->request->post();
            $invoiceEditModel->Currency1IDRef = $invoiceEditModel->contract->lastRider->Price1Unit;
            $invoiceEditModel->Currency2IDRef = $invoiceEditModel->contract->lastRider->Price2Unit;

            $invoiceEditModel->Currency1Title = \app\modules\currency\models\Currency::find()
                    ->where(['CurrencyID' => $invoiceEditModel->Currency1IDRef])
                    ->one($db = \app\components\Utility::db())->CurrencyTitle;
            if ($invoiceEditModel->Currency2IDRef > 0) {
                $invoiceEditModel->Currency2Title = \app\modules\currency\models\Currency::find()
                        ->where(['CurrencyID' => $invoiceEditModel->Currency2IDRef])
                        ->one($db = \app\components\Utility::db())->CurrencyTitle;
            }

            $uid = Yii::$app->session->get('UserID');
            $jdateClass = new \app\components\jDateTime(true, true, 'Asia/Tehran');
            $currentDateHijri = $jdateClass->date("Y/m/d-hi", false, false);
            $currentDate = $jdateClass->date("Y-m-d", false, false, false);

            $invoiceEditModel->UserIDRef2 = $uid;
            $invoiceEditModel->LastUpdateHijri = $currentDateHijri;
            $invoiceEditModel->InvoiceID = InvoiceEdit::find()->max('InvoiceID') + 1;
            $invoiceEditModel->AmountStatus = 1;
            $invoiceEditModel->UserIDRef1 = $uid;
            $invoiceEditModel->InsertDateHijri = $currentDateHijri;
            $invoiceEditModel->AmountStatus = 1;

            if ($invoiceEditModel->validate()) {
                $pid = 63;
                $lastActionId = \app\modules\workflow\models\Action::find()
                        ->where(['ProcessIDRef' => $pid, 'isStartAction' => 1])
                        ->one()->ActionID;
                $processEntityModel = new \app\modules\workflow\models\ProcessEntity();
                $processEntityModel->ProcessEntityID = $processEntityModel->find()
                        ->max('ProcessEntityID') + 1;
                $processEntityModel->ProcessIDRef = $pid;
                $processEntityModel->LastUserIDRef = $uid;
                $processEntityModel->LastActionIDRef = $lastActionId;
                $processEntityModel->LastActionDateHijri = $currentDateHijri;
                $processEntityModel->InvoiceIDRef = $invoiceEditModel->InvoiceID;
                $processEntityModel->Status = 1;

                $actionEntityModel = new \app\modules\workflow\models\ActionEntity();
                $actionEntityModel->ProcessEntityIDRef = $processEntityModel->ProcessEntityID;
                $actionEntityModel->FromActionIDRef = null;
                $actionEntityModel->ActionIDRef = $lastActionId;
                $actionEntityModel->ActionDate = $currentDate;
                $actionEntityModel->ActionDateHijri = $currentDateHijri;
                $actionEntityModel->UserIDRef = $uid;
                $actionEntityModel->OBSCategory3IDRef = Yii::$app->session->get('ObsPosition');
                $actionEntityModel->ipAddress = $ip;
                $actionEntityModel->ComputerName = \app\components\Utility::findComputerNameByIp($ip);
                
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if ($invoiceEditModel->save() && $processEntityModel->save() && $actionEntityModel->save()) {
                        $this->sendLog($invoiceEditModel->InvoiceID, "New");

                        $processEntityTable = \app\modules\workflow\models\ProcessEntity::tableName();
                        $activityManagement1 = new \app\components\ActivityManagement();
                        $actionType = "New";
                        $subType = "NewProcessEntity";
                        $subTypeDescription["TableName"] = $processEntityTable;
                        $subTypeDescription["RecordPrimaryKey"] = $processEntityModel->ProcessEntityID;
                        $activityManagement1->sendActitvityLog($actionType, $subType, $subTypeDescription);

                        $actionEntityTable = \app\modules\workflow\models\ActionEntity::tableName();
                        $activityManagement2 = new \app\components\ActivityManagement();
                        $subType = "NewActionEntity";
                        $subTypeDescription["TableName"] = $actionEntityTable;
                        $subTypeDescription["RecordPrimaryKey"] = $actionEntityModel->ID;
                        $activityManagement2->sendActitvityLog($actionType, $subType, $subTypeDescription);
                        
                        $transaction->commit();
                        return $this->redirect(['view', 'peid' => $invoiceEditModel->processEntity->ProcessEntityID]);
                    } else {
                        $transaction->rollBack();
                    }
                } catch (Exception $exc) {
                    $transaction->rollBack();
                }
            }
        }

        $invoiceEditModel->RevisionNumber = 0;

        $contractInvoiceSearchModel = new \app\modules\invoice\models\InvoiceSearch();
        $contractInvoiceSearchModel->setScenario('search');
        $contractInvoiceDataProvider = $contractInvoiceSearchModel->searchContract(Yii::$app->request->queryParams, $cid);
        $invoiceTypeArray = \yii\helpers\ArrayHelper::map(\app\modules\invoice\models\InvoiceTypes::find()->asArray()->all($db = \app\components\Utility::db()), 'InvoiceTypeID', 'InvoiceType');



        return $this->render('invoiceForm', [
                    'invoiceEditModel' => $invoiceEditModel,
                    'arrayCurrency' => $arrayCurrency,
                    'contractInvoiceDataProvider' => $contractInvoiceDataProvider,
                    'contractInvoiceSearchModel' => $contractInvoiceSearchModel,
                    'invoiceTypeArray' => $invoiceTypeArray,
                    'parentInvoiceArray' => $parentInvoiceArray,
                    'invoiceTypes' => $invoiceEditModel->getInvoiceType($invoiceEditModel->ContractID),
        ]);
    }

    public function actionUpdate($peid) {
        
        $peid = \yii\helpers\Html::encode($peid);
        
        $invid = ProcessEntity::find()
                ->where(['ProcessEntityID' => $peid])
                ->one()
                ->InvoiceIDRef;
        
        $invoiceEditModel = InvoiceEdit::find()
                ->where(['InvoiceID' => $invid])
                ->one();
        
        $parentInvoiceArray = \yii\helpers\ArrayHelper::map(InvoiceEdit::find()
                ->where(['ContractID' => $invoiceEditModel->ContractID])
                ->andWhere('InvoiceID<>' . $invid)
                ->asArray()
                ->all(), 'InvoiceID', 'Title');
        
        $arrayCurrency = \yii\helpers\ArrayHelper::map(\app\modules\currency\models\Currency::find()
                ->asArray()
                ->all($db = \app\components\Utility::db()), 'CurrencyID', 'CurrencyTitle');
        
        if ($invoiceEditModel->load(Yii::$app->request->post())) {
            $uid = Yii::$app->session->get('UserID');
            $jdateClass = new \app\components\jDateTime(true, true, 'Asia/Tehran');
            $currentDateHijri = $jdateClass->date("Y/m/d-hi", false, false);
            $currentDate = $jdateClass->date("Y-m-d", false, false, false);
            $invoiceEditModel->UserIDRef2 = $uid;
            $invoiceEditModel->LastUpdateHijri = $currentDateHijri;

            if ($invoiceEditModel->save()) {
                $this->sendLog($invoiceEditModel->InvoiceID, "Edit");
                return $this->redirect(['/invoice/maincontractinvoice/view',
                            'peid' => $invoiceEditModel->processEntity->ProcessEntityID,
                            '_tab' => 'InvoiceInfo',
                ]);
            }
        } else {

            $fieldEnabled = ActionParam::find()
                    ->where(['actionIdRef' => $invoiceEditModel->processEntity->LastActionIDRef, 'paramTitle' => 'fieldEnabled'])
                    ->all();
            return $this->renderAjax('_subInvoiceForm', [
                        'invoiceEditModel' => $invoiceEditModel,
                        'invoiceTypes' => $invoiceEditModel->getInvoiceType($invoiceEditModel->ContractID),
                        'formStatus' => 'edit',
                        'fieldEnabled' => empty($fieldEnabled) ? '' : $fieldEnabled,
                        'parentInvoiceArray' => $parentInvoiceArray,
                        'arrayCurrency' => $arrayCurrency,
            ]);
        }
    }

    public function sendLog($id, $type) {
        $invoiceDocumentTable = InvoiceDocument::tableName();
        $activityManagement = new \app\components\ActivityManagement();
        $actionType = $type;
        $subType = $type . "InvoiceDocument";
        $subTypeDescription["TableName"] = $invoiceDocumentTable;
        $subTypeDescription["RecordPrimaryKey"] = $id;
        $activityManagement->sendActitvityLog($actionType, $subType, $subTypeDescription);
    }
}
