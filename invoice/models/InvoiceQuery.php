<?php

namespace app\modules\invoice\models;

use Yii;
use app\components\CandoHelper;
use app\modules\contract\models\Contract;
/**
 * This is the ActiveQuery class for [[InvoiceEdit]].
 *
 * @see InvoiceEdit
 */
class InvoiceQuery extends \yii\db\ActiveQuery {

    public function contract($cid) {
        return $this->andWhere(['ContractID' => $cid]);
    }
    public function debitmemoCreate($cid) {
        return $this->andWhere(['ContractID' => $cid])
                ->andWhere('AmountStatus=5');
    }
    public function debitmemo($debitmemoId) {
        $invoiceTable= \app\modules\invoice\models\InvoiceEdit::tableName();
        $invoiceAmountTable= \app\modules\invoice\models\InvoiceAmountEdit::tableName();
        $debitmemoAmountTable= \app\modules\debitmemo\models\DebitmemoAmount::tableName();
        
        return $this->andWhere("$invoiceTable.InvoiceID in (select $invoiceAmountTable.InvoiceID from $invoiceAmountTable inner join $debitmemoAmountTable on $debitmemoAmountTable.invoiceAmountIdRef=$invoiceAmountTable.ID where $debitmemoAmountTable.debitmemoIdRef=$debitmemoId and ($debitmemoAmountTable.isDeleted is null or $debitmemoAmountTable.isDeleted!=1))");
    }

    public function poReport($type) {
        switch ($type) {
            case 'PO':
                return $this->andWhere(Contract::tableName(). '.ContractType=15 and '.InvoiceEdit::tableName().'.InvoiceType<=5');
            case 'LC':
                return $this->andWhere(Contract::tableName().'.ContractType=15 and '.InvoiceEdit::tableName().'.InvoiceType=17');
            case 'Logestic':
                return $this->andWhere(Contract::tableName().'.ContractType=15 and '.InvoiceEdit::tableName().'.InvoiceType=18');
            case 'Insurance':
                return $this->andWhere(Contract::tableName().'.ContractType=15 and '.InvoiceEdit::tableName().'.InvoiceType=19');
            case 'ServieceFee':
                return $this->andWhere(Contract::tableName().'.ContractType=15 and '.InvoiceEdit::tableName().'.InvoiceType=20');
            case 'Advance':
                return $this->andWhere(Contract::tableName().'.ContractType=15 and '.InvoiceEdit::tableName().'.InvoiceType=21');
        }
    }


    public function all($db = null) {
        return parent::all($db);
    }

    public function one($db = null) {
        return parent::one($db);
    }

}
