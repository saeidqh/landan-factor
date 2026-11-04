<?php

namespace app\access;

use Yii;
use \app\modules\account\models\UserOBSFormAction;

/**
 * Description of FormActionAccess
 *
 * @author joudi_a
 */
class FormActionAccess extends AccessAbstract {

    public $userId;
    public $obsId;
    public $aid;

    public function execute() {
        $this->userId = !empty($this->userId) ? $this->userId : Yii::$app->user->id;
        $this->obsId = !empty($this->obsId) ? $this->obsId : Yii::$app->session->get('ObsPosition');

        $this->checkParameter([
            [['userId', 'obsId', 'aid'], 'required']
        ]);

        $userObsFormTable = UserOBSFormAction::tableName();

        $userObsFormAction = UserOBSFormAction::find()
                ->where(['UserIDRef' => $this->userId, 'OBSCategory3IDRef' => $this->obsId, 'FormActionIDRef' => $this->aid, 'AccessValue' => 1])
                ->andWhere("($userObsFormTable.isDeleted is null or $userObsFormTable.isDeleted<>1)")
                ->one();
        if (!$userObsFormAction) {
            return ['result' => false, 'message' => "You need Action Id <span style='color: #242b14;border-radius: 5px;background: #ffffff;padding: 2px;    box-shadow: 2px 3px 6px 0 #463636;line-height: 1.5;'>$this->aid</span>"];
        } else {
            return ['result' => true];
        }
    }

}
