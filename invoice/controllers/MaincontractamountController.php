<?php

namespace app\modules\invoice\controllers;

use Yii;
use yii\web\Controller;
use app\modules\invoice\models\InvoiceEdit;
use app\modules\invoice\models\InvoiceAmountEdit;
use app\modules\workflow\models\ProcessEntity;
use yii\helpers\Html;

class MaincontractamountController extends Controller {

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
                            return \app\access\AccessControl::processEntity(['peid' => $peid, 'type' => 'ToDo'])
                                            ->isAccess();
                        },
                    ],
                    [
                        'actions' => ['update'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function($rule, $action, $exteraData = []) {
                            $peid = isset($exteraData['peid']) ? $exteraData['peid'] : Yii::$app->request->get('peid');
                            return \app\access\AccessControl::processEntity(['peid' => $peid, 'type' => 'ToDo'])
                                            ->isAccess();
                        },
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function($rule, $action, $exteraData = []) {
                            $peid = isset($exteraData['peid']) ? $exteraData['peid'] : Yii::$app->request->get('peid');
                            return \app\access\AccessControl::processEntity(['peid' => $peid, 'type' => 'ToDo'])
                                            ->isAccess();
                        },
                    ],
                ],
            ],
        ];
    }

    public function actionCreate($peid) {
        $peid = Html::encode($peid);
        $processEntityModel = \app\modules\workflow\models\ProcessEntity::find()
                ->where(['ProcessEntityID'=>$peid])
                ->one();
        $invid = $processEntityModel->InvoiceIDRef;
        $amountTypeArray = \yii\helpers\ArrayHelper::map(\app\modules\invoice\models\InvoiceAmountTypes::find()->where(['<>', 'ID', 2])->asArray()->all($db = \app\components\Utility::db()), 'ID', 'InvoiceAmountType');
        $invoiceModel = InvoiceEdit::find()->where(['InvoiceID' => $invid])->one();
        $invoiceAmountModel = new InvoiceAmountEdit();
        $invoiceAmountModel->setScenario('maincontract-create');
        $invoiceAmountModel->InvoiceID = $invid;
        $invoiceAmountModel->Currency1 = $invoiceModel->Currency1IDRef;
        $invoiceAmountModel->Currency2 = $invoiceModel->Currency2IDRef;
        $invoiceAmountModel->hasChange = 1;
        $invoiceAmountModel->AmountType = 1;

        $contractProjectModel = \app\modules\wbs\models\Epsnew::find()
                ->where(['contractIdRef' => $invoiceAmountModel->invoice->contract->ContractID])
                ->andWhere("(isDeleted is null or isDeleted<>1)")
                ->one();
        
        if(!$contractProjectModel){
            return '<div class="alert alert-danger fade in"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <strong>Error!</strong> Porject of Contract not found.</div>';
        }

        if ($invoiceAmountModel->load(Yii::$app->request->post())) {
            if ($invoiceAmountModel->validate()) {
                if ($invoiceAmountModel->save()) {
                    $this->sendLog($invoiceAmountModel->ID, "New");
                    return 1;
                } else {
                }
            } else {
            }
        }
        return $this->renderAjax('amountForm', [
                    'modelAmount' => $invoiceAmountModel,
                    'amountTypeArray' => $amountTypeArray,
                    'formState' => 'new',
                    'projectId' => $contractProjectModel->EPSID,
        ]);
    }

    public function actionUpdate($peid, $invamid) {
        $peid = Html::encode($peid);
        $invamid = Html::encode($invamid);
        
        $invid = ProcessEntity::find()
                ->where(['ProcessEntityID'=>$peid])
                ->one()
                ->InvoiceIDRef;
        
        $invoiceAmountModel = InvoiceAmountEdit::find()
                ->where(['ID'=>$invamid])
                ->one();
        
        if (!$invoiceAmountModel ) {
            throw new \yii\web\HttpException("Invalid arguments", 404);
        }
        if ($invoiceAmountModel->InvoiceID !=  $invid) {
            throw new \yii\web\HttpException("Invalid arguments", 404);
        }

        $amountTypeArray = \yii\helpers\ArrayHelper::map(\app\modules\invoice\models\InvoiceAmountTypes::find()->where(['<>', 'ID', 2])->asArray()->all($db = \app\components\Utility::db()), 'ID', 'InvoiceAmountType');
        $invoiceAmountModel->setScenario('maincontract-create');

        $contractProjectModel = \app\modules\wbs\models\Epsnew::find()
                ->where(['contractIdRef' => $invoiceAmountModel->invoice->contract->ContractID])
                ->andWhere("(isDeleted is null or isDeleted<>1)")
                ->one();


        if ($invoiceAmountModel->load(Yii::$app->request->post()) && $invoiceAmountModel->validate()) {
            $res = $invoiceAmountModel->save();
            if ($res) {
                $this->sendLog($invoiceAmountModel->ID, "Edit");
                Yii::$app->replication->synch($invoiceAmountModel);
//                Yii::$app->session->setFlash('success', 'edit InvoiceAmont');
                return 1;
            }
        }

        return $this->renderAjax('amountForm', [
                    'modelAmount' => $invoiceAmountModel,
                    'amountTypeArray' => $amountTypeArray,
                    'formState' => 'edit',
                    'projectId' => $contractProjectModel->EPSID,
        ]);
    }

    public function actionDelete($peid, $id) {
        $peid = Html::encode($peid);
        $id = Html::encode($id);
        
        $invid = ProcessEntity::find()
                ->where(['ProcessEntityID'=>$peid])
                ->one()->InvoiceIDRef;
      
        if (!Yii::$app->request->isAjax && !Yii::$app->request->isPjax) {
            throw new \yii\web\BadRequestHttpException();
        }
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

//        $invoiceAmountModel = \app\modules\invoice\models\InvoiceAmountEdit::findOne($id);
        $invoiceAmountModel = \app\modules\invoice\models\InvoiceAmountEdit::find()->where(['ID' => $id])->one();

        if ($invoiceAmountModel->InvoiceID != $invid) {
            throw new \yii\web\HttpException(403, 'Invalid Arguments!');
        }

        $amountCount = \app\modules\invoice\models\InvoiceAmountEdit::find()
                ->where(["InvoiceID"=>$invid])
                ->count();
        if ($amountCount == 1) {
            return [
                'result' => 0,
                'message' => 'This invoice has just one amount record and you can not delete it'
            ];
        }
        //@TODO: واقعا حذف اینجوری باید بشود   :O
        $res = \app\modules\invoice\models\InvoiceAmountEdit::deleteAll("ID=$id");
        if ($res) {
            if (Yii::$app->params['autoReplicate']) {
                \app\modules\invoice\models\InvoiceAmountEditLocal::deleteAll(["ID"=>$id]);
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
        $subType = $type . "InvoiceAmountEdit";
        $subTypeDescription["TableName"] = $invoiceAmountEditTable;
        $subTypeDescription["RecordPrimaryKey"] = $id;
        $activityManagement->sendActitvityLog($actionType, $subType, $subTypeDescription);
    }
}
