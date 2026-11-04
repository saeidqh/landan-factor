<?php
namespace app\access;

/**
 * Description of PlanAccess
 *
 * @author joudi_a
 */
class PlanAccess extends AccessAbstract{
    
    const VIEW_TYPE = "view";
    const UPDATE_TYPE = "update";
    const DELETE_TYPE = "delete";

    /**
     *
     * @var integer Plan Id
     */
    public $pid;
    
    /**
     *
     * @var string 
     */
    public $check;

    /**
     * 
     * @return type
     */
    public function execute() {
       
        $check = $this->check;
        if (method_exists($this, $check)) {
            $this->checkParameter([
                [
                    ['pid', 'check'],
                    'required'
                ],
            ]);
            return ['result' => $this->$check()];
        }
    }
    
    public function view(){
        $uid = \Yii::$app->session->get('UserID');
        $obsId = \Yii::$app->session->get('ObsPosition');
        $companyId = \Yii::$app->session->get('CompanyID');

        $res = \app\modules\budget\models\Plan::find()
        ->where(["PlanID"=>$this->pid])        
        ->where(["OBSCategory3CompanyIDRef"=>$companyId])       
        ->where(['IN', "EPSIDRef", \app\components\CandoHelper::userEps($uid, $obsId)])
                ->exists();
        return ['result' => $res];
    }
    //@TODO Farzam
    public function update() {
        return $this->view();
    }
    //@TODO Farzam
    public function delete() {
        return $this->view();
    }
}
