<?php
namespace app\access;

/**
 * قوانین یک کلاس دسترسی
 * باید این متدها در کلاس های قانون مشتق و یا بازنویسی شود
 * Description of AccessAbstract
 *
 * @author joudi_a
 */
abstract  class AccessAbstract extends \yii\base\Component{
    
    public $errorException = false;


    public function execute() {}
    
    /**
     * برای چک کردن داده های ورودی می باشد
     * مثال
     *  $this->checkParameter([
     *       [
     *           ['obsPosition', 'actionId'],
     *           'required'
     *       ],
     *   ], $data);
     * 
     * @param type $parameters
     * @param type $data
     * @throws \yii\web\HttpException
     */
    public function checkParameter($parameters = []) 
    {
        if(is_array($parameters))
        {
            for($i=0; $i<count($parameters); $i++)
            {
                $params = $parameters[$i][$i];
                $validate = $parameters[$i][$i+1];
                if($validate == 'required')
                {
                    foreach ($params as $param)
                    {
                        if(!isset($this->$param))
                        {      
                            throw new \yii\web\HttpException(403, "Missing required parameters: $param");
                        }
                    }
                }
            }
            return true;
        }
    }
}
