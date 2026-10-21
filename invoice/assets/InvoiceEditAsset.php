<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\modules\invoice\assets;

/**
 * Description of InvoiceEditAsset
 *
 * @author Akbar Joudy <akbar.joody@example.com>
 */
class InvoiceEditAsset extends \yii\web\AssetBundle{
    public $sourcePath = '@app/modules/invoice/bower';
    
    
    public $css = [
        'css/invoice-edit.css'
    ];
    
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
