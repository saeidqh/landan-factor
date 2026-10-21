<?php

namespace app\modules\invoice\controllers;

use Yii;
use yii\web\Controller;
use app\components\Security;
use app\components\jDateTime;
use app\modules\currency\models\Currency;
use app\components\Hex;
use app\components\Session;
use app\components\Utility;
use yii\helpers\ArrayHelper;

class WidgetController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $defaultAction = null;

    public function behaviors() {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['contract-cumulative', 'contract-cumulative-currency'],
                'rules' => [
                    // allow authenticated users
                    [
                        'actions' => [
                            'contract-cumulative',
                        ],
                        'roles' => ['@'],
                        'allow' => true,
//                        'matchCallback' => function($rule, $action, $exteraData = []) {
//                            $cid = isset($exteraData['cid']) ? $exteraData['cid'] : Yii::$app->request->get('cid');
//                            return \app\access\AccessControl::isAccess([
//                                        'or',
//                                        ['contract', 'cid' => $cid, 'accessType' => 'ContractView'],
//                                        ['maincontract', 'cid' => $cid, 'accessType' => 'view'],
//                            ]);
//                        },
                    ],
                    [
                        'actions' => [
                            'contract-cumulative-currency',
                        ],
                        'roles' => ['@'],
                        'allow' => true,
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

    public function actionContractCumulative($cid, $amountStatus, $hdate = '', $wid = 'con-cum') {
        if (!Yii::$app->request->isAjax && !Yii::$app->request->isPjax) {
            throw new \yii\web\BadRequestHttpException();
        }
        $dataProvider = \app\modules\contract\models\Contract::totalAmount($cid, $amountStatus, $hdate);

        $contractModel = \app\components\ActiveModel::find('\app\modules\contract\models\Contract')
                ->where(['ContractID' =>$cid])
                ->one();
        return $this->renderAjax('_contractCumulative', [
                    'dataProvider' => $dataProvider,
                    'contractModel' => $contractModel,
                    'wid' => $wid,
        ]);
    }

    public function actionContractCumulativeCurrency() {
        $fullId = Yii::$app->request->post('expandRowKey');
        $r = explode(':', $fullId);

        $contractId = $r[0];
        $currencyId = str_replace('\'', '', $r[1]) * 1;
        $amountStatusString = $r[2];
        $hdate = $r[3];

        $amountStatus = -1;
        if ($amountStatusString == 'Approved') {
            $amountStatus = 5;
        } else if ($amountStatusString == 'Checking') {
            $amountStatus = 1;
        } else if ($amountStatusString == 'Held Or Rejected') {
            $amountStatus = 0;
        } else if ($amountStatusString == 'Approved Or Checking') {
            $amountStatus = 6;
        }
        $dataProvider = \app\components\CandoHelper::ContractNodeCurrency($contractId, $amountStatus, $currencyId, $hdate);

        return $this->renderAjax('_contractCumulativeCurrency', [
                    'dataProvider' => $dataProvider,
        ]);
    }

}
