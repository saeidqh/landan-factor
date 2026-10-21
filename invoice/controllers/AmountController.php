<?php

namespace app\modules\invoice\controllers;

use Yii;
use yii\web\Controller;
use app\modules\workflow\models\ProcessEntity;
use app\modules\invoice\models\InvoiceAmountEdit;
use yii\helpers\Html;

class AmountController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $defaultAction = "";
    public $access = null;

    public function behaviors() {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['create', 'update', 'delete'],
                'rules' => [
                    // allow authenticated users
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function($rule, $action, $exteraData = []) {
                    $peid = isset($exteraData['peid']) ? $exteraData['peid'] : Yii::$app->request->get('peid');
                    $peid = Html::encode($peid);
                    
                    $processEntityModel = ProcessEntity::find()
                            ->where(['ProcessEntityID'=>$peid])
                            ->one();
                    return \app\access\AccessControl::isAccess([
                                'or',
                                [
                                    'and',
                                    ['processEntity', 'peid' => $peid, 'type' => 'ToDo'],
                                    ['actionParam', 'aid' => $processEntityModel->LastActionIDRef, 'pt' => 'InvoiceAccessId', 'for' => 'EditInvoiceAmount'],
                                   ],
                                ['obs', 'check' => 'isCandoAdmin'],   
                    ]);
                },
                    ],
                    [
                        'actions' => ['update'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function($rule, $action, $exteraData = []) {
                            $peid = isset($exteraData['peid']) ? $exteraData['peid'] : Yii::$app->request->get('peid');
                            $peid = Html::encode($peid);  
                            
                            $processEntityModel = ProcessEntity::find()
                            ->where(['ProcessEntityID'=>$peid])
                            ->one();
                              
                            return \app\access\AccessControl::isAccess([
                                'or',
                                [
                                    'and',
                                    ['processEntity', 'peid' => $peid, 'type' => 'ToDo'],
                                    ['actionParam', 'aid' => $processEntityModel->LastActionIDRef, 'pt' => 'InvoiceAccessId', 'for' => 'EditInvoiceAmount'],
                                ],
                                ['formAction', 'aid' => 379],
                                [
                                    'and',
                                    ['formAction', 'aid' => 384],
                                    ['Invoice', 'invoiceId' => $processEntityModel->invoice->InvoiceID]
                                ]
                            ]);
                        },
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function($rule, $action, $exteraData = []) {
                        $peid = isset($exteraData['peid']) ? $exteraData['peid'] : Yii::$app->request->get('peid');
                           $peid = Html::encode($peid);
                            $processEntityModel = ProcessEntity::find()
                            ->where(['ProcessEntityID'=>$peid])
                            ->one();
                        return \app\access\AccessControl::isAccess(
                                ['or',
                                    ['obs', 'check' => 'isCandoAdmin'],
                                    [
                                        'and',
                                        ['actionParam', 'aid' => $processEntityModel->LastActionIDRef, 'pt' => 'InvoiceAccessId', 'for' => 'EditInvoiceAmount'],
                                        ['processEntity', 'peid' => $peid, 'type' => 'ToDo']
                                    ]
                        ]);
                },
                    ],
                ],
            ],
        ];
    }

    public function actionCreate($peid) {
        $amountTypeArray = \yii\helpers\ArrayHelper::map(\app\modules\invoice\models\InvoiceAmountTypes::find()
                ->where(['<>', 'ID', 2])
                ->asArray()
                ->all($db = \app\components\Utility::db()), 'ID', 'InvoiceAmountType');
        $processEntityModel = \app\modules\workflow\models\ProcessEntity::find()
                ->where(['ProcessEntityID'=>$peid])
                ->one();

        $invoiceModel = $processEntityModel->invoice;
        $invoiceAmountModel = new InvoiceAmountEdit();
        $invoiceAmountModel->InvoiceID = $processEntityModel->InvoiceIDRef;
        $invoiceAmountModel->Currency1 = $invoiceModel->Currency1IDRef;
        $invoiceAmountModel->Currency2 = $invoiceModel->Currency2IDRef;
        $invoiceAmountModel->hasChange = 1;

        if ($invoiceAmountModel->load(Yii::$app->request->post()) && $invoiceAmountModel->validate()) {
            $res = $invoiceAmountModel->save();
            if ($res) {
                $this->sendLog($invoiceAmountModel->ID, "New");
                return \app\components\Utility::renderPjaxCallBack('invoiceAmountCallbackPjax', [
                    'result' => true,
                    'message' => 'insert Invoice Amount',
                ]);
//                Yii::$app->session->setFlash('success', 'insert Invoice Amount');
//                return 1;
            }
        }
        
        //uniqueNodeID fill
        
        $invoiceAmountModel->uniqueNodeID = $invoiceAmountModel->CostCode;
        $conNodeBudget = \app\modules\contract\models\ContractNodeBudget::find()
                ->invoice($invoiceAmountModel->invoice->contract->ContractID)
                ->all();
        $costCodeArray = [];
        for ($i = 0; $i < count($conNodeBudget); $i++) {
            $costCodeArray[$i] = [
                'NodeFullCode' => '|' . $conNodeBudget[$i]->NodeID
                . '|' . $conNodeBudget[$i]->costcode->NodeFullCode
                . '|' . $conNodeBudget[$i]->Strategy
                . '|' . $conNodeBudget[$i]->Description
                . '|' . $conNodeBudget[$i]->owner->ContractNo
                . '|' . $conNodeBudget[$i]->owner->subContractor->PersonTitle
                . '|' . $conNodeBudget[$i]->owner->ContractSubject
                . '|' . $conNodeBudget[$i]->OwnerContractIDRef
                . '|' . $conNodeBudget[$i]->costcode->level1
                . '|' . $conNodeBudget[$i]->costcode->level2
                . '|' . $conNodeBudget[$i]->costcode->level3
                . '|' . $conNodeBudget[$i]->costcode->level4
                . '|' . $conNodeBudget[$i]->costcode->level5
                . '|' . $conNodeBudget[$i]->costcode->level6
                . '|' . $conNodeBudget[$i]->costcode->level7
                . '|' . $conNodeBudget[$i]->costcode->level8,
                'NodeID' => $conNodeBudget[$i]->NodeID . $conNodeBudget[$i]->OwnerContractIDRef,
            ];
        }
        return $this->renderAjax('amountForm', [
                    'modelAmount' => $invoiceAmountModel,
                    'amountTypeArray' => $amountTypeArray,
                    'costCodeArray' => $costCodeArray,
                    'formState' => 'new',
        ]);
    }

    public function actionUpdate($peid, $invamid) {
        $invamid = Html::encode($invamid);
        $peid  = Html::encode($peid);
      
        $processEntityModel = \app\modules\workflow\models\ProcessEntity::find()
                ->where(['ProcessEntityID'=>$peid])
                ->one();

        $invoiceAmountModel = InvoiceAmountEdit::find()
                ->where(['ID'=>$invamid])
                ->one();
        
        if (!$invoiceAmountModel) {
            throw new \yii\web\HttpException("Invalid arguments", 404);
        }
        if ($invoiceAmountModel->InvoiceID != $processEntityModel->InvoiceIDRef) {
            throw new \yii\web\HttpException("Invalid arguments", 404);
        }
        $amountTypeArray = \yii\helpers\ArrayHelper::map(\app\modules\invoice\models\InvoiceAmountTypes::find()
                ->where(['<>', 'ID', 2])
                ->asArray()
                ->all($db = \app\components\Utility::db()), 'ID', 'InvoiceAmountType');

        $invoiceAmountModel->hasChange = 1;
        if ($invoiceAmountModel->load(Yii::$app->request->post()) && $invoiceAmountModel->validate()) {
            $res = $invoiceAmountModel->save();
            if ($res) {
                $this->sendLog($invoiceAmountModel->ID, "Edit");
                return \app\components\Utility::renderPjaxCallBack('invoiceAmountCallbackPjax', [
                    'result' => true,
                    'message' => 'Update Invoice Amount',
                ]);
            }
        }
        //uniqueNodeID fill
        $invoiceAmountModel->uniqueNodeID = $invoiceAmountModel->CostCode;
        $conNodeBudget = \app\modules\contract\models\ContractNodeBudget::find()
                ->invoice($invoiceAmountModel->invoice->contract->ContractID)
                ->all();
        $costCodeArray = [];
        for ($i = 0; $i < count($conNodeBudget); $i++) {
            $costCodeArray[$i] = [
                'NodeFullCode' => '|' .
                $conNodeBudget[$i]->NodeID
                . '|' . $conNodeBudget[$i]->costcode->NodeFullCode
                . '|' . $conNodeBudget[$i]->Strategy
                . '|' . $conNodeBudget[$i]->Description
                . '|' . $conNodeBudget[$i]->owner->ContractNo
                . '|' . $conNodeBudget[$i]->owner->subContractor->PersonTitle
                . '|' . $conNodeBudget[$i]->owner->ContractSubject
                . '|' . $conNodeBudget[$i]->OwnerContractIDRef
                . '|' . $conNodeBudget[$i]->costcode->level1
                . '|' . $conNodeBudget[$i]->costcode->level2
                . '|' . $conNodeBudget[$i]->costcode->level3
                . '|' . $conNodeBudget[$i]->costcode->level4
                . '|' . $conNodeBudget[$i]->costcode->level5
                . '|' . $conNodeBudget[$i]->costcode->level6
                . '|' . $conNodeBudget[$i]->costcode->level7
                . '|' . $conNodeBudget[$i]->costcode->level8,
                'NodeID' => $conNodeBudget[$i]->NodeID . $conNodeBudget[$i]->OwnerContractIDRef,
            ];
        }
        return $this->renderAjax('amountForm', [
                    'modelAmount' => $invoiceAmountModel,
                    'amountTypeArray' => $amountTypeArray,
                    'costCodeArray' => $costCodeArray,
                    'formState' => 'edit',
        ]);
    }

    /**
     * 
     * @param type $peid
     * @param type $aid amount id
     * @return type
     * @throws \yii\web\BadRequestHttpException
     * @throws \yii\web\HttpException
     */
    public function actionDelete($peid, $aid) {
        if (!Yii::$app->request->isAjax && !Yii::$app->request->isPjax) {
            throw new \yii\web\BadRequestHttpException();
        }
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $aid = Html::encode($aid);
        $peid = Html::encode($peid);
        
        $processEntityModel = \app\modules\workflow\models\ProcessEntity::find()
                ->where(['ProcessEntityID'=>$peid])
                ->one();
        
        $invoiceAmountModel = InvoiceAmountEdit::find()
                ->where(['ID'=>$aid])
                ->one();
        
        if (!$invoiceAmountModel || ($invoiceAmountModel->InvoiceID != $processEntityModel->InvoiceIDRef)) {
            throw new \yii\web\HttpException("Invalid arguments", 404);
        }

        $debitmemoAmountExists=  \app\modules\debitmemo\models\DebitmemoAmount::find()
                ->andWhere(['invoiceAmountIdRef'=>$aid])
                ->exists();
        if($debitmemoAmountExists){
            return [
                'result' => 0,
                'message' => "This invoice amount has Debitmemo and you can't Delete this amount."
            ];
        }

        $amountCount = \app\modules\invoice\models\InvoiceAmountEdit::find()
                ->where("InvoiceID=:aid")
                ->addParams([':aid'=>$aid])
                ->count();
        if ($amountCount == 1) {
            return [
                'result' => 0,
                'message' => 'This invoice has just one amount record and you can not delete it'
            ];
        }

        $res = InvoiceAmountEdit::deleteAll("ID=$aid");
        if ($res) {
            if (Yii::$app->params['autoReplicate']) {
                \app\modules\invoice\models\InvoiceAmountEditLocal::deleteAll("ID=$aid");
            }
            return [
                'result' => 1,
                'message' => 'Invoice Amount deleted successfully!!'
            ];
        } else {
            return [
                'result' => 0,
                'message' => 'Error in deleting invoice amount'
            ];
        }
    }

    public function sendLog($id, $type) {
        $invoiceAmountEditTable = InvoiceAmountEdit::tableName();
        $activityManagement = new \app\components\ActivityManagement();
        $actionType = $type;
        $subType = $type ."InvoiceAmountEdit";
        $subTypeDescription["TableName"] = $invoiceAmountEditTable;
        $subTypeDescription["RecordPrimaryKey"] = $id;
        $activityManagement->sendActitvityLog($actionType, $subType, $subTypeDescription);
    }
}
