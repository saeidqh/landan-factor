<?php

namespace app\access;

use app\modules\cashRequest\models\CashRequestAmount;
use app\modules\cashRequest\models\CashRequestDocument;
use app\modules\workflow\models\ProcessEntity;
use app\modules\workflow\models\Process;

/**
 * Description of ProcessEntity
 *
 * @author joudi_a
 */
class ProcessEntityAccess extends AccessAbstract {

    public $peid = 0;
    public $type = 'all';

    public function execute() {
        $res = ['result' => false];
        $this->checkParameter([
            [['peid', 'type'], 'required']
        ]);

        $peid = $this->peid;
        $type = $this->type;

        $processEntityModel = ProcessEntity::find()
                ->andWhere(["ProcessEntityID" => $peid])
                ->one();
        /* if ($processEntityModel->TenderIDRef > 0) {
          $processEntityModel2 = ProcessEntity::find()
          ->andWhere(["ProcessEntityID"=>$peid]);

          echo $processEntityModel2->tender($type)->createCommand()->rawSql;
          } */

        if (!$processEntityModel) {
            return $res;
        }
        // این تکه کد جدیدجهت بررسی این درنظر گرفته شده که کاربر جاری کاربر ایجاد کنند پروسس باشد
        if ($type == 'CheckUser') {
            $res['result'] = \app\components\Utility::userIsProcessEntityUser($peid);
            return $res;
        }
        $processEntityQuery = ProcessEntity::find()->andWhere(['ProcessEntityID' => $peid]);
        if ($processEntityModel->InvoiceIDRef > 0) {
            if ($processEntityQuery->invoice($type)->count() > 0) {
                $res['result'] = true;
            } else {
                $res['result'] = false;
            }
            return $res;
        } else if ($processEntityModel->CashRequestIDRef > 0) {
            if ($processEntityQuery->cashRequest($type)->count() > 0) {
                $res['result'] = true;
            } else {
                $res['result'] = false;
            }
            return $res;
        } else if ($processEntityModel->WorkflowContractIDRef > 0) {
            if ($processEntityQuery->contract($type)->count() > 0) {
                $res['result'] = true;
            } else {
                $res['result'] = false;
            }
            return $res;
        } else if ($processEntityModel->BudgetIDRef > 0) {
            if ($processEntityQuery->budget($type)->count() > 0) {
                $res['result'] = true;
            } else {
                $res['result'] = false;
            }
            return $res;
        } else if ($processEntityModel->warrantyIdRef > 0) {
            if ($processEntityQuery->warranty($type)->count() > 0) {
                $res['result'] = true;
            } else {
                $res['result'] = false;
            }
            return $res;
        } else if ($processEntityModel->capitalBalanceIdRef > 0) {
            if ($processEntityQuery->capital($type)->count() > 0) {
                $res['result'] = true;
            } else {
                $res['result'] = false;
            }
            return $res;
        } else if ($processEntityModel->debitmemoIdRef > 0) {
            if ($processEntityQuery->debitmemo($type)->count() > 0) {
                $res['result'] = true;
            } else {
                $res['result'] = false;
            }
            return $res;
        } else if ($processEntityModel->claimIdRef > 0) {
            if ($processEntityQuery->claim($type)->count() > 0) {
                $res['result'] = true;
            } else {
                $res['result'] = false;
            }
            return $res;
        } else if ($processEntityModel->permissionId > 0) {
            if ($processEntityQuery->permission($type)->count() > 0) {
                $res['result'] = true;
            } else {
                $res['result'] = false;
            }
            return $res;
        } else if ($processEntityModel->actualProgressAnnualBudgetIdRef > 0) {
            if ($processEntityQuery->actualProgress($type)->count() > 0) {
                $res['result'] = true;
            } else {
                $res['result'] = false;
            }
            return $res;
        } else if ($processEntityModel->PersonID > 0) {
            if ($processEntityQuery->sourcing($type)->count() > 0) {
                $res['result'] = true;
            } else {
                $res['result'] = false;
            }
            return $res;
        } else if ($processEntityModel->TenderIDRef > 0) {
            if ($processEntityQuery->tender($type)->count() > 0) {
                $res['result'] = true;
            } else {
                $res['result'] = false;
            }
            return $res;
        } else if ($processEntityModel->ContractClosingDocumentIDRef > 0) {
            if ($processEntityQuery->contractClosingDocument($type)->count() > 0) {
                $res['result'] = true;
            } else {
                $res['result'] = false;
            }
            return $res;
        } else if ($processEntityModel->AutomationIDRef > 0) {
            if ($processEntityQuery->automation($type)->count() > 0) {
                $res['result'] = true;
            } else {
                $res['result'] = false;
            }
            return $res;
        } else if ($processEntityModel->NetworkIDRef > 0) {
            if ($processEntityQuery->network($type)->count() > 0) {
                $res['result'] = true;
            } else {
                $res['result'] = false;
            }
            return $res;
        } else if ($processEntityModel->process->ProccessTypeIDRef == Process::EMPLOYEESETTLEMENT_PROCESS_TYPE) {
            if ($processEntityQuery->employeeSettlement($type)->count() > 0) {
                $res['result'] = true;
            } else {
                $res['result'] = false;
            }
            return $res;
        } else if ($processEntityModel->process->ProccessTypeIDRef == Process::EMPLOYEE_MEMBERSHIP_PROCESS_TYPE) {
            if ($processEntityQuery->employeeMembership($type)->count() > 0) {
                $res['result'] = true;
            } else {
                $res['result'] = false;
            }
            return $res;
        } else if ($processEntityModel->process->ProccessTypeIDRef == Process::FOREIGN_MISSION_PROCESS_TYPE) {
            if ($processEntityQuery->foreignMission($type)->count() > 0) {
                $res['result'] = true;
            } else {
                $res['result'] = false;
            }
            return $res;
        }else if ($processEntityModel->process->ProccessTypeIDRef == Process::STAMP_DOCUMENT_PROCESS_TYPE) {
            if ($processEntityQuery->stampDocument($type)->count() > 0) {
                $res['result'] = true;
            } else {
                $res['result'] = false;
            }
            return $res;
        }else if ($processEntityModel->process->ProccessTypeIDRef == Process::REFUND_STAMP_PROCESS_TYPE) {
            if ($processEntityQuery->refundStamp($type)->count() > 0) {
                $res['result'] = true; 
            }else {
                $res['result'] = false;
            }
            return $res;
        }
        else {
            return $res;
        }
    }

    /**
     * cashRequest amount Id
     * @param type $val
     */
    public function setAid($val) {
        $cashRequestAmount = CashRequestAmount::find()
                ->where(['ID' => $val])
                ->one();
        if (isset($cashRequestAmount)) {
            $this->peid = $cashRequestAmount->cashRequest->processEntity->ProcessEntityID;
        }
    }

    /**
     * 
     * @param type $val
     */
    public function setCrdid($val) {
        $cashRequestDocument = CashRequestDocument::find()->where(['ID' => $val])->one();
        if (isset($cashRequestDocument)) {
            $this->peid = $cashRequestDocument->cashRequest->processEntity->ProcessEntityID;
        }
    }

}
