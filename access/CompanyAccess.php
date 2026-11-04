<?php

namespace app\access;

use Yii;
/**
 * 
 */
class CompanyAccess extends AccessAbstract {
    const IN_PARENT_AND_CHILD= "inChild";
    

    /**
     *
     * @var integer 
     */
    public $comid;
    public $check;
    
    

    public function execute() {
        

        $check = $this->check;
        if (method_exists($this, $check)) {
            $this->checkParameter([
                [
                    ['comid'],
                    'required'
                ],
            ]);
            return $this->$check();
        }
        return ['result' => false];
    }
    
    public function match()
    {
        return Yii::$app->session->get('CompanyID') == $this->comid;
    }

 
    public function inChild()
    {
        $comid = Yii::$app->session->get('CompnayID');
        $array = \app\components\CandoHelper::companyChilds($comid);
        return ['result' => in_array($this->comid, $array)];
    }
    
    
    public function setRoleId($val) {
        $roleModel = \app\modules\OBS\models\Role::find()->where(['roleId' => $val])->one();
        if(isset($roleModel->roleId))
        {
            $this->comid = $roleModel->companyIdRef;
        }
        return false;
    }
}

