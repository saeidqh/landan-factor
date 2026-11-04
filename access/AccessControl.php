<?php

namespace app\access;

use Yii;

/**
 * Description of AccessControlNew
 * 
 *  Check access width method ::isAccess()
 *  width out error excption
 *  \app\access\AccessControl::isAccess([
 *      'and',
 *      ['obs', 'check' => 'isAdmin'],
 *      ['formAction', 'aid' => 131]
 *      , 'errorException' => false
 *   ]);
 * 
 * 
 * check with url
 * \app\access\AccessControl::can('home/dashboard/index');//boolean
 * 
 * @author joudi_a
 */
class AccessControl {

    private $_isAccess;
    public $_where;

    /**
     * call from url || view
     * @var type 
     */
    private static $call = 'url';

    public function __call($name, $arguments) {
        // <editor-fold desc="__call" defaultstate="collapsed" >
        $className = trim('\app\access\ ') . ucwords($name) . 'Access';
        $arg = \yii\helpers\ArrayHelper::getValue($arguments, 0);
        if (class_exists($className)) {
            $obj = new $className($arg);
            self::$_isAccess = $obj->execute();

            return $this;
        } else {
            if (strpos($name, 'and') !== false) {
                $array = [
                    'condition' => 'and',
                    'accessControl' => [
                        'class' => str_replace('And', '', $className),
                    ],
                ];
                if (!empty($arg)) {
                    foreach ($arg as $key => $value) {
                        $array['accessControl'][$key] = $value;
                    }
                }
                $this->_where [] = $array;
                return $this;
            } else if (strpos($name, 'or') !== false) {
                $array = [
                    'condition' => 'or',
                    'accessControl' => [
                        'class' => str_replace('Or', '', $className),
                    ],
                ];
                if (!empty($arg)) {
                    foreach ($arg as $key => $value) {
                        $array['accessControl'][$key] = $value;
                    }
                }
                $this->_where [] = $array;
                return $this;
            } else {
                throw new \yii\web\HttpException(404, "Class Not Found");
            }
        }
    }

    // </editor-fold> 


    public static function __callStatic($name, $arguments) {
        // <editor-fold desc="__callStatic" defaultstate="collapsed" >        
        $className = trim('\app\access\ ') . ucwords($name) . 'Access';
        if (class_exists($className)) {
            $arg = \yii\helpers\ArrayHelper::getValue($arguments, 0);
            $obj = new $className($arg);

            $accessControl = new AccessControl();
            $accessControl->_isAccess = $obj->execute();
            isset($arg['errorException']) ? $accessControl->_isAccess['errorException'] = $arg['errorException'] : null;
            return $accessControl;
        }
    }

    // </editor-fold> 

