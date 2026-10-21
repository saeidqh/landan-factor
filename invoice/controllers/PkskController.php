<?php

namespace app\modules\invoice\controllers;

use Yii;
use yii\web\Controller;
use app\components\Security;
use app\components\jDateTime;
use app\modules\invoice\models\InvoicePksk;
use app\modules\currency\models\Currency;
use yii\web\UploadedFile;
use app\components\Hex;
use app\components\Session;
use app\components\Utility;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class PkskController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $defaultAction = null;
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
                    return \app\access\AccessControl::isAccess([
                                'or',
                                ['processEntity', 'peid' => $peid, 'type' => 'ToDo'],
                                ['and',
                                    ['contract', 'invoicePeid' => $peid, 'accessType' => 'InvoiceList'],
                                    ['formAction', 'aid' => 175]
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
                    return \app\access\AccessControl::isAccess([
                                'or',
                                ['processEntity', 'peid' => $peid, 'type' => 'ToDo'],
                                ['and',
                                    ['contract', 'invoicePeid' => $peid, 'accessType' => 'InvoiceList'],
                                    ['formAction', 'aid' => 175]
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
                    return \app\access\AccessControl::processEntity(['peid' => $peid, 'type' => 'ToDo'])
                                    ->isAccess();
                },
                    ],
                ],
            ],
        ];
    }

    public function actionCreate($peid) {
        //find processEntity

        $peid = Html::encode($peid);

        $processEntityModel = \app\modules\workflow\models\ProcessEntity::find()
                ->where(['ProcessEntityID' => $peid])
                ->one();

        if (!isset($processEntityModel)) {
            throw new \yii\web\BadRequestHttpException();
        }
        $invoicePkskModel = new \app\modules\invoice\models\InvoicePksk();
        $invoicePkskModel->InvoiceIDRef = $processEntityModel->InvoiceIDRef;
        if ($invoicePkskModel->invoice->InvoiceType <= 5) {
            $invoicePkskModel->setScenario('normal');
        } else {
            $invoicePkskModel->setScenario('aditional');
        }
        $currencyArray = \yii\helpers\ArrayHelper::map(\app\modules\currency\models\Currency::find()
                                ->asArray()
                                ->all($db = \app\components\Utility::db()), 'CurrencyID', 'CurrencyTitle');
        $invoicePkskModel->CI_Currency1IDRef = $invoicePkskModel->invoice->Currency1IDRef;
        $invoicePkskModel->CI_Currency2IDRef = $invoicePkskModel->invoice->Currency2IDRef;
        $invoicePkskModel->hasChange = 1;

        if ($invoicePkskModel->load(Yii::$app->request->post())) {

            if ($invoicePkskModel->validate()) {
                $res = $invoicePkskModel->save();
                if($res) {
                    $this->sendLog($invoicePkskModel->ID, "New");
                }
                return $res;
            }
        }
        return $this->renderAjax('_invoicePkskForm', [
                    'invoicePkskModel' => $invoicePkskModel,
                    'currencyArray' => $currencyArray,
        ]);
    }

    public function actionUpdate($peid, $pkskid) {

        $peid = Html::encode($peid);
        $pkskid = Html::encode($pkskid);

        $processEntityModel = \app\modules\workflow\models\ProcessEntity::find()
                ->where(['ProcessEntityID' => $peid])
                ->one();

        if (!isset($processEntityModel)) {
            throw new \yii\web\BadRequestHttpException();
        }

        $invoicePkskModel = \app\modules\invoice\models\InvoicePksk::find()
                ->where(['ID' => $pkskid])
                ->one();

        if ($invoicePkskModel->InvoiceIDRef != $invoicePkskModel->InvoiceIDRef) {
            throw new \yii\web\BadRequestHttpException();
        }
        if ($invoicePkskModel->invoice->InvoiceType <= 5) {
            $invoicePkskModel->setScenario('normal');
        } else {
            $invoicePkskModel->setScenario('aditional');
        }
        $invoicePkskModel->hasChange = 1;
        $currencyArray = \yii\helpers\ArrayHelper::map(\app\modules\currency\models\Currency::find()
                                ->asArray()
                                ->all($db = \app\components\Utility::db()), 'CurrencyID', 'CurrencyTitle');

        if ($invoicePkskModel->load(Yii::$app->request->post())) {
            if ($invoicePkskModel->validate()) {
                $res = $invoicePkskModel->save();
                if ($res) {
                    $this->sendLog($invoicePkskModel->ID, "Edit");
                    Yii::$app->replication->synch($invoicePkskModel);
                    return 1;
                }
            } else {
                $modelErrors = "";
                foreach ($invoicePkskModel->errors as $v) {
                    for ($i = 0; $i < count($v); $i++) {
                        $modelErrors .= "<br / ><i class='fa-remove' style='color:red'></i>&nbsp;&nbsp;<b>$v[$i]</b>";
                    }
                }
                Yii::$app->session->setFlash('warning', 'Please solve the validation errors and try again.' . $modelErrors);
            }
        }
        return $this->renderAjax('_invoicePkskForm', [
                    'invoicePkskModel' => $invoicePkskModel,
                    'currencyArray' => $currencyArray,
        ]);
    }

    public function actionDelete($peid, $pkskid) {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        $peid = Html::encode($peid);
        $pkskid = Html::encode($pkskid);

        //find processEntity
        $processEntityModel = \app\modules\workflow\models\ProcessEntity::find()
                ->where(['ProcessEntityID' => $peid])
                ->one();

        if (!isset($processEntityModel)) {
            throw new \yii\web\HttpException(404, "Invalid Argument");
        }

        $pkskModel = InvoicePksk::find()
                ->where(['ID'=>$pkskid])
                ->one();
        
        if ($pkskModel->InvoiceIDRef != $processEntityModel->InvoiceIDRef) {
            throw new \yii\web\BadRequestHttpException();
        }
        if (isset($pkskModel)) {
            if ($pkskModel->delete()) {
                return [
                    'result' => 1,
                    'message' => 'Pksk record deleted successfully!!'
                ];
            } else {
                return [
                    'result' => 0,
                    'message' => 'Cannot deleted pksk record'
                ];
            }
        }
        return false;
    }

    public function sendLog($id, $type) {
        $invoicePkskTable = \app\modules\invoice\models\InvoicePksk::tableName();
        $activityManagement = new \app\components\ActivityManagement();
        $actionType = $type;
        $subType = $type . "InvoicePksk";
        $subTypeDescription["TableName"] = $invoicePkskTable;
        $subTypeDescription["RecordPrimaryKey"] = $id;
        $activityManagement->sendActitvityLog($actionType, $subType, $subTypeDescription);
    }
}
