<?php

namespace app\modules\invoice\controllers;

use Yii;
use yii\web\Controller;
use app\components\jDateTime;
use app\modules\invoice\models\InvoiceDocument;
use yii\helpers\ArrayHelper;
use app\modules\workflow\models\ProcessEntity;
use yii\helpers\Html;

class DocumentController extends Controller {

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
                'only' => ['create', 'update', 'delete', 'download', 'download-all'],
                'rules' => [
                    // allow authenticated users
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function($rule, $action, $exteraData = []) {
                    $peid = isset($exteraData['peid']) ? $exteraData['peid'] : Yii::$app->request->get('peid');
                    $processEntityModel = \app\modules\workflow\models\ProcessEntity::find()
                            ->where(['ProcessEntityID' => $peid])
                            ->one();
                    return \app\access\AccessControl::isAccess([
                                'or',
                                ['actionParam', 'aid' => $processEntityModel->LastActionIDRef, 'pt' => 'InvoiceAccessId', 'for' => 'EditInvoiceDocument'],
                                ['or',
                                    ['formAction', 'aid' => 352],
                                ]
                    ]);
                },
                    ],
                    [
                        'actions' => ['update'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function($rule, $action, $exteraData = []) {
                    $peid = isset($exteraData['peid']) ? $exteraData['peid'] : Yii::$app->request->get('peid');
                    $processEntityModel = ProcessEntity::find()
                            ->where(['ProcessEntityID' => $peid])
                            ->one();
                    return \app\access\AccessControl::isAccess([
                                'and',
                                ['actionParam', 'aid' => $processEntityModel->LastActionIDRef, 'pt' => 'InvoiceAccessId', 'for' => 'EditInvoiceDocument'],
                                [
                                    'or',
                                    ['processEntity', 'peid' => $peid, 'type' => 'ToDo'],
                                    ['formAction', 'aid' => 180],
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
                    $processEntityModel = ProcessEntity::find()
                            ->where(['ProcessEntityID' => $peid])
                            ->one();
                    return \app\access\AccessControl::isAccess([
                                'and',
                                ['actionParam', 'aid' => $processEntityModel->LastActionIDRef, 'pt' => 'InvoiceAccessId', 'for' => 'EditInvoiceDocument'],
                                ['or',
                                    ['processEntity', 'peid' => $peid, 'type' => 'ToDo'],
                                    ['formAction', 'aid' => 192],
                                ]
                    ]);
                },
                    ],
                    [
                        'actions' => ['download', 'download-all'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function($rule, $action, $exteraData = []) {
                    $peid = isset($exteraData['peid']) ? $exteraData['peid'] : Yii::$app->request->get('peid');
                    $processEntityModel = \app\modules\workflow\models\ProcessEntity::find()
                            ->where(['ProcessEntityID' => $peid])
                            ->one();
                    return \app\access\AccessControl::isAccess([
                                'and',
                                ['actionParam', 'aid' => $processEntityModel->LastActionIDRef, 'pt' => 'InvoiceAccessId', 'for' => 'ViewInvoiceDocument'],
                                ['processEntity', 'peid' => $peid, 'type' => 'Graph'],
                    ]);
                },
                    ],
                ],
            ],
        ];
    }

    public function actionCreate($peid) {
        if (!Yii::$app->request->isAjax && !Yii::$app->request->isPjax) {
            throw new \yii\web\BadRequestHttpException();
        }
        //find processEntity

        $peid = Html::encode($peid);

        $processEntityModel = ProcessEntity::find()
                ->where(['ProcessEntityID' => $peid])
                ->one();

        if (!isset($processEntityModel)) {
            throw new \yii\web\HttpException(404, "Invalid Argument");
        }
        $invoiceDocumentModel = new InvoiceDocument();
        $invoiceDocumentModel->setScenario('add');
        $documentTypeArray = ArrayHelper::map(\app\modules\invoice\models\InvoiceDocumentType::find()->andWhere("InvoiceDocumentTypeID not in (2,3,4,9)")->asArray()->all($db = \app\components\Utility::db()), 'InvoiceDocumentTypeID', 'InvoiceDocumentTypeTitle');
        $invoiceDocumentModel->InvoiceIDref = $processEntityModel->InvoiceIDRef;
        $invoiceDocumentModel->UserIDRef = Yii::$app->user->id;
        $invoiceDocumentModel->hasChange = 1;
        $date = new jDateTime(true, true, 'Asia/Tehran');
        $invoiceDocumentModel->InsertDateHijri = $date->date("Y/m/d-Hi", false, false);
        $invoiceDocumentModel->InvoiceActionIDRef = $processEntityModel->LastActionIDRef;

        if ($invoiceDocumentModel->load(Yii::$app->request->post())) {
            $invoiceDocumentModel->file = \yii\web\UploadedFile::getInstance($invoiceDocumentModel, "file");
            if ($invoiceDocumentModel->file instanceof \yii\web\UploadedFile) {
                $invoiceDocumentModel->FileName = $invoiceDocumentModel->file->name;
            }
            if ($invoiceDocumentModel->validate()) {
                if ($invoiceDocumentModel->save() && $invoiceDocumentModel->upload()) {
                    $this->sendLog($invoiceDocumentModel->InvoiceDocumentID, "New");
                    Yii::$app->replication->synch($invoiceDocumentModel);
                    return \app\components\Utility::renderPjaxCallBack("callbackPjax");
                }
            }
        }

        return $this->renderAjax("documentForm", [
                    'model' => $invoiceDocumentModel,
                    'documentType' => $documentTypeArray,
                    'formState' => 'new',
        ]);
    }

    public function actionUpdate($peid, $invdid) {

        if (!Yii::$app->request->isAjax && !Yii::$app->request->isPjax) {
            throw new \yii\web\BadRequestHttpException();
        }

        $processEntityModel = \app\modules\workflow\models\ProcessEntity::find()
                ->where(['ProcessEntityID' => $peid])
                ->one();

        $invoiceDocumentModel = InvoiceDocument::find()
                ->where(['InvoiceDocumentID' => $invdid])
                ->one();

        if ($processEntityModel->InvoiceIDRef != $invoiceDocumentModel->InvoiceIDref) {
            throw new \yii\web\HttpException(404, "Invalid Argument");
        }

        if (!\app\access\AccessControl::isAccess(['obs', 'check' => 'isCandoAdmin', 'errorException' => false]) && ($invoiceDocumentModel->UserIDRef != Yii::$app->session->get('UserID'))) {
            throw new \yii\web\BadRequestHttpException();
        }

        $invoiceDocumentModel->hasChange = 1;
        $documentTypeArray = ArrayHelper::map(\app\modules\invoice\models\InvoiceDocumentType::find()->andWhere("InvoiceDocumentTypeID not in (2,3,4,9)")
                                ->asArray()
                                ->all($db = \app\components\Utility::db()), 'InvoiceDocumentTypeID', 'InvoiceDocumentTypeTitle');

        if ($invoiceDocumentModel->load(Yii::$app->request->post()) && $invoiceDocumentModel->validate() && $invoiceDocumentModel->save()) {
            $this->sendLog($invoiceDocumentModel->InvoiceDocumentID, "Edit");
            return \app\components\Utility::renderPjaxCallBack("callbackPjax");
        }
        return $this->renderAjax("documentForm", [
                    'model' => $invoiceDocumentModel,
                    'documentType' => $documentTypeArray,
                    'formState' => 'edit',
        ]);
    }

    public function actionDelete($peid, $invdid) {
        $processEntityModel = ProcessEntity::find()
                ->where(['ProcessEntityID' => $peid])
                ->one();
        $invoiceDocumentModel = InvoiceDocument::find()
                ->where(['InvoiceDocumentID' => $invdid])
                ->one();

        if ($processEntityModel->InvoiceIDRef != $invoiceDocumentModel->InvoiceIDref || !$invoiceDocumentModel) {
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
                        'peid' => $peid,
                        '_tab' => 'InvoiceDocument'
            ]);
        }
    }

    public function actionDownload($peid, $docid) {

        if (empty($docid) || empty($peid)) {
            throw new \yii\web\BadRequestHttpException();
        }


        $processEntityModel = \app\modules\workflow\models\ProcessEntity::find()
                ->where(['ProcessEntityID' => $peid])
                ->one();


        $model = InvoiceDocument::find()
                ->where(['InvoiceDocumentID' => $docid])
                ->one();

        if ($processEntityModel->InvoiceIDRef != $model->InvoiceIDref) {
            throw new \yii\web\BadRequestHttpException();
        }

        if (!isset($model->InvoiceDocumentID)) {
            throw new \yii\web\HttpException("Document not found", 404);
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

    public function actionDownloadAll($peid) {
        if (empty($peid)) {
            throw new \yii\web\BadRequestHttpException();
        }
        
        $processEntityModel = \app\modules\workflow\models\ProcessEntity::find()
                ->where(['ProcessEntityID' => $peid])
                ->one();

        $invoiceDocumentQuery = InvoiceDocument::find()
                ->where(['InvoiceIDref' => $processEntityModel->InvoiceIDRef])->all();

        if (count($invoiceDocumentQuery) <= 0) {
            throw new \yii\web\HttpException(404, "Document not found!");
        }
        
        $fileName = 'FileName';
        \app\components\Utility::downloadAllDocument($invoiceDocumentQuery, null, $fileName);
        
        $ids = "";
        foreach ($invoiceDocumentQuery as $invoiceDocument) {
            $ids .= $invoiceDocument->InvoiceDocumentID . ",";
        }
        
        $invoiceDocumentTable = InvoiceDocument::tableName();
        $activityManagement = new \app\components\ActivityManagement();
        $actionType = "Download";
        $subType = "DownloadInvoiceDocument";
        $subTypeDescription["TableName"] = $invoiceDocumentTable;
        $subTypeDescription["RecordPrimaryKey"] = "[$ids]";
        $subTypeDescription["FileName"] = "all-document.zip";
        $activityManagement->sendActitvityLog($actionType, $subType, $subTypeDescription);
        
        exit();
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