    /**
     * get $obsId
     * @return boolean
     */
    public static function isCandoAdmin($obsId) {
        if ($obsId == 606) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * get $companyId
     * @return boolean
     */
    public static function isAdmin($obsId = 0) {
        if ($obsId == 0) {
            $obsId = Yii::$app->session->get('ObsPosition');
        }
        return \app\modules\OBS\models\Company::find()->andWhere(["adminIdRef" => $obsId])->exists();
    }

    /**
     * 
     * @return boolean
     */
    public function isAccess($array = []) {
        // <editor-fold desc="isAccess" defaultstate="collapsed" >
        if (!empty($array)) {
            $namespace = trim('\app\access\ ');
            $className = $namespace . ucwords($array[0]) . 'Access';

            //one item
            if (class_exists($className)) {
                $res = static::check($array);
                if (isset($array['errorException']) && !$array['errorException']) {
                    return $res['result'];
                } else if (self::$call == 'url' && $res['result'] === false) {
                    $message = $res;
                    return static::BadRequestHttpException([$message]);
                } else {
                    return $res['result'];
                }
            } else {
                $result = [];
                $conditionMain = $array[0];
                unset($array[0]);
                foreach ($array as $arr) {
                    $result[] = static::check($arr);
                }

                $results = \yii\helpers\ArrayHelper::getColumn($result, 'result');
                if ($conditionMain == 'or') {
                    if (in_array(true, $results)) {
                        if (isset($array['resultType']) && $array['resultType'] == 'array') {
                            return ['result' => true];
                        } else {
                            return true;
                        }
                    }

                    if (isset($array['errorException']) || self::$call == 'view') {
                        return false;
                    } else if (self::$call == 'url' && !isset($array['resultType'])) {
                        return static::BadRequestHttpException($result);
                    } else {
                        if (isset($array['resultType']) && $array['resultType'] == 'array') {
                            $message = isset($res['message']) ? $res['message'] : '';
                            return ['result' => false, 'message' => $message];
                        } else {
                            return false;
                        }
                    }
                }

                if ($conditionMain == 'and') {
                    foreach ($results as $res) {
                        if ($res === false) {
                            if (isset($array['resultType']) && $array['resultType'] == 'array') {
                                return $result;
                            } else {
                                if (isset($array['errorException']) || self::$call == 'view') {

                                    return false;
                                } else if (self::$call == 'url' && !isset($array['resultType'])) {
                                    return static::BadRequestHttpException($result);
                                }
                            }
                        }
                    }

                    if (isset($array['resultType']) && $array['resultType'] == 'array') {
                        return ['result' => true];
                    } else {
                        return true;
                    }
                }
            }
        } else if (empty($this->_where)) {
            if (isset($this->_isAccess['errorException']) && !$this->_isAccess['errorException']) {
                return $this->_isAccess['result'];
            } else if (self::$call == 'url' && !isset($this->_isAccess['resultType'])) {
                if (!$this->_isAccess['result']) {
                    $res = [$this->_isAccess];
                    return static::BadRequestHttpException($res);
                }
            } else if (self::$call == 'view' && !isset($this->_isAccess['resultType'])) {
                return $this->_isAccess['result'];
            }
            return true;
        } else {
            if (is_array($this->_where)) {
                $result [] = [
                    'access' => $this->_isAccess,
                    'condition' => null,
                ];

                foreach ($this->_where as $where) {
                    //بدست آوردن دسترسی شرط های مورد نظر
                    $accessControlArray = $where['accessControl'];
                    $class = $accessControlArray['class'];
                    unset($accessControlArray['class']);

                    $accessControlObj = new $class($accessControlArray);
                    $res = $accessControlObj->execute();
                    $result[] = ['access' => $res, 'condition' => $where['condition']];
                }

                //پردازش برای ارسال نتیجه درستی یا نادرستی
                $isacess = false;
                for ($i = 0; $i < count($result); $i++) {
                    $condition = isset($result[$i + 1]) ? $result[$i + 1]['condition'] : null;
                    if ($condition == 'or') {
                        if ($result[$i]['access'] or ( isset($result[$i + 1]) && $result[$i + 1]['access'])) {
                            $isacess = true;
                        } else {
                            $isacess = false;
                        }
                    } elseif ($condition == 'and') {
                        if ($result[$i]['access'] and ( isset($result[$i + 1]) && $result[$i + 1]['access'])) {
                            $isacess = true;
                        } else {
                            $isacess = false;
                        }
                    }
                }
                return $isacess;
            }
        }
    }

    // </editor-fold>

    private static function check($item = []) {
        $namespace = trim('\app\access\ ');
        $className = $namespace . ucwords($item[0]) . 'Access';

        if (class_exists($className)) {
            unset($item[0]);
            $param = [];
            foreach ($item as $key => $val) {
                $param[$key] = $val;
            }
            $objAccess = new $className($param);
            return $objAccess->execute();
        } else {
            if (is_array($item)) {
                $item = array_merge($item, ['resultType' => 'array']);

                return static::isAccess($item); //isAccess($condition)
            }
        }
    }

    /**
     * یک آدرس می گیرد و دسترسیش را نسبت به آرایه ای که از کنترولر می گیرد
     * چکی می کند
     * @param type $ruleOrUrl
     * @param type $extraData
     * @return type
     */
    public static function can($ruleOrUrl = '', $extraData = []) {
        // <editor-fold desc="can" defaultstate="collapsed" >

        $ex = explode('/', $ruleOrUrl);
        if (is_array($ex) && count($ex) == 3) {
            $moduleName = $ex[0];
            $controllerName = $ex[1];
            $actionName = $ex[2];


            $module = Yii::$app->getModule($moduleName);
            if ($module === null) {
                throw new \yii\web\HttpException(404, "Module $moduleName:: Not Found");
            }
            self::$call = 'view';

            $controllerObj = $module->createController($controllerName)[0];
//            var_dump($controllerObj);
//            die();
            if (is_object($controllerObj)) {
                $behaviors = $controllerObj->behaviors();
                foreach ($behaviors['access']['rules'] as $rules) {
                    $actions = $rules['actions'];
                    foreach ($actions as $action) {
                        if ($action == $actionName) {
                            if (isset($rules['matchCallback'])) {
                                $matchCallback = $rules['matchCallback'];
//                                if ($actionName == "createportfolio") {
//                                    var_dump($extraData);
//                                    die();
//                                }

                                $result = $matchCallback(null, null, $extraData);

                                return isset($result['result']) ? $result['result'] : $result;
                            } else {
                                if (isset($rules['roles'][0]) && ($rules['roles'][0] == '@' && !Yii::$app->user->isGuest)) {
                                    return true;
                                }
                                return false;
                            }
                        }
                    }
                }
                throw new \yii\web\HttpException(404, "Action ::$actionName() Not Found");
            } else {
                throw new \yii\web\HttpException(404, "Controller $controllerName:: Not Found");
            }
        }
    }

    // </editor-fold> 



    public function BadRequestHttpException($result) {
        $messages = '';

        foreach ($result as $res) {
            if (isset($res['message'])) {
                $messages.=$res['message'] . "\n";
            } else {
                if (is_array($res)) {
                    foreach ($res as $r) {
                        if (isset($r['message'])) {
                            $messages.=$r['message'] . "\n";
                        }
                    }
                }
            }
        }

        if (Yii::$app->request->isPjax) {
            echo \yii\helpers\Json::encode(['result' => false, 'message' => $messages]);
            Yii::$app->end();
        }

        $controller = new \app\modules\home\controllers\ErrorController('mytest', Yii::$app->controller->module);
        $controller->viewPath = '@app/modules/home/views/error/';


        echo $controller->runAction('access-denid', ['name' => 'Access Denid!!', 'title' => 'action', 'message' => $messages]);
        Yii::$app->end();
    }

}
