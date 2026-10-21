<?php

namespace app\modules\invoice\controllers;

use Yii;
use yii\web\Controller;
use app\modules\invoice\models\InvoiceEdit;
use app\modules\invoice\models\InvoiceDocument;
use app\modules\workflow\models\ActionParam;
use app\components\jDateTime;
use app\modules\workflow\models\ProcessEntity;
use yii\helpers\Html;

/**
 * class Invoice Controller
 */
class InvoiceController extends Controller {

    public $defaultAction = "list";
    public $access = null;

    public function behaviors() {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['log', 'list', 'view', 'view-invoice-amount', 'view-invoice-document', 'view-contract-revision', 'view-contract-budget', 'view-contract-issue', 'view-contract-invoices', 'createform', 'create', 'delete', 'delete-confirm', 'update', 'import-invoice'],
                'rules' => [
                    // allow authenticated users
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action, $exteraData = []) {
                            $cid = isset($exteraData['cid']) ? $exteraData['cid'] : Yii::$app->request->get('cid');
                            $contractModel = \app\modules\contract\models\Contract::find()->where(['ContractID' => $cid])->one();
                            $HasCertainInvoice = \app\modules\invoice\models\InvoiceEdit::hasCertainInvoice($cid);
                            if ($contractModel->ContractStatusIDRef == 12 && $contractModel->ContractType != 15 && $HasCertainInvoice) {
                                return false;
                            } else {
                                return \app\access\AccessControl::isAccess(['or',
                                            ['maincontract', 'cid' => $cid, 'accessType' => 'createInnerZoneMainContractInvoice'],
                                            ['contract', 'cid' => $cid, 'accessType' => 'InvoiceList'],
                                ]);
                            }
                        },
                    ],
                    [
                        'actions' => [
                            'list',
                        ],
                        'roles' => ['@'],
                        'allow' => true,
                        'matchCallback' => function ($rule, $action, $exteraData = []) {
                            return \app\access\AccessControl::isAccess([
                                'or',
                                ['obs', 'check' => 'isAdmin'],
                                ['formAction', 'aid' => 132],
                            ]);
                        },
                    ],
                    [
                        'actions' => [
                            'log',
                        ],
                        'roles' => ['@'],
                        'allow' => true,
                        'matchCallback' => function ($rule, $action, $exteraData = []) {
                            $invid = isset($exteraData['invid']) ? $exteraData['invid'] : Yii::$app->request->get('invid');
                            return \app\access\AccessControl::contract(['invid' => $invid, 'accessType' => 'ContractList'])
                                    ->isAccess();
                        },
                    ],
                    [
                        'actions' => [
                            'view', 'view-invoice-amount', 'view-invoice-document', 'view-contract-revision', 'view-contract-budget', 'view-contract-issue', 'view-contract-invoices', 'createform',
                        ],
                        'roles' => ['@'],
                        'allow' => true,
                        'matchCallback' => function ($rule, $action, $exteraData = []) {
                            $peid = isset($exteraData['peid']) ? $exteraData['peid'] : Yii::$app->request->get('peid');
                            return \app\access\AccessControl::isAccess(['processEntity', 'peid' => $peid, 'type' => 'Graph']);
                        },
                    ],
                    [
                        'actions' => [
                            'update',
                        ],
                        'roles' => ['@'],
                        'allow' => true,
                        'matchCallback' => function ($rule, $action, $exteraData = []) {
                            $peid = isset($exteraData['peid']) ? $exteraData['peid'] : Yii::$app->request->get('peid');
                            $peid = Html::encode($peid);
                            $processEntityModel = \app\modules\workflow\models\ProcessEntity::find()
                                    ->where(['ProcessEntityID' => $peid])
                                    ->one();
                            $contractModel = $processEntityModel->invoice->contract;
                            $HasCertainInvoice = \app\modules\invoice\models\InvoiceEdit::hasCertainInvoice($contractModel->ContractID);
                            if ($contractModel->ContractStatusIDRef == 12 && $contractModel->ContractType != 15 && $HasCertainInvoice) {
                                return false;
                            } else {
                                $cid = $contractModel->ContractID;
                                return \app\access\AccessControl::isAccess([
                                            'or',
                                            ['and',
                                                ['processEntity', 'peid' => $peid, 'type' => 'ToDo'],
                                                ['actionParam', 'aid' => $processEntityModel->LastActionIDRef, 'pt' => 'InvoiceAccessId', 'for' => 'EditInvoice'],
                                            ],
                                            ['obs', 'check' => 'isCandoAdmin'],
                                            ["and",
                                                ['obs', 'check' => 'isAdmin'],
                                                ['contract', 'cid' => $cid, 'accessType' => 'ContractEdit'],
                                            ],
                                ]);
                            }
                        },
                    ],
                    [
                        'actions' => [
                            'delete', 'delete-confirm',
                        ],
                        'roles' => ['@'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action, $exteraData = []) {
                            return \app\access\AccessControl::isAccess([
                                'or',
                                ['formAction', 'aid' => 450],                                
                                ['obs', 'check' => 'isCandoAdmin']
                            ]);
                        },
                    ],
//                        'matchCallback' => function ($rule, $action, $exteraData = []) {
//                            return \app\access\AccessControl::isAccess(
//                                ['obs', 'check' => 'isCandoAdmin']
//                            );
//                            /*return \app\access\AccessControl::isAccess([
//                                'and',
//                                ['user', 'check' => 'isCandoManager'],
//                                ['obs', 'check' => 'isCandoAdmin']
//                            ]);*/
//                        },
//                    ],

