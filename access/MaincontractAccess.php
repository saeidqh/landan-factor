<?php

namespace app\access;

use app\components\ActiveModel;

/**
 * Description of ContractAccess
 *
 * @author joudi_a
 */
class MaincontractAccess extends AccessAbstract {

    /**
     * شناسه قرارداد
     * @var type 
     */
    public $cid;

    /**
     * نوع دسترسی
     *  
     * @var string fro sample value 'ContractList' 
     */
    public $accessType;

    public function execute() {
        $this->checkParameter([
            [['cid', 'accessType'], 'required']
        ]);

        $cid = $this->cid;
        $accessType = $this->accessType;

        $contractModel = \app\modules\contract\models\Contract::find()->maincontract($accessType)
                ->andWhere([\app\modules\contract\models\Contract::tableName() . ".ContractID" => $cid])
                ->one();
        return ['result' => isset($contractModel->ContractID), 'message' => "You do not have access in state (".  ucfirst($this->accessType).') mode'];
    }
    
    public function setDid($val)
    {
        $RevisionModel= \app\modules\contract\models\ContractRevisionDocument::find()
                ->where(['ID'=>$val])
                ->one();
        
        if(isset($RevisionModel)){
            $this->cid = $RevisionModel->revision->ContractID;
        }
    }
    
    

    public function setRid($val) {
        $RevisionModel = \app\modules\contract\models\ContractRevision::find()
                ->where(['RiderID'=>$val])
                ->one();
        if (isset($RevisionModel)) {
            $this->cid = $RevisionModel->ContractID;
        }
    }

    public function setInvid($val) {
        $invoiceModel = \app\modules\invoice\models\InvoiceEdit::find()
                ->where(['InvoiceID'=>$val])
                ->one();
        if (isset($invoiceModel)) {
            $this->cid = $invoiceModel->ContractID;
        }
    }
    
    public function setPeid($val) {
        $processEntityModel = \app\modules\workflow\models\ProcessEntity::find()
                ->where(['ProcessEntityID'=>$val])
                ->one();
        $invoiceModel = \app\modules\invoice\models\InvoiceEdit::find()
                ->where(['InvoiceID'=>$processEntityModel->InvoiceIDRef])
                ->one();
        if (isset($invoiceModel)) {
            $this->cid = $invoiceModel->ContractID;
        }
    }

}
