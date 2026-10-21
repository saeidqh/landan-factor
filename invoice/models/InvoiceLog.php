<?php

namespace app\modules\invoice\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "log.tblInvoices".
 *
 * @property integer $ID
 * @property integer $InvoiceID
 * @property integer $ContractID
 * @property integer $InvoiceType
 * @property string $Title
 * @property integer $RevisionNumber
 * @property integer $ParrentID
 * @property string $CutOffDateHijri
 * @property string $CutOffDate
 * @property string $Description
 * @property integer $Currency1IDRef
 * @property string $Currency1Title
 * @property integer $Currency2IDRef
 * @property string $Currency2Title
 * @property integer $userIdRef
 * @property integer $actionIDRef
 * @property string $updateHijri
 * @property string $financialDocNumber
 * @property string $financialDocDateHijri
 * @property double $approved1UsdF2
 * @property double $approved2UsdF2
 * @property double $approved1UsdF3
 * @property double $approved2UsdF3
 * @property double $approved1IrrF2
 * @property double $approved2IrrF2
 * @property double $approved1IrrF3
 * @property double $approved2IrrF3
 */
class InvoiceLog extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {

        if (Yii::$app->params['dmbs'] == 'mysql') {
            return 'invoice_table_invoice_log';
        } else {
            return 'log.tblInvoices';
        }
    }

    public static function primaryKey() {
        return ['ID'];
    }

    public static function getDb() {
        return \app\components\Utility::centralDb();
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['InvoiceID', 'ContractID', 'InvoiceType', 'RevisionNumber', 'ParrentID', 'Currency1IDRef', 'Currency2IDRef', 'userIdRef', 'actionIDRef'], 'integer'],
            [['Title', 'CutOffDateHijri', 'Description', 'Currency1Title', 'Currency2Title', 'updateHijri', 'financialDocNumber', 'financialDocDateHijri'], 'string'],
            [['CutOffDate'], 'safe'],
            [['approved1UsdF2', 'approved2UsdF2', 'approved1UsdF3', 'approved2UsdF3', 'approved1IrrF2', 'approved2IrrF2', 'approved1IrrF3', 'approved2IrrF3'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'ID' => 'ID',
            'InvoiceID' => 'Invoice ID',
            'ContractID' => 'Contract ID',
            'InvoiceType' => 'Invoice Type',
            'Title' => 'Title',
            'RevisionNumber' => 'Revision Number',
            'ParrentID' => 'Parrent ID',
            'CutOffDateHijri' => 'Cut Off Date Hijri',
            'CutOffDate' => 'Cut Off Date',
            'Description' => 'Description',
            'Currency1IDRef' => 'Currency1 Idref',
            'Currency1Title' => 'Currency1 Title',
            'Currency2IDRef' => 'Currency2 Idref',
            'Currency2Title' => 'Currency2 Title',
            'userIdRef' => 'User Id Ref',
            'actionIDRef' => 'Action Idref',
            'updateHijri' => 'Update Hijri',
            'financialDocNumber' => 'Financial Doc Number',
            'financialDocDateHijri' => 'Financial Doc Date Hijri',
            'approved1UsdF2' => 'Approved1 Usd F2',
            'approved2UsdF2' => 'Approved2 Usd F2',
            'approved1UsdF3' => 'Approved1 Usd F3',
            'approved2UsdF3' => 'Approved2 Usd F3',
            'approved1IrrF2' => 'Approved1 Irr F2',
            'approved2IrrF2' => 'Approved2 Irr F2',
            'approved1IrrF3' => 'Approved1 Irr F3',
            'approved2IrrF3' => 'Approved2 Irr F3',
        ];
    }

    public static function invoiceLogDataProvider($invid) {
        $activeQuery = static::find()->joinWith(['user', 'action', 'type'])
                ->where(['InvoiceID' => $invid]);


        $dataProvider = new ActiveDataProvider([
            'db' => \app\components\Utility::db(),
            'query' => $activeQuery,
            'pagination' => [
                'pageSize' => Yii::$app->params['pageSize'],
            ],
        ]);
        return $dataProvider;
    }

    public function getType() {
        return $this->hasOne(InvoiceTypes::className(), ['InvoiceTypeID' => 'InvoiceType']);
    }

    public function getUser() {
        return $this->hasOne(\app\modules\account\models\User::className(), ['UserID' => 'userIdRef']);
    }

    public function getAction() {
        return $this->hasOne(\app\modules\workflow\models\Action::className(), ['ActionID' => 'actionIDRef']);
    }

}
