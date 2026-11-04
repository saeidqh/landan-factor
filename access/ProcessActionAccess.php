<?php
namespace app\access;

use Yii;
use app\modules\OBS\models\ObsProcessAction;
/**
 * Description of ProcessActionAccess
 *
 * @author joudi_a
 */
class ProcessActionAccess extends AccessAbstract{
    
    /**
     *
     * @var integer
     */
    public $obsId;
    
    /**
     *
     * @var integer
     */
    public $aid;
    
    public function __construct($config = array()) {
        $this->obsId = !empty($this->obsId) ? $this->obsId:Yii::$app->session->get('ObsPosition');
        parent::__construct($config);
    }


    public function execute()
    {
        $this->checkParameter([
            [
                ['obsId', 'aid'],
                'required'
            ],
        ]);
        
        $UserObs = $this->obsId;
        $aid = $this->aid;
        
        $obsProcessActionTable = ObsProcessAction::tableName();
        $obsProcessActionExists = ObsProcessAction::find()
                ->andWhere(['OBSCategory3IDRef' => $UserObs, 'ActionIDRef' => $aid])
                ->andWhere("$obsProcessActionTable.isDeleted is null or $obsProcessActionTable.isDeleted!=1")
                ->exists();
        return ['result' => $obsProcessActionExists, 'message' => "You need Process Action Id <span style='color: #242b14;border-radius: 5px;background: #ffffff;padding: 2px;    box-shadow: 2px 3px 6px 0 #463636;line-height: 1.5;'>$this->aid</span>"];
    }
     
    
    
}
