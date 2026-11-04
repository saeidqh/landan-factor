<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\access;

/**
 * Description of actionParam
 *
 * @author joudi_a
 */
class ActionParamAccess extends AccessAbstract {

    public $aid;
    public $pt;
    public $val1;

    /**
     * InvoiceAmount
     * InvoiceInfo
     * Invoice Document
     * @var type 
     */
    public $for;

    public function execute() {
        $this->checkParameter([
            [['aid', 'pt'], 'required']
        ]);

        $query = \app\modules\workflow\models\ActionParam::find()
                ->where(['actionIdRef' => $this->aid, 'paramTitle' => $this->pt]);

        if (isset($this->val1)) {
            $query->andWhere(['value1' => $this->val1]);
        }
//        echo $query->createCommand()->rawSql;

        $model = $query->one();
        if (!empty($model)) {
            $access = array_map('intval', str_split($model->value1));
        } else {
            $access = -1;
        }

        if ($this->for == "EditInvoiceAmount") {
            if ($access[1] == 3) {
//            if($access[0] == 3 || $access[0] == 2){
                return ['result' => true];
            } else {
                return ['result' => false];
            }
        } else if ($this->for == "ViewInvoiceAmount") {
            if ($access[1] == 2) {
                return ['result' => true];
            } else {
                return ['result' => false];
            }
        } else if ($this->for == "EditInvoice") {
            if ($access[2] == 3) {
                return ['result' => true];
            } else {
                return ['result' => false];
            }
        } else if ($this->for == "ViewInvoice") {
            if ($access[2] == 2) {
                return ['result' => true];
            } else {
                return ['result' => false];
            }
        } else if ($this->for == "EditInvoiceDocument") {
            if ($access[0] == 3 || $access[0] == 2) {
                return ['result' => true];
            } else {
                return ['result' => false];
            }
        } else if ($this->for == "ViewInvoiceDocument") {
            if ($access[0] == 2 || $access[0] == 3) {
                return ['result' => true];
            } else {
                return ['result' => false];
            }
        } else if ($this->for == "automation") {
            return $this->checkActionParamAutomation($model);
        } else if ($this->for == "network") {
            return $this->checkActionParamNetwork($model);
        } else if ($this->pt == "tenderAccessId") {
            return $this->checkActionParamTender($access);
        } else if ($this->pt == "contractClosingDocumentAccessId") {
            return $this->checkActionParamContractClosingDocument($access);
        }
        return ['result' => isset($model)];
    }

    public function checkActionParamTender($access) {
        if ($this->for == "EditCallingDocument") {
            if ($access[9] == 3) {
                return ['result' => true];
            } else {
                return ['result' => false];
            }
        } else if ($this->for == "ViewCallingDocument") {
            if ($access[9] == 2 || $access[9]) {
                return ['result' => true];
            } else {
                return ['result' => false];
            }
        } else if ($this->for == "EditTender") {
            if ($access[8] == 3) {
                return ['result' => true];
            } else {
                return ['result' => false];
            }
        } else if ($this->for == "ViewTender") {
            if ($access[8] == 2 || $access[8] == 3) {
                return ['result' => true];
            } else {
                return ['result' => false];
            }
        } else if ($this->for == "EditTenderWbs") {
            if ($access[7] == 3) {
                return ['result' => true];
            } else {
                return ['result' => false];
            }
        } else if ($this->for == "ViewTenderWbs") {
            if ($access[7] == 2 || $access[7] == 3) {
                return ['result' => true];
            } else {
                return ['result' => false];
            }
        } else if ($this->for == "EditTenderDocument") {
            if ($access[6] == 3) {
                return ['result' => true];
            } else {
                return ['result' => false];
            }
        } else if ($this->for == "ViewTenderDocument") {
            if ($access[6] == 2 || $access[6] == 3) {
                return ['result' => true];
            } else {
                return ['result' => false];
            }
        } else if ($this->for == "EditTenderActivities") {
            if ($access[5] == 3) {
                return ['result' => true];
            } else {
                return ['result' => false];
            }
        } else if ($this->for == "ViewTenderActivities") {
            if ($access[5] == 2 || $access[5] == 3) {
                return ['result' => true];
            } else {
                return ['result' => false];
            }
        } else if ($this->for == "EditTenderContractStrategy") {
            if ($access[4] == 3) {
                return ['result' => true];
            } else {
                return ['result' => false];
            }
        } else if ($this->for == "ViewTenderContractStrategy") {
            if ($access[4] == 2 || $access[4] == 3) {
                return ['result' => true];
            } else {
                return ['result' => false];
            }
        } else if ($this->for == "EditTenderFinancing") {
            if ($access[3] == 3) {
                return ['result' => true];
            } else {
                return ['result' => false];
            }
        } else if ($this->for == "ViewTenderFinancing") {
            if ($access[3] == 2 || $access[3] == 3) {
                return ['result' => true];
            } else {
                return ['result' => false];
            }
        } else if ($this->for == "EditTenderBidder") {
            if ($access[2] == 3) {
                return ['result' => true];
            } else {
                return ['result' => false];
            }
        } else if ($this->for == "ViewTenderBidder") {
            if ($access[2] == 2 || $access[2] == 3) {
                return ['result' => true];
            } else {
                return ['result' => false];
            }
        } else if ($this->for == "EditTenderIndexPrice") {
            if ($access[1] == 3) {
                return ['result' => true];
            } else {
                return ['result' => false];
            }
        } else if ($this->for == "ViewTenderIndexPrice") {
            if ($access[1] == 2 || $access[1] == 3) {
                return ['result' => true];
            } else {
                return ['result' => false];
            }
        } else if ($this->for == "EditTenderCommission") {
            if ($access[0] == 3) {
                return ['result' => true];
            } else {
                return ['result' => false];
            }
        } else if ($this->for == "ViewTenderCommission") {
            if ($access[0] == 2 || $access[0] == 3) {
                return ['result' => true];
            } else {
                return ['result' => false];
            }
        } else {
            return ['result' => false];
        }
    }

    public function checkActionParamContractClosingDocument($access) {
        if ($this->for == "EditClosingDocument") {
            if ($access[0] == 3) {
                return ['result' => true];
            } else {
                return ['result' => false];
            }
        }
    }
    
    public function checkActionParamAutomation($automation) {
        if(isset($automation)) {
            $result = ($automation->value2 == 'true') ? true : false;
            return ['result' => $result];
        }
        return ['result' => false];
    }
    
    public function checkActionParamNetwork($automation) {
        if(isset($automation)) {
            $result = ($automation->value2 == 'true') ? true : false;
            return ['result' => $result];
        }
        return ['result' => false];
    }

}
