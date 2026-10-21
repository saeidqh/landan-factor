<?php
namespace app\modules\invoice;
use Yii;
/**
 * 
 *  @author Akbar Joudy <akbar.joody@gmail.com>
 */
class InvoiceModule extends \yii\base\Module {

    public $controllerNamespace = 'app\modules\invoice\controllers';
    public $defaultRoute  = 'invoice';
  

    public function init() {
        Yii::$app->view->on(\yii\web\View::EVENT_END_BODY, function(){
            Yii::$app->view->registerAssetBundle(\app\modules\invoice\assets\InvoiceGridViewAsset::className());
        });
    }

}