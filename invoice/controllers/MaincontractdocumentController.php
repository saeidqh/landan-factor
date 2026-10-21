<?php

namespace app\modules\invoice\controllers;

use Yii;
use yii\web\Controller;
use app\components\Security;
use app\components\jDateTime;
use yii\web\UploadedFile;
use app\modules\invoice\models\InvoiceDocument;
use yii\helpers\ArrayHelper;
use app\components\Documents;
use yii\helpers\Html;

class MaincontractdocumentController extends Controller {

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
                'only' => ['create', 'update', 'delete', 'download'],
                'rules' => [
                    // allow authenticated users
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function($rule, $action, $exteraData = []) {
                            $peid = isset($exteraData['peid']) ? $exteraData['peid'] : Yii::$app->request->get('peid');
                            return \app\access\AccessControl::isAccess([
                                'or',
                                ['processEntity','peid' => $peid, 'type' => 'ToDo'],
                                ['obs', 'check' => 'isCandoAdmin'],
                                ['formAction', 'aid' => 317]
                            ]);
                        },
                    ],
                    [
                        'actions' => ['update'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function($rule, $action, $exteraData = []) {
                            $peid = isset($exteraData['peid']) ? $exteraData['peid'] : Yii::$app->request->get('peid');
                            return \app\access\AccessControl::isAccess([
                                'or',
                                ['processEntity','peid' => $peid, 'type' => 'ToDo'],
                                ['obs', 'check' => 'isCandoAdmin'],
                                ['formAction', 'aid' => 313]
                            ]);
                        },
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function($rule, $action, $exteraData = []) {
                            $peid = isset($exteraData['peid']) ? $exteraData['peid'] : Yii::$app->request->get('peid');
                            return \app\access\AccessControl::isAccess([
                                'or',
                                ['processEntity', 'peid' => $peid, 'type' => 'ToDo'],
                                ['obs', 'check' => 'isCandoAdmin']
                            ]);
                        },
                    ],
                    [
                        'actions' => ['download'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function($rule, $action, $exteraData = []) {
                            $peid = isset($exteraData['peid']) ? $exteraData['peid'] : Yii::$app->request->get('peid');
                            return \app\access\AccessControl::isAccess([
                                'and',
                                ['processEntity', 'peid' => $peid, 'type' => 'Graph'],
//                                ['maincontract', 'peid' => $peid, 'accessType' => 'view'],
                                ['formAction', 'aid' => 312]
                            ]);
                        },
                    ],
                ],
            ],
        ];
    }

    public static function getActionRequiredParameter($actionName) {
        $params = [
            'download' => ['docid'],
            'create' => ['invid'],
            'update' => ['invdid'],
            'delete' => ['invdid'],
        ];
        return $params[$actionName];
    }

    public function actionCreate($peid) {
        $invid = \app\modules\workflow\models\ProcessEntity::find()
                ->where(['ProcessEntityID' => $peid])
                ->one()
                ->InvoiceIDRef;
        if (!Yii::$app->request->isAjax && !Yii::$app->request->isPjax) {
            throw new \yii\web\BadRequestHttpException();
        }
        $invoiceDocumentModel = new InvoiceDocument();
        $invoiceDocumentModel->setScenario('add');
        $documentTypeArray = ArrayHelper::map(\app\modules\invoice\models\InvoiceDocumentType::find()
                ->asArray()
                ->all($db = \app\components\Utility::db()), 'InvoiceDocumentTypeID', 'InvoiceDocumentTypeTitle');
        $invoiceDocumentModel->InvoiceIDref = $invid;
        $invoiceDocumentModel->UserIDRef = Yii::$app->user->id;
        $invoiceDocumentModel->hasChange = 1;
        $date = new jDateTime(true, true, 'Asia/Tehran');
        $invoiceDocumentModel->InsertDateHijri = $date->date("Y/m/d-Hi", false, false);


        $processEntityModel = \app\modules\workflow\models\ProcessEntity::find()
                ->where(['InvoiceIDRef' => $invid])
                ->one();
        $invoiceDocumentModel->InvoiceActionIDRef = $processEntityModel->LastActionIDRef;

        if ($invoiceDocumentModel->load(Yii::$app->request->post())) {
            $invoiceDocumentModel->file = \yii\web\UploadedFile::getInstance($invoiceDocumentModel, "file");
            if ($invoiceDocumentModel->file instanceof \yii\web\UploadedFile) {
                $invoiceDocumentModel->FileName = $invoiceDocumentModel->file->name;
            }
            if ($invoiceDocumentModel->save() && $invoiceDocumentModel->upload()) {
                $this->sendLog($invoiceDocumentModel->InvoiceDocumentID, "New");
                Yii::$app->replication->synch($invoiceDocumentModel);
                return "<script>callbackPjax()</script>";
            }
        }

        return $this->renderAjax("documentForm", [
                    'model' => $invoiceDocumentModel,
                    'documentType' => $documentTypeArray,
                    'formState' => 'new',
        ]);
    }

    public function actionUpdate($peid, $invdid) {
       
        $peid = Html::encode($peid);
        $invdid = Html::encode($invdid);
        
        $invid = \app\modules\workflow\models\ProcessEntity::find()
                ->where(['ProcessEntityID' => $peid])
                ->one()
                ->InvoiceIDRef;
       
        $invoiceDocumentModel = InvoiceDocument::find()
                ->where(['InvoiceDocumentID' => $invdid])
                ->one();
        
        if ($invoiceDocumentModel->InvoiceIDref != $invid) {
            throw new \yii\web\HttpException("Invalid arguments", 404);
        }

        if (!Yii::$app->request->isAjax && !Yii::$app->request->isPjax) {
            throw new \yii\web\BadRequestHttpException();
        }

        $invoiceDocumentModel->hasChange = 1;
        $documentTypeArray = ArrayHelper::map(\app\modules\invoice\models\InvoiceDocumentType::find()
                ->asArray()
                ->all($db = \app\components\Utility::db()), 'InvoiceDocumentTypeID', 'InvoiceDocumentTypeTitle');

        if ($invoiceDocumentModel->load(Yii::$app->request->post()) && $invoiceDocumentModel->validate() && $invoiceDocumentModel->save()) {
            $this->sendLog($invoiceDocumentModel->InvoiceDocumentID, "Edit");
            Yii::$app->replication->synch($invoiceDocumentModel);
            return "<script>callbackPjax()</script>";
        }

        return $this->renderAjax("documentForm", [
                    'model' => $invoiceDocumentModel,
                    'documentType' => $documentTypeArray,
                    'formState' => 'edit',
        ]);
    }

    public function actionDelete($peid, $invdid) {
        
        $peid = Html::encode($peid);
        $invdid = Html::encode($invdid);
        
        $invid = \app\modules\workflow\models\ProcessEntity::find()
                ->where(['ProcessEntityID' => $peid])
                ->one()
                ->InvoiceIDRef;
       
        $invoiceDocumentModel = InvoiceDocument::find()
                ->where(['InvoiceDocumentID' => $invdid])
                ->one();
         
        if ($invoiceDocumentModel->InvoiceIDref != $invid) {
            throw new \yii\web\HttpException("Invalid arguments", 404);
        }
        if (!$invoiceDocumentModel) {
            throw new \yii\web\BadRequestHttpException();
        }

        if (!\app\access\AccessControl::isAccess(['obs', 'check' => 'isCandoAdmin', 'errorException' => false]) && ($invoiceDocumentModel->UserIDRef != Yii::$app->session->get('UserID'))) {
            throw new \yii\web\BadRequestHttpException();
        }

        $invoiceDocumentModel->isDeleted = 1;

        if ($invoiceDocumentModel->save()) {
            $invoiceDocumentModel->deleteFile();

            $this->sendLog($invoiceDocumentModel->InvoiceDocumentID, "Delete");
            
            return $this->redirect(['invoice/view',
                        'invid' => $invoiceDocumentModel->InvoiceIDref,
                        '_tab' => 'InvoiceDocument'
            ]);
        }
    }

    public function actionDownload($peid, $docid) {
        
        $invid = \app\modules\workflow\models\ProcessEntity::find()
                ->where(['ProcessEntityID' => $peid])
                ->one()
                ->InvoiceIDRef;
       
        $model = InvoiceDocument::find()
                ->where(['InvoiceDocumentID'=>$docid])
                ->one();
        
        if (!isset($model->InvoiceDocumentID)) {
            throw new \yii\web\HttpException("Document not found", 404);
        }
        if ($model->InvoiceIDref != $invid) {
            throw new \yii\web\HttpException("Invalid arguments", 404);
        }
        $model->setConfigDoc([
            'selectedFileName' => $model->FileName,
        ]);
        $invoiceDocumentTable = InvoiceDocument::tableName();
        $activityManagement = new \app\components\ActivityManagement();
        $actionType = "Download";
        $subType = "DownloadInvoiceDocument";
        $subTypeDescription["TableName"] = $invoiceDocumentTable;
        $subTypeDescription["RecordPrimaryKey"] = $model->InvoiceDocumentID;
        $subTypeDescription["FileName"] = $model->FileName;
        $activityManagement->sendActitvityLog($actionType, $subType, $subTypeDescription);
        
        return $model->download();
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
