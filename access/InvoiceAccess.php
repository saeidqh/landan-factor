<?php

namespace app\access;

use Yii;
use \app\modules\invoice\models\InvoiceEdit;
use app\modules\OBS\models\Obsnew;

/**
 * Description of FormActionAccess
 *
 * @author joudi_a
 */
class InvoiceAccess extends AccessAbstract {

    public $userId;
    public $obsId;
    public $invoiceId;

    public function execute() {
        $this->userId = !empty($this->userId) ? $this->userId : Yii::$app->user->id;
        $this->obsId = !empty($this->obsId) ? $this->obsId : Yii::$app->session->get('ObsPosition');

        $this->checkParameter([
            [['invoiceId'], 'required']
        ]);
        
        $obsModel = Obsnew::findOne($this->obsId);
        
        $invoiceTable = InvoiceEdit::tableName();
        $contractTable = \app\modules\contract\models\Contract::tableName();
        $query = InvoiceEdit::find()
                ->innerJoin($contractTable, "$contractTable.ContractID=$invoiceTable.ContractID")
                ->where(["$invoiceTable.InvoiceID" => $this->invoiceId])
                ->andWhere("$invoiceTable.AmountStatus <> 5")
                ->andWhere("$contractTable.SignerPersonIDRef=$obsModel->companyIdRef")
                ->one();

//        echo $query->createCommand()->rawSql;
//        $query = $query->one();
        if (!$query) {
            return ['result' => false, 'message' => "You dont edit this Amount!"];
        } else {
            return ['result' => true];
        }
    }

}
