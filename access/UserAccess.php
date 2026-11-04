<?php

namespace app\access;

use Yii;
use app\modules\account\models\User;
use app\modules\account\models\UserObs;
use app\modules\OBS\models\Obsnew;

class UserAccess extends AccessAbstract {
    const USER_VIEW = "userView";
    
    const USER_UPDATE = "userUpdate";

    /**
     *
     * @var integer 
     */
    public $userId;
    public $check;
    
    
    public function __construct($config = array()) {
        
        $this->userId = !empty($this->userId) ? $this->userId: Yii::$app->user->id;
       
        parent::__construct($config);
    }

    public function execute() {
        $this->checkParameter([
            [
                ['userId'],
                'required'
            ],
        ]);

        $check = $this->check;
        if (method_exists($this, $check)) {
            return ['result' => $this->$check()];
        }
    }

 
    public function isCandoManager() {
        if (($this->userId == 1) || ($this->userId == 2) || ($this->userId == 25) || ($this->userId == 2889)||($this->userId == 3117)||($this->userId == 4518)||($this->userId == 4128)) {
            return true;
        } else {
            return false;
        }
    }
 
    public function isCandoTopManager() {
        if (($this->userId == 25) or ($this->userId == 2889)) {
            return true;
        } else {
            return false;
        }
    }
    
    
    public function userView()
    {
        //@TODO Farzam
        $rs = User::find()
                ->company()
                ->andWhere(['UserID' => $this->userId])
                ->exists();
        
        return $rs;
    }
    
    public function userUpdate()
    {
        $obsnewTable = Obsnew::tableName();
        $companyId = Yii::$app->session->get('CompanyID');
        $userobsTable = UserObs::tableName();
        $rs = UserObs::find()
                ->where("$userobsTable.isDeleted is null or $userobsTable.isDeleted")
                ->andWhere("OBSCategory3IDRef not in(select obsId from $obsnewTable where companyIdRef = $companyId)")
                ->addParams([':companyId'=>$companyId])
                ->exists();
        return $rs;
    }

}
