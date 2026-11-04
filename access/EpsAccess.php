<?php

namespace app\access;

use Yii;

/**
 * Description of EpsAccess
 *
 * @author joudi_a
 */
class EpsAccess extends AccessAbstract {

    const VIEW_PROJECT_TYPE = "viewProject";
    const UPDATE_PROJECT_TYPE = "updateProject";
    const DELETE_PROJECT_TYPE = "deleteProject";
    const CREATE_SUB_PROJECT_TYPE = "createSubProject";
    const UPDATE_SUB_PROJECT_TYPE = "updateSubProject";
    const VIEW_SUB_PROJECT_TYPE = "viewSubProject";
    const DELETE_SUB_PROJECT_TYPE = "deleteSubProject";

    public $epsId;
    public $check;

    public function execute() {

        $check = $this->check;
        if (method_exists($this, $check)) {
            $this->checkParameter([
                [
                    ['epsId', 'check'],
                    'required'
                ],
            ]);
            return ['result' => $this->$check()];
        }
    }

    public function viewProject() {
        $companyId = Yii::$app->session->get('CompanyID');
        $epsModel = \app\modules\wbs\models\Epsnew::find()
                ->where(['EPSID' => $this->epsId])->project('view', $companyId)
                ->one();
//        die(var_dump($epsModel));
        if (!$epsModel) {
            return false;
        }
        return true;
    }

    public function updateProject() {
        $companyId = Yii::$app->session->get('CompanyID');
        $epsModel = \app\modules\wbs\models\Epsnew::find()
                ->where(['EPSID' => $this->epsId])->project('update', $companyId)
                ->one();
        if (!$epsModel) {
            return false;
        }
        return true;
    }

    public function deleteProject() {
        return false;
    }

    public function createSubProject() {
        return $this->viewProject();
    }

    public function updateSubProject() {
        $companyId = Yii::$app->session->get('CompanyID');
        $subprojectModel = \app\modules\wbs\models\Epsnew::find()
                ->where(['EPSID' => $this->epsId])
                ->one();
        if (!$subprojectModel) {
            return false;
        }
        $this->epsId = $subprojectModel->ParentEPSIDRef;


        return $this->viewProject();
    }

    public function viewSubProject() {
        return $this->updateSubProject();
    }

    public function deleteSubProject() {
        return $this->updateProject();
    }

}
