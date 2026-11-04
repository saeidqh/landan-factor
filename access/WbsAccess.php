<?php

namespace app\access;
use Yii;
/**
 * Description of WbsAccess
 *
 * @author joudi_a
 */
class WbsAccess extends AccessAbstract {

    const WBS_VIEW_TYPE = "view";
    const WBS_CREATE_TYPE = "create";
    const WBS_UPDATE_TYPE = "update";
    const WBS_VIEW_BUDGET_TYPE = "viewBudget";
    const WBS_UPDATE_BUDGET_TYPE = "updateBudget";

    /**
     *
     * @var integer node id
     */
    public $nid;

    /**
     *
     * @var string 
     */
    public $check;

    public function execute() {
        $this->checkParameter([
            [
                ['nid', 'check'],
                'required'
            ],
        ]);

        $check = $this->check;
        if (method_exists($this, $check)) {
            return ['result' => $this->$check()];
        }
    }

    public function view() {
        $companyId=Yii::$app->session->get('CompanyID');
        $costcodeModel= \app\modules\wbs\models\Costcodenew::find()
                ->where(['NodeID'=>$this->nid])
                ->andWhere(['LIKE','access','comv'.$companyId])
                ->one();
        if(!$costcodeModel){
            return false;
        }
        return true;
    }
    public function create() {
        $companyId=Yii::$app->session->get('CompanyID');
        $costcodeModel= \app\modules\wbs\models\Costcodenew::find()
                ->where(['NodeID'=>$this->nid])
                ->one();
        if(!$costcodeModel){
            return false;
        }
        if($costcodeModel->ownerCompanyIdRef !=$companyId){
            return false;
        }
        return true;
    }
    public function update() {
        return $this->create();
    }
    public function viewBudget() {
        return $this->view();
    }
    public function updateBudget() {
        return $this->create();
    }

}
