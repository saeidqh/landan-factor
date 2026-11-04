<?php
namespace app\access;

/**
 * Description of ProcessAccess
 *
 * @author joudi_a
 */
class ProcessAccess extends AccessAbstract {
    
    const PROCESS_VIEW_TYPE = "processList";
    
    const PROCESS_UPDATE_TYPE = "processUpdate";

    public $pid;
    
    public $type;


    public function execute()
    {
        $this->checkParameter([
            [['pid', 'type'], 'required']
        ]);
        
        $processTable = \app\modules\workflow\models\Process::tableName();
        $type = $this->type;
        $res = \app\modules\workflow\models\Process::find()
//                ->$type()
                ->andWhere(["$processTable.ProcessID"=>$this->pid])
                ->exists();
        return ['result' => $res];
    }
}
