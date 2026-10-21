<?php

namespace app\modules\invoice\models;

use Yii;
use yii\data\ActiveDataProvider;

class InvoiceDocumentSearch extends InvoiceDocument {

    public $documentType;
    public $userName;
    public $actionTitle;

    public static function getDb() {
        return \app\components\Utility::db();
    }

    public function rules() {
        return [
            [['InvoiceDocumentID','originalNumber','copyNumber', 'documentType', 'FileName', 'Description', 'InsertDateHijri', 'userName', 'actionTitle'], 'safe', 'on' => 'search'],
        ];
    }

    public function searchInvoice($params, $invid) {
        $userTable=  \app\modules\account\models\User::tableName();
        $activeQuery = \app\components\ActiveModel::find('\app\modules\invoice\models\InvoiceDocument')->invoice($invid)->joinWith(['type', 'user', 'action']);

        $dataProvider = new ActiveDataProvider([
            'query' => $activeQuery,
            'pagination' => [
                'pageSize' => Yii::$app->params['pageSize'],
            ],
        ]);
        $this->load($params);

        if ((!$this->load($params)) and $this->validate()) {
            return $dataProvider;
        }
        $activeQuery
                ->andFilterWhere(['LIKE', InvoiceDocumentType::tableName(). '.InvoiceDocumentTypeTitle', $this->documentType])
                ->andFilterWhere(['LIKE', 'FileName', $this->FileName])
                ->andFilterWhere(['LIKE', 'originalNumber', $this->originalNumber])
                ->andFilterWhere(['LIKE', 'copyNumber', $this->copyNumber])
                ->andFilterWhere(['LIKE', 'InvoiceDocumentID', $this->InvoiceDocumentID])
                ->andFilterWhere(['LIKE', 'Description', $this->Description])
//                ->andFilterWhere(['LIKE', \app\components\CandoHelper::braketTable(\app\modules\account\models\User::tableName()). '.UserName', $this->userName])
                ->andFilterWhere(['LIKE', "concat($userTable.FirstName, ' ', $userTable.LastName)", $this->userName])
                ->andFilterWhere(['LIKE', 'InsertDateHijri', $this->InsertDateHijri])
                ->andFilterWhere(['LIKE', \app\modules\workflow\models\Action::tableName(). '.ActionTitle2', $this->actionTitle]);
        return $dataProvider;
    }

}
