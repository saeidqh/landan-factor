<?php
namespace app\access;

use app\modules\contract\models\Contract;
/**
 * Description of ContractAccess
 *
 * @author joudi_a
 */
class ContractAccess extends AccessAbstract{
    
    const TYPE_CONTRACT_LIST = "ContractList";
    const TYPE_CONTRACT_VIEW = "ContractView";
    const TYPE_CONTRACT_EDIT = "ContractEdit";
    const TYPE_INVOICE_LIST = "InvoiceList";
    const TYPE_INVOICE_LIST_WORKFLOW = "InvoiceListWorkflow";
    const TYPE_INVOICE_NEW = "InvoiceNew";
    const TYPE_BUDGET_ADD_OR_EDIT = "BudgetAddOrEdit";
    const TYPE_BUDGET_DELETE = "BudgetDelete";
    const TYPE_INVOICE_EDIT = "InvoiceEdit";
    const TYPE_INVOICE_DOCUMENT_ADD_OR_EDIT = "InvoiceDocumentAddOrEdit";
    const TYPE_INVOICE_DOCUMENT_DELETE = "InvoiceDocumentDelete";
    const TYPE_ISSUE_ADD_OR_EDIT = "IssueAddOrEdit";
    const TYPE_ISSUE_DELETE = "IssueDelete";
    const TYPE_CONTRACT_EDIT_PMS_AND_PLANONLY = "ContractEditPMSAndPlanOnly";
    const TYPE_CONTRACT_REVISION_EDIT = "ContractRevisionEdit";
    const TYPE_REGISTER_CASH_REQUEST = "RegisterCashRequest";

    /**
     *شناسه قرارداد
     * @var type 
     */
    public $cid;
    
    /**
     * نوع دسترسی
     *  
     * @var string fro sample value 'ContractList' 
     */
    public $accessType;
    
    



    public function execute()
    {
        $this->checkParameter([
            [['cid', 'accessType'], 'required']
        ]);
        $cid = $this->cid;
        $accessType = $this->accessType;
        return ['result' => $this->existsRecord($cid, $accessType), 'message' => "You do not have access in state ".static::TYPE_CONTRACT_REVISION_EDIT.' mode'];
    }
    
    
    public function existsRecord($cid, $accessType)
    {
//        echo $accessType;
        $contractTable = Contract::tableName();
        
//        return $contractModel= Contract::find()
//                    ->contractAccess($accessType)
//                    ->andWhere("$contractTable.ContractID=$cid")
//                    ->exists();

        $subContractorQuery= Contract::find()
                    ->contractAccess($accessType)
                    ->andWhere(["$contractTable.ContractID"=>$cid]);
        
        if($accessType =='ContractRevisionEdit') {
            return $subContractorQuery->exists();
        } else{
            $subContractorQuery->createCommand()->rawSql;
        }
//        echo $subContractorQuery->createCommand()->rawSql;
//        die();
        
        /*return $mainContractorQuery= Contract::find()
                    ->maincontract($accessType)
                    ->union($subContractorQuery)
                    ->andWhere(["$contractTable.ContractID"=>$cid])
                    ->exists();*/
        
        $mainContractorQuery= Contract::find()
                    ->maincontract($accessType)
                    ->union($subContractorQuery)
                    ->andWhere(["$contractTable.ContractID"=>$cid])
                    ;
//        echo $mainContractorQuery->createCommand()->rawSql;
//        die();
        return $mainContractorQuery->exists();
        
   
        echo '__________________________________________________/n';
        echo $mainContractorQuery;
        
//        echo (new \yii\db\Query())->from($subContractorQuery)->union($mainContractorQuery)->createCommand()->rawSql;
            die();
        return $contractModel= Contract::find()
            ->contractAccess($accessType)
             ->andWhere(["$contractTable.ContractID"=>$cid])
            ->exists();
    }
    
    public function setInvid($val)
    {
        $invoiceModel= \app\modules\invoice\models\InvoiceEdit::find()
                ->where(['InvoiceID'=>$val])
                ->one();
        if(isset($invoiceModel)){
            $this->cid = $invoiceModel->ContractID;
        }
    }
    
    
    public function setRid($val)
    {
        $RevisionModel= \app\modules\contract\models\ContractRevision::find()
                ->where(['RiderID'=>$val])
                ->one();
        if(isset($RevisionModel)){
            $this->cid = $RevisionModel->ContractID;
        }
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
    
    public function setPcdid($val) {
        $preContractDocumentModel= \app\modules\contract\models\PreContractDocument::find()
                ->where(['preContractDocumentId' => $val])
                ->one();
        if(isset($preContractDocumentModel)){
            $this->cid = $preContractDocumentModel->contractIdRef;
        }
    }
    
    public function setInvoicePeid($val) {
        $procesEntityModel = \app\modules\workflow\models\ProcessEntity::find()
                ->where(['ProcessEntityID'=>$val])
                ->one(); 
        if(!isset($procesEntityModel)){
            throw new \yii\web\BadRequestHttpException;
        }
        $this->cid = $procesEntityModel->invoice->contract->ContractID;
    }
    
    
}
