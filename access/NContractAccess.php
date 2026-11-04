<?php
namespace app\access;

/**
 * Description of NContractAccess
 *
 * @author joudi_a
 */
class NContractAccess extends AccessAbstract{
    
    const VIEW_TYPE = "view";
    const DELETE_TYPE = "delete";
    const UPDATE_TYPE = "update";
    
    public $ncid;
    
    public $check;


    public function execute() {
       
        $check = $this->check;

        if (method_exists($this, $check)) {
            $this->checkParameter([
                [
                    ['ncid', 'check'],
                    'required'
                ],
            ]);
            
            return ['result' => $this->$check()];
        }
    }
    
    public function view(){
        return \app\modules\budget\models\Ncontract::find()
                ->where(['NewContractID' => $this->ncid])->newBudget()->exists();
    }
    
    public function update() {
         return \app\modules\budget\models\Ncontract::find()
                ->where(['NewContractID' => $this->ncid])->newBudget()->exists();
    }
    
    public function delete() {
         return \app\modules\budget\models\Ncontract::find()
                ->where(['NewContractID' => $this->ncid])->newBudget()->exists();
    }
    
    public function setBid($val) {
        
        $ncontractBudgetModel = \app\modules\budget\models\NcontractNodeBudget::find()
                ->where(['NodeBudgetID'=>$val])
                ->one();
        if(isset($ncontractBudgetModel->NodeBudgetID))
        {
            $this->ncid = $ncontractBudgetModel->NewContractIDRef;
        }
        return false;
    }
}
