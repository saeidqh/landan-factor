<?php

namespace app\access;

use Yii;

class WorkflowReportAccess extends AccessAbstract {
    const REPORT_VIEW = "reportView";
    
    const REPORT_UPDATE = "reportUpdate";

    /**
     *
     * @var integer 
     */
    public $rid;
    public $check;
    
    

    public function execute() {
        $this->checkParameter([
            [
                ['rid'],
                'required'
            ],
        ]);

        $check = $this->check;
        if (method_exists($this, $check)) {
            return ['result' => $this->$check()];
        }
    }
 
    public function reportView()
    {
        $reprotTable= \app\modules\reportWorkflow\models\Report::tableName();
        $reprotItemTable= \app\modules\reportWorkflow\models\ReportItem::tableName();
        $reprotItemActionTable= \app\modules\reportWorkflow\models\ReportItemAction::tableName();
        $actionTable= \app\modules\workflow\models\Action::tableName();
        
        if($this->rid != 0){
            $reportModel = \app\modules\reportWorkflow\models\Report::find()
                    ->where(['reportId'=>$this->rid])
                    ->one();

            if(!isset($reportModel->reportId))
            {
                throw new \yii\web\BadRequestHttpException("report Id not found!!");
            }
            if($reportModel->processTypeIdRef==7){
                $reportIdArray = \app\modules\reportWorkflow\models\Report::find()
                    ->addSelect("$reprotTable.reportId")
                    ->innerJoin($reprotItemTable, "$reprotItemTable.reportIdRef=$reprotTable.reportId")
                    ->innerJoin($reprotItemActionTable, "$reprotItemActionTable.itemIdRef=$reprotItemTable.itemId")
                    ->innerJoin($actionTable, "$actionTable.ActionID=$reprotItemActionTable.actionIdRef")
                    ->where(['processTypeIdRef'=>7])
                    ->andWhere(['like', "$actionTable.companyIdList", Yii::$app->session->get('CompanyID')])
                    ->andWhere(["$reprotTable.reportId" => $this->rid])
                    ->groupBy("$reprotTable.reportId")
                    ->createCommand()->rawSql;
                
                $checkAccess=\app\modules\reportWorkflow\models\Report::find()
                    ->andWhere(["or",
                        ['=', 'OBSCategory3CompanyIDRef', Yii::$app->session->get('CompanyID')],
                        "reportId in ($reportIdArray)"
                        ])
                    ->exists();

                if($checkAccess){
                    return true;
                }  else {
                    return false;
                }
            }
            return  ($reportModel->OBSCategory3CompanyIDRef == null || $reportModel->OBSCategory3CompanyIDRef == Yii::$app->session->get('CompanyID'));
        }else{
            return true;
        }
    }
    
    
    public function reportUpdate()
    {
        //@TODO Farzam
       $reportModel = \app\modules\reportWorkflow\models\Report::find()
                    ->where(['reportId'=>$this->rid])
                    ->one();
       
        if(!isset($reportModel->reportId))
        {
            throw new \yii\web\BadRequestHttpException("report Id not found!!");
        }
        return  $reportModel->OBSCategory3CompanyIDRef == Yii::$app->session->get('CompanyID');
    }
}