                    [
                        'actions' => [
                            'import-invoice',
                        ],
                        'roles' => ['@'],
                        'allow' => true,
                        'matchCallback' => function ($rule, $action, $exteraData = []) {
                            return \app\access\AccessControl::isAccess([
                                'obs', 'check' => 'isCandoAdmin'
                            ]);
                        },
                    ],
                // everything else is denied
                ],
            ],
        ];
    }

    public function formFieldIsEnable($field, $actionParams) {
        foreach ($actionParams as $key => $row) {
            if ($row['value1'] == $field) {
                if ($row['value2'] == 'true') {
                    return true;
                }
            }
        }
        return false;
    }

    public function actionLog($invid) {
        $invoiceLogDataProvider = \app\modules\invoice\models\InvoiceLog::invoiceLogDataProvider($invid);
        $invoiceAmountLogDataProvider = \app\modules\invoice\models\InvoiceAmountLog::invoiceAmountLogDataProvider($invid);

        return $this->renderAjax('_InvoiceLog', [
                    'invoiceLogDataProvider' => $invoiceLogDataProvider,
                    'invoiceAmountLogDataProvider' => $invoiceAmountLogDataProvider,
                    'invid' => $invid,
                        ], false, true);
    }

    public function actionDeleteConfirm($invid) {
        if (!Yii::$app->request->isAjax && !Yii::$app->request->isPjax) {
            throw new \yii\web\BadRequestHttpException();
        }

        $invid = Html::encode($invid);

        $invoiceModel = \app\components\ActiveModel::find('\app\modules\invoice\models\InvoiceEdit')
                ->where(['InvoiceID' => $invid])
                ->one();

        if (!isset($invoiceModel)) {
            throw new \yii\web\HttpException("Invoice Not found", 404);
        }
        return $this->renderAjax('_deleteConfirm', [
                    'invoiceModel' => $invoiceModel,
        ]);
    }

    public function actionDelete($invid) {
        if (!Yii::$app->request->isAjax && !Yii::$app->request->isPjax) {
            throw new \yii\web\BadRequestHttpException();
        }
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $res = \app\components\CandoHelper::deleteInvoice($invid);
        if ($res['result']) {

            return [
                'result' => 1,
                'message' => $res['message'],
            ];
        } else {
            return [
                'result' => 0,
                'message' => $res['message'],
            ];
        }
    }

    public function actionList() {

        $invoiceSearchModel = new \app\modules\invoice\models\InvoiceSearch();
        $invoiceSearchModel->setScenario('search');
        $invoiceDataProvider = $invoiceSearchModel->search(Yii::$app->request->queryParams);

        $totalInvoiceDataProvider = $invoiceSearchModel->totalAmount(Yii::$app->request->queryParams);

        return $this->render('list', [
                    'invoiceSearchModel' => $invoiceSearchModel,
                    'invoiceDataProvider' => $invoiceDataProvider,
                    'partsAccess' => $this->access,
                    'totalInvoiceDataProvider' => empty($totalInvoiceDataProvider) ? null : $totalInvoiceDataProvider,
        ]);
    }

    public function actionView($peid) {

        $peid = Html::encode($peid);

        $processEntityModel = \app\modules\workflow\models\ProcessEntity::find()
                ->where(['ProcessEntityID' => $peid])
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

        $actionParamModel = \app\modules\workflow\models\ActionParam::find()
                ->where(['actionIdRef' => $processEntityModel->LastActionIDRef, 'paramTitle' => 'invoiceAccessId'])
                ->one();

        if (!empty($actionParamModel)) {
            $access = array_map('intval', str_split($actionParamModel->value1));
        } else {
            $access = -1;
        }

        //---------------
        //contract Amount Total
//        $dataProviderContractAmount = \app\modules\contract\models\Contract::totalAmount($invoiceModel->ContractID, 'Approved Or Checking', $invoiceModel->CutOffDateHijri);


        $todoExists = \app\modules\workflow\models\ProcessEntity::find()->invoice('ToDo')->andWhere(['InvoiceIDRef' => $invid])->exists();
        return $this->render('view', [
                    'processEntityModel' => $processEntityModel,
                    'invoiceModel' => $invoiceModel,
                    'contractInvoiceDataProvider' => $contractInvoiceDataProvider,
                    'contractInvoiceSearchModel' => $contractInvoiceSearchModel,
                    'access' => empty($access) ? 'notworkflow' : $access,
//                    'dataProviderContractAmount' => $dataProviderContractAmount,
                    'todoExists' => $todoExists,
                    'peid' => $peid,
        ]);
    }

    public function actionViewInvoiceAmount($peid) {

        $peid = Html::encode($peid);

        $processEntityModel = \app\modules\workflow\models\ProcessEntity::find()
                ->where(['ProcessEntityID' => $peid])
                ->one();

        if (!$processEntityModel) {
            return $this->redirect(["/home/dashboard"]);
        }
        $invoiceModel = $processEntityModel->invoice;
        $invid = $processEntityModel->InvoiceIDRef;

        $invoice_amount_model = new \app\modules\invoice\models\InvoiceAmountSearch();
        $invoice_amount_model->setScenario('search');
        $invoiceAmountDataProvider = $invoice_amount_model->searchInvoice(Yii::$app->request->queryParams, $invid);

        $actionParamModel = \app\modules\workflow\models\ActionParam::find()
                ->where(['actionIdRef' => $processEntityModel->LastActionIDRef, 'paramTitle' => 'invoiceAccessId'])
                ->one();

        if (!empty($actionParamModel)) {
            $access = array_map('intval', str_split($actionParamModel->value1));
        } else {
            $access = -1;
        }

        $todoExists = \app\modules\workflow\models\ProcessEntity::find()->invoice('ToDo')
                ->andWhere(['InvoiceIDRef' => $invid])
                ->exists();

        return $this->render('view_invoice_amount', [
                    'peid' => $peid,
                    'invoiceModel' => $invoiceModel,
                    'invoiceAmountDataProvider' => $invoiceAmountDataProvider,
                    'processEntityModel' => $processEntityModel,
                    'access' => $access,
                    'invoice_amount_model' => $invoice_amount_model,
                    'todoExists' => $todoExists,
        ]);
    }

    public function actionViewInvoiceDocument($peid) {
        $peid = Html::encode($peid);

        $processEntityModel = \app\modules\workflow\models\ProcessEntity::find()
                ->where(['ProcessEntityID' => $peid])
                ->one();

        if (!$processEntityModel) {
            return $this->redirect(["/home/dashboard"]);
        }
        $invoiceModel = $processEntityModel->invoice;
        $invid = $processEntityModel->InvoiceIDRef;

        //---------------------------------------------
        $invoiceDocumentModel = new \app\modules\invoice\models\InvoiceDocumentSearch();
        $invoiceDocumentModel->setScenario('search');
        $invoiceDocumentDataProvider = $invoiceDocumentModel->searchInvoice(Yii::$app->request->queryParams, $invid);
        //-------------------------------------------------
        //----------------------------------------
        $actionParamModel = \app\modules\workflow\models\ActionParam::find()->where(['actionIdRef' => $processEntityModel->LastActionIDRef, 'paramTitle' => 'invoiceAccessId'])->one();
        if (!empty($actionParamModel)) {
            $access = array_map('intval', str_split($actionParamModel->value1));
        } else {
            $access = -1;
        }
        $todoExists = \app\modules\workflow\models\ProcessEntity::find()->invoice('ToDo')->andWhere(['InvoiceIDRef' => $invid])->exists();
        return $this->render('view_invoice_document', [
                    'peid' => $peid,
                    'invoiceModel' => $invoiceModel,
                    'invoiceDocumentDataProvider' => $invoiceDocumentDataProvider,
                    'processEntityModel' => $processEntityModel,
                    'access' => $access,
                    'invoiceDocumentModel' => $invoiceDocumentModel,
                    'todoExists' => $todoExists,
        ]);
    }

    public function actionViewContractRevisions($peid) {

        $peid = Html::encode($peid);

        $processEntityModel = \app\modules\workflow\models\ProcessEntity::find()
                ->where(['ProcessEntityID' => $peid])
                ->one();

        if (!$processEntityModel) {
            return $this->redirect(["/home/dashboard"]);
        }
        $invoiceModel = $processEntityModel->invoice;
        $invid = $processEntityModel->InvoiceIDRef;

        //----------------------------------------------------
        // contract revision
        //---------------------------------------------
        $contractRevisionModel = new \app\modules\contract\models\ContractRevisionSearch();
        $contractRevisionModel->setScenario('search');
        $contractRevisionDataProvider = $contractRevisionModel->searchContract(Yii::$app->request->queryParams, $invoiceModel->ContractID);

        $actionParamModel = \app\modules\workflow\models\ActionParam::find()->where(['actionIdRef' => $processEntityModel->LastActionIDRef, 'paramTitle' => 'invoiceAccessId'])->one();
        if (!empty($actionParamModel)) {
            $access = array_map('intval', str_split($actionParamModel->value1));
        } else {
            $access = -1;
        }
        $todoExists = \app\modules\workflow\models\ProcessEntity::find()->invoice('ToDo')->andWhere(['InvoiceIDRef' => $invid])->exists();
        return $this->render('view_contract_revisions', [
                    'peid' => $peid,
                    'invoiceModel' => $invoiceModel,
                    'contractRevisionDataProvider' => $contractRevisionDataProvider,
                    'processEntityModel' => $processEntityModel,
                    'access' => $access,
                    'contractRevisionModel' => $contractRevisionModel,
                    'todoExists' => $todoExists,
        ]);
    }

    public function actionViewContractBudget($peid) {
        $peid = Html::encode($peid);
        $processEntityModel = \app\modules\workflow\models\ProcessEntity::find()
                ->where(['ProcessEntityID' => $peid])
                ->one();

        if (!$processEntityModel) {
            return $this->redirect(["/home/dashboard"]);
        }
        $invoiceModel = $processEntityModel->invoice;
        $invid = $processEntityModel->InvoiceIDRef;

        //-------------------------------------------------
        // Contract Node Budget
        //-------------------------------------------------
        $contractNodeBudgetModel = new \app\modules\contract\models\ContractNodeBudgetSearch();
        $contractNodeBudgetModel->setScenario('search');
        $contractNodeBudgetProvider = $contractNodeBudgetModel->searchContract(Yii::$app->request->queryParams, $invoiceModel->ContractID);

        $actionParamModel = \app\modules\workflow\models\ActionParam::find()->where(['actionIdRef' => $processEntityModel->LastActionIDRef, 'paramTitle' => 'invoiceAccessId'])->one();
        if (!empty($actionParamModel)) {
            $access = array_map('intval', str_split($actionParamModel->value1));
        } else {
            $access = -1;
        }
        $todoExists = \app\modules\workflow\models\ProcessEntity::find()->invoice('ToDo')->andWhere(['InvoiceIDRef' => $invid])->exists();
        return $this->render('view_contract_budget', [
                    'peid' => $peid,
                    'invoiceModel' => $invoiceModel,
                    'contractNodeBudgetProvider' => $contractNodeBudgetProvider,
                    'processEntityModel' => $processEntityModel,
                    'access' => $access,
                    'contractNodeBudgetModel' => $contractNodeBudgetModel,
                    'todoExists' => $todoExists,
        ]);
    }

    public function actionViewContractIssue($peid) {
        $peid = Html::encode($peid);

        $processEntityModel = \app\modules\workflow\models\ProcessEntity::find()
                ->where(['ProcessEntityID' => $peid])
                ->one();

        if (!$processEntityModel) {
            return $this->redirect(["/home/dashboard"]);
        }
        $invoiceModel = $processEntityModel->invoice;
        $invid = $processEntityModel->InvoiceIDRef;

        //-------------------------------------------------  
        // Contract Issue
        //-------------------------------------------------
        $contractIssueModel = new \app\modules\contract\models\ContractIssueSearch();
        $contractIssueModel->setScenario('search');
        $contractIssueProvider = $contractIssueModel->searchContract(Yii::$app->request->queryParams, $invoiceModel->ContractID);
        //-------------------------------------------------


        $actionParamModel = \app\modules\workflow\models\ActionParam::find()->where(['actionIdRef' => $processEntityModel->LastActionIDRef, 'paramTitle' => 'invoiceAccessId'])->one();
        if (!empty($actionParamModel)) {
            $access = array_map('intval', str_split($actionParamModel->value1));
        } else {
            $access = -1;
        }
        $todoExists = \app\modules\workflow\models\ProcessEntity::find()->invoice('ToDo')->andWhere(['InvoiceIDRef' => $invid])->exists();
        return $this->render('view_contract_issue', [
                    'peid' => $peid,
                    'invoiceModel' => $invoiceModel,
                    'contractIssueProvider' => $contractIssueProvider,
                    'processEntityModel' => $processEntityModel,
                    'access' => $access,
                    'contractIssueModel' => $contractIssueModel,
                    'todoExists' => $todoExists
        ]);
    }

    /**
     * 
     * @param integer $peid
     * @return type
     */
    public function actionViewContractInvoices($peid) {

        $peid = Html::encode($peid);

        $processEntityModel = \app\modules\workflow\models\ProcessEntity::find()
                ->where(['ProcessEntityID' => $peid])
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

        $actionParamModel = \app\modules\workflow\models\ActionParam::find()->where(['actionIdRef' => $processEntityModel->LastActionIDRef, 'paramTitle' => 'invoiceAccessId'])->one();
        if (!empty($actionParamModel)) {
            $access = array_map('intval', str_split($actionParamModel->value1));
        } else {
            $access = -1;
        }
        //---------------
        //contract Amount Total

        $invoiceTypeArray = \yii\helpers\ArrayHelper::map(\app\components\ActiveModel::find('\app\modules\invoice\models\InvoiceTypes')->asArray()->all(), 'InvoiceTypeID', 'InvoiceType');
        $todoExists = \app\modules\workflow\models\ProcessEntity::find()->invoice('ToDo')->andWhere(['InvoiceIDRef' => $invid])->exists();
        return $this->render('view_contract_invoices', [
                    'peid' => $peid,
                    'invoiceModel' => $invoiceModel,
                    'contractInvoiceDataProvider' => $contractInvoiceDataProvider,
                    'processEntityModel' => $processEntityModel,
                    'access' => $access,
                    'contractInvoiceSearchModel' => $contractInvoiceSearchModel,
                    'invoiceTypeArray' => $invoiceTypeArray,
                    'todoExists' => $todoExists,
        ]);
    }

    /**
     * 
     * @param type $peid
     * @return type
     */
    public function actionViewPkskInfo($peid) {

        $peid = Html::encode($peid);
        $processEntityModel = \app\modules\workflow\models\ProcessEntity::find()
                ->where(['ProcessEntityID' => $peid])
                ->one();

        if (!$processEntityModel) {
            return $this->redirect(["/home/dashboard"]);
        }
        $invoiceModel = $processEntityModel->invoice;
        $invid = $processEntityModel->InvoiceIDRef;
        //-------------------------------------------------
        //-----------invoice-pksks-search-model------------
        $invoicePkskModel = new \app\modules\invoice\models\InvoicePkskSearch();
        $invoicePkskModel->setScenario('search');
        $invoicePkskDataProvider = $invoicePkskModel->searchInvoice(Yii::$app->request->queryParams, $invid);

        $actionParamModel = \app\modules\workflow\models\ActionParam::find()->where(['actionIdRef' => $processEntityModel->LastActionIDRef, 'paramTitle' => 'invoiceAccessId'])->one();
        if (!empty($actionParamModel)) {
            $access = array_map('intval', str_split($actionParamModel->value1));
        } else {
            $access = -1;
        }
        $todoExists = \app\modules\workflow\models\ProcessEntity::find()->invoice('ToDo')->andWhere(['InvoiceIDRef' => $invid])->exists();
        return $this->render('view_pksks_info', [
                    'peid' => $peid,
                    'invoiceModel' => $invoiceModel,
                    'invoicePkskDataProvider' => $invoicePkskDataProvider,
                    'processEntityModel' => $processEntityModel,
                    'access' => $access,
                    'invoicePkskModel' => $invoicePkskModel,
                    'todoExists' => $todoExists
        ]);
    }

    /**
     * 
     * @param type $peid
     * @return type
     */
    public function actionViewPkskList($peid) {

        $peid = Html::encode($peid);
        $processEntityModel = \app\modules\workflow\models\ProcessEntity::find()
                ->where(['ProcessEntityID' => $peid])
                ->one();

        if (!$processEntityModel) {
            return $this->redirect(["/home/dashboard"]);
        }
        $invoiceModel = $processEntityModel->invoice;
        $invid = $processEntityModel->InvoiceIDRef;
        //-------------------------------------------------
        //-----------invoice-pksks-search-model------------
        $invoicePkskModel = new \app\modules\invoice\models\InvoicePkskSearch();
        $invoicePkskModel->setScenario('search');
        $pkskRelateddataProvider = $invoicePkskModel->searchContarct(Yii::$app->request->queryParams, $invoiceModel->ContractID);

        $actionParamModel = \app\modules\workflow\models\ActionParam::find()
                ->where(['actionIdRef' => $processEntityModel->LastActionIDRef, 'paramTitle' => 'invoiceAccessId'])
                ->one();
        if (!empty($actionParamModel)) {
            $access = array_map('intval', str_split($actionParamModel->value1));
        } else {
            $access = -1;
        }

        $todoExists = \app\modules\workflow\models\ProcessEntity::find()->invoice('ToDo')
                ->andWhere(['InvoiceIDRef' => $invid])
                ->exists();

        return $this->render('view_pksks_list', [
                    'peid' => $peid,
                    'invoiceModel' => $invoiceModel,
                    'pkskRelateddataProvider' => $pkskRelateddataProvider,
                    'processEntityModel' => $processEntityModel,
                    'access' => $access,
                    'invoicePkskModel' => $invoicePkskModel,
                    'todoExists' => $todoExists,
        ]);
    }

    public function actionCreate($cid) {
        //@@ToDo کنترل شماره قرارداد مجاز برای درج صورت وضیعت
        $parentInvoiceArray = \yii\helpers\ArrayHelper::map(InvoiceEdit::find()->where(['ContractID' => $cid])->asArray()->all(), 'InvoiceID', 'Title');

        $invoiceEditModel = new InvoiceEdit();
        $invoiceEditModel->ContractID = $cid;
        $invoiceEditModel->hasChange = 1;
        
        $currencyItems = \yii\helpers\ArrayHelper::map(\app\modules\currency\models\Currency::find()->asArray()->all($db = \app\components\Utility::db()), 'CurrencyID', 'CurrencyTitle');
        if ($invoiceEditModel->load(Yii::$app->request->post())) {
            if ($invoiceEditModel->InvoiceType == 3) { //امکان ثبت صورت وضعیت قطعی در صورت داشتن صورت وضعیت درجریان وجود ندارد
                $invoiceTable = InvoiceEdit::tableName();
                $processEntityTable = ProcessEntity::tableName();
                $actionTable = \app\modules\workflow\models\Action::tableName();
                $hasInProgressInvoice = InvoiceEdit::find()
                        ->innerJoin($processEntityTable, "$processEntityTable.InvoiceIDRef=$invoiceTable.InvoiceID")
                        ->innerJoin($actionTable, "$actionTable.ActionID=$processEntityTable.LastActionIDRef")
                        ->where(["$invoiceTable.ContractID" => $cid])
                        ->andWhere(["$actionTable.isEndAction" => 0])
                        ->exists();
                if ($hasInProgressInvoice) {
//                    Yii::$app->session->setFlash('You Can not Add Invoice. Contract has in progress invoice!!', 'error');
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return [
                        'result' => false,
                        'message' => 'قرارداد صورت وضعیت در جریان دارد و امکان درج  صورت وضعیت جدید وجود ندارد.',
                    ];
                }
            }
            if ($invoiceEditModel->InvoiceType < 6) {
                $invoiceEditModel->Currency1IDRef = $invoiceEditModel->contract->lastRider->Price1Unit;
                $invoiceEditModel->Currency2IDRef = $invoiceEditModel->contract->lastRider->Price2Unit;
            }

            $invoiceEditModel->Currency1Title = \app\modules\currency\models\Currency::find()->where(['CurrencyID' => $invoiceEditModel->Currency1IDRef])->one($db = \app\components\Utility::db())->CurrencyTitle;
            if ($invoiceEditModel->Currency2IDRef > 0) {
                $invoiceEditModel->Currency2Title = \app\modules\currency\models\Currency::find()->where(['CurrencyID' => $invoiceEditModel->Currency2IDRef])->one($db = \app\components\Utility::db())->CurrencyTitle;
            }
            $ip = Yii::$app->request->userIP;
            $uid = Yii::$app->session->get('UserID');
            $jdateClass = new jDateTime(true, true, 'Asia/Tehran');
            $currentDateHijri = $jdateClass->date("Y/m/d-hi", false, false);
            $currentDate = $jdateClass->date("Y-m-d", false, false, false);

            $invoiceEditModel->UserIDRef2 = $uid;
            $invoiceEditModel->UserIDRef1 = $uid;
            $invoiceEditModel->AmountStatus = 1;
            $invoiceEditModel->InvoiceID = InvoiceEdit::find()->max('InvoiceID') + 1;
            $invoiceEditModel->LastUpdateHijri = $currentDateHijri;
            $invoiceEditModel->InsertDateHijri = $currentDateHijri;

            if ($invoiceEditModel->validate()) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $typeProcessTable = \app\modules\workflow\models\ContractInvoiceTypeProcess::tableName();
                    $pid = \app\modules\workflow\models\ContractInvoiceTypeProcess::find()
                                    ->where(['ContractIDRef' => $invoiceEditModel->ContractID, 'InvoiceTypeIDRef' => $invoiceEditModel->InvoiceType])
                                    ->andWhere("$typeProcessTable .isDeleted is null or $typeProcessTable.isDeleted != 1")
                                    ->one()->ProcessIDRef;

                    $lastActionId = \app\modules\workflow\models\Action::find()
                                    ->where(['ProcessIDRef' => $pid, 'isStartAction' => 1])
                                    ->one()
                            ->ActionID;

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
                        return $this->redirect(['view', 'peid' => $processEntityModel->ProcessEntityID]);
                    } else {
                        $transaction->rollBack();
                    }
                } catch (\Exception $exc) {
                    $transaction->rollBack();
                }
            }
        }

        $invoiceEditModel->RevisionNumber = 0;

        $contractInvoiceSearchModel = new \app\modules\invoice\models\InvoiceSearch();
        $contractInvoiceSearchModel->setScenario('search');
        $contractInvoiceDataProvider = $contractInvoiceSearchModel->searchContract(Yii::$app->request->queryParams, $cid);
        $invoiceTypeArray = \yii\helpers\ArrayHelper::map(\app\modules\invoice\models\InvoiceTypes::find()
                                ->asArray()
                                ->all(), 'InvoiceTypeID', 'InvoiceType');
        $invoiceTypes = $invoiceEditModel->getInvoiceType($invoiceEditModel->ContractID);
        if ($invoiceTypes == null) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [
                'result' => false,
                'message' => 'You Can not Add Invoice In This Contract!!',
            ];
        }


        return $this->render('create', [
                    'invoiceEditModel' => $invoiceEditModel,
                    'invoiceTypes' => $invoiceTypes,
                    'currencyItems' => $currencyItems,
                    'contractInvoiceDataProvider' => $contractInvoiceDataProvider,
                    'contractInvoiceSearchModel' => $contractInvoiceSearchModel,
                    'invoiceTypeArray' => $invoiceTypeArray,
                    'parentInvoiceArray' => $parentInvoiceArray,
        ]);
    }

    public function actionUpdate($peid) {

        $peid = Html::encode($peid);
        $processEntityModel = \app\modules\workflow\models\ProcessEntity::find()
                ->where(['ProcessEntityID' => $peid])
                ->one();

        $invoiceEditModel = InvoiceEdit::find()
                ->where(['InvoiceID' => $processEntityModel->InvoiceIDRef])
                ->one();

        $parentInvoiceArray = \yii\helpers\ArrayHelper::map(InvoiceEdit::find()
                                ->where(['ContractID' => $invoiceEditModel->ContractID])
                                ->andWhere('InvoiceID<>' . $processEntityModel->InvoiceIDRef)->asArray()->all(), 'InvoiceID', 'Title');
        $currencyItems = \yii\helpers\ArrayHelper::map(\app\modules\currency\models\Currency::find()->asArray()
                                ->all($db = \app\components\Utility::db()), 'CurrencyID', 'CurrencyTitle');

        $invoiceTypeBeforeUpdate = $invoiceEditModel->InvoiceType;
        $invoiceEditModel->hasChange = 1;
        $formError = array();

        if ($invoiceEditModel->load(Yii::$app->request->post()) && $invoiceEditModel->validate()) {
            if ($invoiceEditModel->save()) {
                $this->sendLog($invoiceEditModel->InvoiceID, "New");

                Yii::$app->session->setFlash('success', 'Invoice edited successfully');
                return $this->redirect(['/invoice/invoice/view',
                            'peid' => $peid,
                            '_tab' => 'InvoiceInfo',
                ]);
            }
        }

        $fieldEnabled = ActionParam::find()
                ->where(['actionIdRef' => $invoiceEditModel->processEntity->LastActionIDRef, 'paramTitle' => 'fieldEnabled'])
                ->asArray()
                ->all();

        return $this->renderAjax('_subInvoiceForm', [
                    'invoiceEditModel' => $invoiceEditModel,
                    'invoiceTypes' => $invoiceEditModel->getInvoiceType($invoiceEditModel->ContractID),
                    'formError' => $formError,
                    'formStatus' => 'edit',
                    'fieldEnabled' => empty($fieldEnabled) ? '' : $fieldEnabled,
                    'parentInvoiceArray' => $parentInvoiceArray,
                    'currencyItems' => $currencyItems,
        ]);
    }

    public function actionCreateform($peid, $type = 'cr') {
        $peid = Html::encode($peid);
        $type = Html::encode($type);

        $actionEntityTable = \app\modules\workflow\models\ActionEntity::tableName();
        $processEntityTable = ProcessEntity::tableName();
        $invocieTable = InvoiceEdit::tableName();
        $invocieAmountTable = \app\modules\invoice\models\InvoiceAmountEdit::tableName();

        $userTable = \app\modules\account\models\User::tableName();

        $companyId = 387; // PGFK
        $pic_file = \app\components\Templates::getTemplate("logo", $companyId . ".png");
        if ($pic_file != "") {
            $file = file_get_contents($pic_file);
            $logo = "data:image/" . $type . ';base64,' . base64_encode($file);
        } else {
            $logo = "";
        }

        $companyIdAdmin = 606; // Cando Admin
        $pic_fileAdmin = \app\components\Templates::getTemplate("logo", $companyIdAdmin . ".png");
        if ($pic_fileAdmin != "") {
            $file_admin = file_get_contents($pic_fileAdmin);
            $logo_admin = "data:image/" . $type . ';base64,' . base64_encode($file_admin);
        } else {
            $logo_admin = "";
        }

        $processEntityModel = ProcessEntity::find()
                ->where(['ProcessEntityID' => $peid])
                ->one();

        $costcode = \app\modules\wbs\models\Costcodenew::tableName();
        $epsTable = \app\modules\wbs\models\Epsnew::tableName();

        $projectList = (new \yii\db\Query())->from($costcode)
                        ->innerJoin($invocieAmountTable, "$invocieAmountTable.CostCode=$costcode.NodeID")
                        ->innerJoin($epsTable, "$costcode.ProjectIDRef=$epsTable.EPSID")
                        ->andWhere(["$invocieAmountTable.InvoiceID" => $processEntityModel->InvoiceIDRef])
                        ->andWhere(["$epsTable.EPSType" => "Project"])
                        ->addSelect(["$epsTable.EPSTitle", "NodeID" => "left($costcode.NodeID,6)"])
                        ->groupBy(["$epsTable.EPSTitle", "left($costcode.NodeID,6)"])->all();
        $projectString = "";
        foreach ($projectList as $key => $value) {
            $projectString .= $value["EPSTitle"] . " # ";
        }
        $projectString = substr($projectString, 0, strlen($projectString) - 2);

        $activityManagement = new \app\components\ActivityManagement();
        $description = "پرینت فرم گردش کار صورت وضعیت";
        $activityManagement->sendCreateFormActitvityLog($description);

        if ($processEntityModel->ProcessIDRef == 57) {
            $sgn_non = $this->Signature($peid, [], []);
            $sgn_01 = $this->Signature($peid, [765], [847, 766]);
            $sgn_03 = $this->Signature($peid, [766], [768]);
//            $sgn_02 = $this->Signature($peid, [766], [847]);
            $sgn_02 = $this->Signature($peid, [847], [766, 765]);
            $sgn_04 = $this->Signature($peid, [768], [886, 769]);
            $sgn_05 = $this->Signature($peid, [769], [956]);
            if (empty($sgn_05['uid'])) {
                $sgn_05 = $this->Signature($peid, [769], [770, 773]);
            }
            $sgn_6 = $this->Signature($peid, [956], [770, 773]);
            $sgn_07 = $this->Signature($peid, [770], [772]);
            $sgn_08 = $this->Signature($peid, [772], [773]);
            $sgn_09 = $this->Signature($peid, [773], [774]);
            $sgn_10 = $this->Signature($peid, [774], [775]);
            $sgn_11 = $this->Signature($peid, [775], [777]);
            $sgn_12 = $this->Signature($peid, [776], [777]);
            $sgn_13 = $this->Signature($peid, [777], [778]);
            $sgn_14 = $this->Signature($peid, [886], [769]);

            $chechSign = $this->Signature($peid, [847], [768]);
            if (!empty($chechSign['uid'])) {
                $sgn_02 = $this->Signature($peid, [766], [847]);
                $sgn_03 = $this->Signature($peid, [847], [768]);
            }

            return $this->renderPartial('createForm_inv_57', [
                        'processEntityModel' => $processEntityModel,
                        'logo' => $logo,
                        'logo_admin' => $logo_admin,
                        'sgn_non' => $sgn_non,
                        'sgn_01' => $sgn_01,
                        'sgn_02' => $sgn_02,
                        'sgn_03' => $sgn_03,
                        'sgn_04' => $sgn_04,
                        'sgn_05' => $sgn_05,
                        'sgn_6' => $sgn_6,
                        'sgn_07' => $sgn_07,
                        'sgn_08' => $sgn_08,
                        'sgn_09' => $sgn_09,
                        'sgn_10' => $sgn_10,
                        'sgn_11' => $sgn_11,
                        'sgn_12' => $sgn_12,
                        'sgn_13' => $sgn_13,
                        'sgn_14' => $sgn_14,
                        'projectList' => $projectList,
                        'projectString' => $projectString,
            ]);
        } elseif ($processEntityModel->ProcessIDRef == 58) {
            $sgn_non = $this->Signature($peid, [], []);
            $sgn_01 = $this->Signature($peid, [779], [780]);
            $sgn_02 = $this->Signature($peid, [780], [782]);
            $sgn_03 = $this->Signature($peid, [782], [783, 786]);
            $sgn_04 = $this->Signature($peid, [783], [785]);
            $sgn_05 = $this->Signature($peid, [785], [786]);
            $sgn_06 = $this->Signature($peid, [786], [787]);
            $sgn_07 = $this->Signature($peid, [787], [789, 790]);
            $sgn_08 = $this->Signature($peid, [789], [790]);
            $sgn_09 = $this->Signature($peid, [788], [790]);
            $sgn_10 = $this->Signature($peid, [790], [791]);
            return $this->renderPartial('createForm_inv_58', [
                        'processEntityModel' => $processEntityModel,
                        'logo' => $logo,
                        'logo_admin' => $logo_admin,
                        'sgn_non' => $sgn_non,
                        'sgn_01' => $sgn_01,
                        'sgn_02' => $sgn_02,
                        'sgn_03' => $sgn_03,
                        'sgn_04' => $sgn_04,
                        'sgn_05' => $sgn_05,
                        'sgn_06' => $sgn_06,
                        'sgn_07' => $sgn_07,
                        'sgn_08' => $sgn_08,
                        'sgn_09' => $sgn_09,
                        'sgn_10' => $sgn_10,
                        'projectList' => $projectList,
                        'projectString' => $projectString,
            ]);
        } elseif ($processEntityModel->ProcessIDRef == 67) {
            $sgn_non = $this->Signature($peid, [], []);
            $sgn_01 = $this->Signature($peid, [887], [888]);
            $sgn_02 = $this->Signature($peid, [888], [890]);
            $sgn_03 = $this->Signature($peid, [890], [891, 892]);
            $sgn_04 = $this->Signature($peid, [891], [892]);
            $sgn_05 = $this->Signature($peid, [892], [893]);
            $sgn_06 = $this->Signature($peid, [893], [894]);
            $sgn_07 = $this->Signature($peid, [894], [895, 896]);
            $sgn_08 = $this->Signature($peid, [895], [896]);
            $sgn_09 = $this->Signature($peid, [896], [897]);

//            var_dump($sgn_01, $sgn_02, $sgn_03, $sgn_06);
//            die();
            return $this->renderPartial('createForm_inv_67', [
                        'processEntityModel' => $processEntityModel,
                        'logo' => $logo,
                        'logo_admin' => $logo_admin,
                        'sgn_non' => $sgn_non,
                        'sgn_01' => $sgn_01,
                        'sgn_02' => $sgn_02,
                        'sgn_03' => $sgn_03,
                        'sgn_04' => $sgn_04,
                        'sgn_05' => $sgn_05,
                        'sgn_06' => $sgn_06,
                        'sgn_07' => $sgn_07,
                        'sgn_08' => $sgn_08,
                        'sgn_09' => $sgn_09,
                        'projectList' => $projectList,
                        'projectString' => $projectString,
            ]);
        } elseif ($processEntityModel->ProcessIDRef == 25) {
            $companyId = 240; // PGFK
            $pic_file = \app\components\Templates::getTemplate("logo", $companyId . ".png");
            if ($pic_file != "") {
                $file = file_get_contents($pic_file);
                $logo = "data:image/" . $type . ';base64,' . base64_encode($file);
            } else {
                $logo = "";
            }

            $sgn_non = $this->SignatureCumulativeApproved($peid, [], []);
            $sgn_01 = $this->SignatureCumulativeApproved($peid, [495], [496]);
            $sgn_02 = $this->SignatureCumulativeApproved($peid, [496], [498]);
            $sgn_03 = $this->SignatureCumulativeApproved($peid, [500], [501]);
            $sgn_04 = $this->SignatureCumulativeApproved($peid, [501], [502]);
            $sgn_05 = $this->SignatureCumulativeApproved($peid, [899], [503]);
            $sgn_06 = $this->SignatureCumulativeApproved($peid, [503], [505]);

            $firstAction = \app\modules\workflow\models\ActionEntity::find()
                    ->andWhere(["ProcessEntityIDRef" => $processEntityModel->ProcessEntityID])
                    ->andWhere(["FromActionIDRef" => NULL])
                    ->one();
            return $this->renderPartial('createForm_inv_25', [
                        'processEntityModel' => $processEntityModel,
                        'firstAction' => $firstAction,
                        'logo' => $logo,
                        'logo_admin' => $logo_admin,
                        'sgn_non' => $sgn_non,
                        'sgn_01' => $sgn_01,
                        'sgn_02' => $sgn_02,
                        'sgn_03' => $sgn_03,
                        'sgn_04' => $sgn_04,
                        'sgn_05' => $sgn_05,
                        'sgn_06' => $sgn_06,
                        'projectList' => $projectList,
                        'projectString' => $projectString,
            ]);
        }
    }

    public function Signature($peid, $from, $to) {
        $processEntityTable = ProcessEntity::tableName();
        $actionEntityTable = \app\modules\workflow\models\ActionEntity::tableName();
        $userTable = \app\modules\account\models\User::tableName();
        $processEntityModel = (new \yii\db\Query())
                ->from($processEntityTable)
                ->innerJoin($actionEntityTable, "$actionEntityTable.ProcessEntityIDRef=$processEntityTable.ProcessEntityID")
                ->innerJoin($userTable, "$userTable.UserID=$actionEntityTable.UserIDRef")
                ->andWhere(["$actionEntityTable.ProcessEntityIDRef" => $peid])
                ->andFilterWhere(["in", 'FromActionIDRef', $from])
                ->andWhere(["in", 'ActionIDRef', $to])
                ->orderBy(["$actionEntityTable.ActionDateHijri" => SORT_DESC])
                ->one();

        $Currency1Title = 0;
        $Currency2Title = 0;
        $AmountApproved1 = 0;
        $AmountApproved2 = 0;

        if (isset($processEntityModel['UserIDRef'])) {
//            echo $processEntityModel->createCommand()->rawSql;
//            var_dump($processEntityModel);
//            die();
            $val = json_decode($processEntityModel['log']);
            $Currency1Title = isset($val->Currency1Title) ? $val->Currency1Title : NULL;
            $Currency2Title = isset($val->Currency2Title) ? $val->Currency2Title : NULL;
            $AmountApproved1 = isset($val->AmountApproved1) ? \app\components\Logger::getSumField($processEntityModel["ID"], "AmountApproved1") : NULL;
            $AmountApproved2 = isset($val->AmountApproved2) ? \app\components\Logger::getSumField($processEntityModel["ID"], "AmountApproved2") : NULL;
        }

        return [
            'uid' => $processEntityModel['UserIDRef'],
            'oid' => $processEntityModel['OBSCategory3IDRef'],
//            'UserName' => $processEntityModel['UserName'],
            'UserName' => $processEntityModel['FirstName'] . ' ' . $processEntityModel['LastName'],
            'ActionDateHijri' => $processEntityModel['ActionDateHijri'],
            'Currency1Title' => $Currency1Title,
            'Currency2Title' => $Currency2Title,
            'AmountApproved1' => $AmountApproved1,
            'AmountApproved2' => $AmountApproved2,
            'description' => $processEntityModel['ActionMessage'],
        ];
    }

    public function SignatureCumulativeApproved($peid, $from, $to) {
        $processEntityTable = ProcessEntity::tableName();
        $actionEntityTable = \app\modules\workflow\models\ActionEntity::tableName();
        $userTable = \app\modules\account\models\User::tableName();
        $processEntityModel = (new \yii\db\Query())
                ->from($processEntityTable)
                ->innerJoin($actionEntityTable, "$actionEntityTable.ProcessEntityIDRef=$processEntityTable.ProcessEntityID")
                ->innerJoin($userTable, "$userTable.UserID=$actionEntityTable.UserIDRef")
                ->andWhere(["$actionEntityTable.ProcessEntityIDRef" => $peid])
                ->andFilterWhere(["in", 'FromActionIDRef', $from])
                ->andWhere(["in", 'ActionIDRef', $to])
                ->orderBy(["$actionEntityTable.ActionDateHijri" => SORT_DESC])
                ->one();

        $Currency1Title = 0;
        $Currency2Title = 0;
        $AmountApproved1 = 0;
        $AmountApproved2 = 0;

        if (isset($processEntityModel['UserIDRef'])) {
            $val = json_decode($processEntityModel['log']);
            $Currency1Title = isset($val->Currency1Title) ? $val->Currency1Title : NULL;
            $Currency2Title = isset($val->Currency2Title) ? $val->Currency2Title : NULL;
            $invoiceModel = InvoiceEdit::find()
                    ->where(['InvoiceID' => $processEntityModel['InvoiceIDRef']])
                    ->one();
            $AmountApproved1 = floatval($invoiceModel->cumulativeApproved1AfterThisInvoice(6)) + floatval(\app\components\Logger::getSumField($processEntityModel["ID"], "AmountApproved1"));
            $AmountApproved2 = floatval($invoiceModel->cumulativeApproved2AfterThisInvoice(6)) + floatval(\app\components\Logger::getSumField($processEntityModel["ID"], "AmountApproved2"));
        }

        return [
            'uid' => $processEntityModel['UserIDRef'],
            'oid' => $processEntityModel['OBSCategory3IDRef'],
//            'UserName' => $processEntityModel['UserName'],
            'UserName' => $processEntityModel['FirstName'] . ' ' . $processEntityModel['LastName'],
            'ActionDateHijri' => $processEntityModel['ActionDateHijri'],
            'Currency1Title' => $Currency1Title,
            'Currency2Title' => $Currency2Title,
            'AmountApproved1Cumulative' => $AmountApproved1,
            'AmountApproved2Cumulative' => $AmountApproved2,
            'description' => $processEntityModel['ActionMessage'],
        ];
    }

    public function actionUsersignature($uid, $oid) {
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;

        //  کد قدیمی
//        $userObs = \app\modules\account\models\UserObs::findOne(['UserIDRef' => $uid, 'OBSCategory3IDRef' => $oid]);
//        //  کد جدید
        $userObsTable = \app\modules\account\models\UserObs::tableName();
        $userObs = \app\modules\account\models\UserObs::find()->where(['UserIDRef' => $uid, 'OBSCategory3IDRef' => $oid])->andWhere("($userObsTable.isDeleted is null or $userObsTable.isDeleted<>1)")->one();

        //loadImage تابع 
        //درپوشه DocumentTraitPro در کلاس 
        // components  قرار دارد
        $userObs->loadImage();
        exit();
    }

    public function actionImportInvoice() {
        var_dump("code is comment!!");
        die();
        $useXlsxFormat = true;
        $xlBook = new \ExcelBook(Yii::$app->params['libxlKey']['key'], Yii::$app->params['libxlKey']['pass'], $useXlsxFormat);
        $xlBook->setLocale('UTF-8');
        $xlBook->loadFile(\app\components\Templates::getTemplate('excel', 'ImportInvoiceTamplatePKSK.xlsx'));

        $s = $xlBook->getSheet(0);
        $row = 2139;
//        $row = 2;
        $result = true;

        $ip = Yii::$app->request->userIP;
        $jdateClass = new jDateTime(true, true, 'Asia/Tehran');
        $currentDateHijri = $jdateClass->date("Y/m/d-hi", false, false);
        $currentDate = $jdateClass->date("Y-m-d", false, false, false);

        /* $path = Yii::getAlias('@candoDocs/temp/');

          for ($i = 1; $i <= $row; $i++) {
          $tempfileName = $s->read($i, 13);
          $tempfilePath = $path . $tempfileName;
          if (file_exists($tempfilePath) && $s->read($i, 17) != 2) {
          $filePath = $path . 'invoice/' . $tempfileName;
          $res = copy($tempfilePath, $filePath);
          }
          }

          var_dump("99");
          die(); */
        $transaction = Yii::$app->db->beginTransaction();
//        $transaction = Yii::$app->dbTehran->beginTransaction();
        try {
            for ($i = 1; $i <= $row; $i++) {
                /* $cutoffdatehijri = explode('/', $s->read($i, 3));
                  if (is_array($cutoffdatehijri) && count($cutoffdatehijri) == 3) {
                  $year = $cutoffdatehijri[0];
                  $month = $cutoffdatehijri[1];
                  $day = $cutoffdatehijri[2];
                  }
                  $cutoffdateMiladi = jDateTime::toGregorian($year, $month, $day);
                  $cutoffdateMiladi = $cutoffdateMiladi[0] . '-' . $cutoffdateMiladi[1] . '-' . $cutoffdateMiladi[2] . ' ' . '00:00:00'; */
                $amountNumber = $s->read($i, 17);
                if ($amountNumber == 2) {
                    $invoiceEditModel = InvoiceEdit::find()
                            ->where([
                                'ContractID' => $s->read($i, 0),
                                'Title' => strval($s->read($i, 2)),
                            ])
                            ->andWhere(['!=', 'AmountStatus', '0'])
                            ->andWhere(['InvoiceType' => $s->read($i, 1)])
                            ->one();
                } else {
                    $invoiceEditModel = new InvoiceEdit();
                    $invoiceEditModel->RevisionNumber = 0;
                    $invoiceEditModel->hasChange = 1;
                    $invoiceEditModel->ContractID = $s->read($i, 0);
                    $invoiceEditModel->InvoiceType = $s->read($i, 1);
                    $invoiceEditModel->Title = strval($s->read($i, 2));
                    $invoiceEditModel->CutOffDateHijri = strval($s->read($i, 3));
//                    $invoiceEditModel->CutOffDate = $cutoffdateMiladi;
                    $invoiceEditModel->CutOffDate = strval($s->read($i, 4));
//                    $invoiceEditModel->Description = strval($s->read($i, 5));
                    $invoiceEditModel->Description = "ثبت شده توسط مدیر سیستم بنا به درخواست شرکت پتروگوهر.";

                    if ($invoiceEditModel->InvoiceType < 6 && isset($invoiceEditModel->contract->lastRider)) {
                        $invoiceEditModel->Currency1IDRef = $invoiceEditModel->contract->lastRider->Price1Unit;
                        $invoiceEditModel->Currency2IDRef = $invoiceEditModel->contract->lastRider->Price2Unit;
                    } else {
                        $invoiceEditModel->Currency1IDRef = $s->read($i, 9);
                        $invoiceEditModel->Currency2IDRef = $s->read($i, 12);
                    }

                    if ($invoiceEditModel->Currency1IDRef > 0) {
                        $invoiceEditModel->Currency1Title = \app\modules\currency\models\Currency::find()->where(['CurrencyID' => $invoiceEditModel->Currency1IDRef])->one($db = \app\components\Utility::db())->CurrencyTitle;
                    } else {
                        var_dump($row);
                    }
                    if ($invoiceEditModel->Currency2IDRef > 0) {
                        $invoiceEditModel->Currency2Title = \app\modules\currency\models\Currency::find()->where(['CurrencyID' => $invoiceEditModel->Currency2IDRef])->one($db = \app\components\Utility::db())->CurrencyTitle;
                    }

                    $invoiceEditModel->InvoiceID = InvoiceEdit::find()->max('InvoiceID') + 1;
                    $invoiceEditModel->UserIDRef1 = 367;
                    $invoiceEditModel->UserIDRef2 = 367;
                    $invoiceEditModel->LastUpdateHijri = $currentDateHijri;
                    $invoiceEditModel->InsertDateHijri = $currentDateHijri;
                    $invoiceEditModel->AmountStatus = 1;

                    if ($invoiceEditModel->validate()) {
                        if ($invoiceEditModel->save()) {
                            
                        } else {
                            $result = false;
                            echo 'row=' . $i;
                        }
                    } else {
                        $result = false;
                        echo 'row=' . $i;
                        var_dump($invoiceEditModel->errors);
                        break;
                    }
                }
                /* $typeProcessTable = \app\modules\workflow\models\ContractInvoiceTypeProcess::tableName();
                  $pid = \app\modules\workflow\models\ContractInvoiceTypeProcess::find()
                  ->where(['ContractIDRef' => $invoiceEditModel->ContractID, 'InvoiceTypeIDRef' => $invoiceEditModel->InvoiceType])
                  ->andWhere("$typeProcessTable .isDeleted is null or $typeProcessTable.isDeleted != 1")
                  ->one()->ProcessIDRef;

                  $lastActionId = \app\modules\workflow\models\Action::find()->where(['ProcessIDRef' => $pid, 'isStartAction' => 1])->one()->ActionID; */
                $lastActionId = \app\modules\workflow\models\Action::find()
                                ->where(['ProcessIDRef' => 59, 'isStartAction' => 1])
                                ->one()
                        ->ActionID;

                $processEntityModel = new \app\modules\workflow\models\ProcessEntity();
                $processEntityModel->ProcessEntityID = $processEntityModel->find()
                                ->max('ProcessEntityID') + 1;
//                    $processEntityModel->ProcessIDRef = $pid;
                $processEntityModel->ProcessIDRef = 59;
                $processEntityModel->LastUserIDRef = 367;
                $processEntityModel->LastActionIDRef = $lastActionId;
                $processEntityModel->LastActionDateHijri = $currentDateHijri;
                $processEntityModel->InvoiceIDRef = $invoiceEditModel->InvoiceID;
                $processEntityModel->Status = 1;

                $actionEntityModel = new \app\modules\workflow\models\ActionEntity();
                $actionEntityModel->ID = \app\modules\workflow\models\ActionEntity::find()
                                ->max('ID') + 1;
                $actionEntityModel->ProcessEntityIDRef = $processEntityModel->ProcessEntityID;
                $actionEntityModel->FromActionIDRef = null;
                $actionEntityModel->ActionIDRef = $lastActionId;
                $actionEntityModel->ActionDate = $currentDate;
                $actionEntityModel->ActionDateHijri = $currentDateHijri;
                $actionEntityModel->UserIDRef = 367;
                $actionEntityModel->OBSCategory3IDRef = Yii::$app->session->get('ObsPosition');
                $actionEntityModel->ipAddress = $ip;
                $actionEntityModel->ComputerName = \app\components\Utility::findComputerNameByIp($ip);
                if ($processEntityModel->save() && $actionEntityModel->save()) {
                    $invoiceAmountModel = new \app\modules\invoice\models\InvoiceAmountEdit();
                    $invoiceAmountModel->ID = \app\modules\invoice\models\InvoiceAmountEdit::find()->max('ID') + 1;
                    $invoiceAmountModel->InvoiceID = $invoiceEditModel->InvoiceID;
                    $invoiceAmountModel->Currency1 = $invoiceEditModel->Currency1IDRef;
                    $invoiceAmountModel->Currency2 = $invoiceEditModel->Currency2IDRef;
                    $invoiceAmountModel->CostCode = strval($s->read($i, 16));
                    $invoiceAmountModel->uniqueNodeID = $invoiceAmountModel->CostCode;
                    $invoiceAmountModel->AmountRequested1 = strval($s->read($i, 7));
                    $invoiceAmountModel->AmountApproved1 = strval($s->read($i, 8));
                    if ($s->read($i, 10) != '' && $s->read($i, 10) != null) {
                        $invoiceAmountModel->AmountRequested2 = strval($s->read($i, 10));
                    }
                    if ($s->read($i, 11) != '' && $s->read($i, 11) != null) {
                        $invoiceAmountModel->AmountApproved2 = strval($s->read($i, 11));
                    }
                    $invoiceAmountModel->AmountType = $s->read($i, 6); //cost
                    $invoiceAmountModel->hasChange = 1;

                    if ($invoiceAmountModel->save()) {
                        $tempfileName = $s->read($i, 13);
                        $tempfilePath = Yii::getAlias('@candoDocs/temp/invoice/' . $tempfileName);
                        if (file_exists($tempfilePath) && $s->read($i, 17) != 2) {
                            $invoiceDocumentModel = new \app\modules\invoice\models\InvoiceDocument();
//                            $invoiceDocumentModel->setScenario('add');
                            $invoiceDocumentModel->InvoiceDocumentID = InvoiceDocument::find()->max('InvoiceDocumentID') + 1;
                            $invoiceDocumentModel->InvoiceIDref = $invoiceEditModel->InvoiceID;
                            $invoiceDocumentModel->UserIDRef = 367;  // zahedi user id
                            $invoiceDocumentModel->FileName = strval($s->read($i, 13));
                            $invoiceDocumentModel->InvoiceDocumentTypeIDref = 10; // pdf type
                            $invoiceDocumentModel->hasChange = 1;
                            $invoiceDocumentModel->InsertDateHijri = $currentDateHijri;
                            $invoiceDocumentModel->InvoiceActionIDRef = $processEntityModel->LastActionIDRef;

                            if ($invoiceDocumentModel->save()) {
                                $candoDocPath = Yii::getAlias('@candoDocs');
                                $mainDir = 'invoice';
                                //برگرداندن پوشه اصلی آپلود فایل ها بدون پوشه 
                                // تقسیم بندی هزارتایی
                                $subDir = '00001';
                                $folderNum = ceil($invoiceDocumentModel->InvoiceDocumentID / 1000);
                                $subDir = $folderNum . '';
                                $subDirLen = strlen($subDir);
                                for ($j = 0; $j < (5 - $subDirLen ); $j++) {
                                    $subDir = '0' . $subDir;
                                }
                                $path = $candoDocPath . DIRECTORY_SEPARATOR . $mainDir . DIRECTORY_SEPARATOR . $subDir;
                                if (!file_exists($path)) {
                                    mkdir($path);
                                    $handle = fopen($path . DIRECTORY_SEPARATOR . 'index.php', 'w');
                                    fclose($handle);
                                }
                                $time = microtime(true);
                                $dFormat = "Ymd.Gis";
                                $mSecs = $time - floor($time);
                                $mSecs = substr($mSecs, 2);
                                $uniqDate = sprintf('%s%s', date($dFormat), $mSecs);
                                $fileName = $invoiceDocumentModel->InvoiceDocumentID;

                                $filePath = $path . DIRECTORY_SEPARATOR . $fileName . '.pdf';
                                // حذف مجازی فایل قبلی در صورت وجود
                                if (file_exists($filePath)) {
                                    $deletFileName = $path . DIRECTORY_SEPARATOR . 'del-' . $fileName . '-' . $uniqDate . '.pdf';
                                    rename($filePath, $deletFileName);
                                }
                                $res = copy($tempfilePath, $filePath);
                                var_dump($filePath);
                                if ($res) {
                                    var_dump("OK");
                                } else {
                                    $result = false;
                                    var_dump("Error copy document");
//                                        break;
                                }
                            } else {
                                $result = false;
                                echo 'row=' . $i;
                                var_dump("Error saving document");
//                                    break;
                            }
                        }
                    } else {
                        $result = false;
                        echo 'row=' . $i;
                        var_dump($invoiceAmountModel->errors);
                        var_dump($s->read($i, 0));
                        var_dump($invoiceAmountModel->invoice->contract->ContractType);
                        var_dump($invoiceAmountModel->invoice->InvoiceID);
//                        var_dump("Error");
//                        break;
                    }
                } else {
                    $result = false;
                    var_dump("Error on saving proccessEntity or actionEntity");
//                    break;
                }
            }
        } catch (Exception $exc) {
            $result = false;
            var_dump("Error");
//            break;
        }
        if ($result == true) {
            var_dump("success");
            $transaction->commit();
        } else {
            var_dump("error");
            $transaction->rollBack();
        }

        die();
        exit();
    }

    public function actionImportInvoice2() {
        var_dump("code is comment??");
        die();

        $workflowContractTable = \app\modules\workflowContract\models\WorkflowContract::tableName();
        $workflowContract = \app\modules\workflowContract\models\WorkflowContract::find()
                ->where(['SubContractorPersonIDRef' => null])
                ->andFilterWhere(['LIKE', 'SubContractorPersonTitle', '"SubContractorPersonTitle":'])
                ->all();

        foreach ($workflowContract as $model) {
            if (\app\components\Utility::isJson($model->SubContractorPersonTitle)) {

                $val = json_decode($model->SubContractorPersonTitle);
                $val = json_encode($val, JSON_UNESCAPED_UNICODE);

                $sql = "update $workflowContractTable set SubContractorPersonTitle='$val' where WorkflowContractID=$model->WorkflowContractID";
                Yii::$app->dbTehran->createCommand($sql)->execute();
            }
        }
        var_dump("success!!");
        die();
    }

    public function sendLog($id, $type) {
        $invoiceEditTable = InvoiceEdit::tableName();
        $activityManagement = new \app\components\ActivityManagement();
        $actionType = $type;
        $subType = $type . "InvoiceEdit";
        $subTypeDescription["TableName"] = $invoiceEditTable;
        $subTypeDescription["RecordPrimaryKey"] = $id;
        $activityManagement->sendActitvityLog($actionType, $subType, $subTypeDescription);
    }

}
