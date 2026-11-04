<?php

namespace app\access;

use Yii;
use app\modules\OBS\models\Company;

/**
 * Description of UserObsAccess
 *
 * @author joudi_a
 */
class ObsAccess extends AccessAbstract {

    /**
     *
     * @var integer 
     */
    public $obsId = 0;
    public $check;
    
    
    public function __construct($config = array()) {
        
        $this->obsId = !empty($this->obsId) ? $this->obsId: Yii::$app->session->get('ObsPosition');
       
        parent::__construct($config);
    }
    
    public function execute() {
        $this->checkParameter([
            [
                ['obsId'],
                'required'
            ],
        ]);

        $check = $this->check;
        
        if (method_exists($this, $check)) {
            return $this->$check();
        }
    }

    /**
     * بررسی مدیر کندو بودن
     * @return boolean
     */
    public function isCandoAdmin() {
        $obsid = $this->obsId;
        if (($obsid == 606)) {
            return ['result' => true];
        } else {
            return ['result' => false, 'message' => 'You must be the CandoAdmin!'];
        }
    }

    /**
     * بررسی مدیر شرکت بودن
     * @return boolean
     */
    public function isCompanyAdmin() {
        if(Company::find()->where(['adminIdRef' => $this->obsId])->exists()){
            return ['result' => true];
        }
        return ['result' => false, 'You must be CompanyAdmin'];
    }

    /**
     * 
     * @return boolean
     */
    public function isAdmin() {
        $isCompanyAdmin = $this->isCompanyAdmin();
        $isCandoAdmin = $this->isCandoAdmin();
        if ($isCandoAdmin['result'] || $isCompanyAdmin['result']) {
            return ['result' => true];
        }
        return ['result' => false, 'message' => 'You must be CandoAdmin or CompanyAdmin'];
    }

    
    public function inCompany()
    {
        $company= \app\modules\OBS\models\Obsnew::findOne($this->obsId)->companyIdRef;
        if((Yii::$app->session->get('CompanyID') == $company)){
            return ['result' => true];
        }
        return ['result' => false, 'Should be in your company'];
    }
    
    public function inCompanyChild()
    {
        $userCompanyId = Yii::$app->session->get('CompanyID');
        $company= \app\modules\OBS\models\Obsnew::findOne($this->obsId)->companyIdRef;
        $companyAllChildsArray = \app\components\CandoHelper::companyChilds($userCompanyId, 'array');
        if(in_array($company, $companyAllChildsArray)){
            return ['result' => true];
        }
        return ['result' => false, 'Should be in your company'];
    }

    public function inCompanyAdmin()
    {
        $company= \app\modules\OBS\models\Obsnew::findOne($this->obsId)->companyIdRef;
        if((Yii::$app->session->get('CompanyID') == $company 
                && (Company::find()->where(['adminIdRef' => Yii::$app->session->get('ObsPosition')])->exists()))){
            return ['result' => true];
        }
        return ['result' => false, 'Should be in your company and the admin'];
    }
}
