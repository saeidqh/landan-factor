<?php

namespace app\modules\invoice\models;

/**
 * This is the ActiveQuery class for [[InvoiceDocument]].
 *
 * @see InvoiceDocument
 */
class InvoiceDocumentQuery extends \yii\db\ActiveQuery {

    public function invoice($invid) {
        return $this->andWhere(['InvoiceIDRef' => $invid])
                ->andWhere(InvoiceDocument::tableName().'.isDeleted is null '
                        . 'or '. InvoiceDocument::tableName().'.isDeleted != 1');
    }

    public function all($db = null) {
        return parent::all($db);
    }

    public function one($db = null) {
        return parent::one($db);
    }

}
