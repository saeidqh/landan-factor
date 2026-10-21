<?php

namespace app\modules\invoice\models;

/**
 * This is the ActiveQuery class for [[InvoiceAmountEdit]].
 *
 * @see InvoiceAmountEdit
 */
class InvoiceAmountQuery extends \yii\db\ActiveQuery {

    public function contractDebtor($cid,$amountStatusString) {
        $invoiceAmountTable= \app\modules\invoice\models\InvoiceAmountEdit::tableName();
        $where = "";
        if ($amountStatusString == 'Approved')
            $where = InvoiceEdit::tableName().".AmountStatus = 5";

        if ($amountStatusString == 'Checking')
            $where = InvoiceEdit::tableName().".AmountStatus = 1";

        if ($amountStatusString == 'Approved Or Checking')
            $where = InvoiceEdit::tableName().".AmountStatus > 0";
        $this->andWhere($where);

        return $this->andWhere("(($invoiceAmountTable.Income1 is not null and $invoiceAmountTable.Income1<>0) OR ($invoiceAmountTable.Income2 is not null and $invoiceAmountTable.Income2<>0))")
                        ->andWhere($where)
                ->andWhere("$invoiceAmountTable.OwnerContractIDRef=:cid")
                ->addParams([':cid'=>$cid]);
    }

    public function contractCreditor($cid,$amountStatusString) {
        $invoiceAmountTable= \app\modules\invoice\models\InvoiceAmountEdit::tableName();
        $where = "";
        if ($amountStatusString == 'Approved')
            $where = InvoiceEdit::tableName().".AmountStatus = 5";

        if ($amountStatusString == 'Checking')
            $where = InvoiceEdit::tableName().".AmountStatus = 1";

        if ($amountStatusString == 'Approved Or Checking')
            $where = InvoiceEdit::tableName().".AmountStatus > 0";
        $this->andWhere($where);

        return $this
                ->andWhere("(($invoiceAmountTable.Income1 is not null and $invoiceAmountTable.Income1<>0) OR ($invoiceAmountTable.Income2 is not null and $invoiceAmountTable.Income2<>0))")
                        ->andWhere($where)
                        ->andWhere([InvoiceEdit::tableName().'.ContractID' => $cid]);
    }

    public function invoice($invid) {
        return $this->andWhere(['InvoiceID' => $invid]);
    }

    public function all($db = null) {
        return parent::all($db);
    }

    public function one($db = null) {
        return parent::one($db);
    }

}
