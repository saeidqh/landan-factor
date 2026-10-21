<?php

namespace app\modules\invoice\models;

/**
 * This is the ActiveQuery class for [[InvoicePksk]].
 *
 * @see InvoicePksk
 */
class InvoicePkskQuery extends \yii\db\ActiveQuery {

    public function invoice($invid) {
        return $this->andWhere(['InvoiceIDRef' => $invid]);
    }

    public function all($db = null) {
        return parent::all($db);
    }

    public function one($db = null) {
        return parent::one($db);
    }

}
