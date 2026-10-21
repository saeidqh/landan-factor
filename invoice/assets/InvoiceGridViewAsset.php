<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\modules\invoice\assets;

/**
 * Description of InvoiceListAsset
 *
 * @author Akbar joudy <akbar.joody@gmail.com>
 */
class InvoiceGridViewAsset extends \yii\web\AssetBundle{
    public $sourcePath = '@app/modules/invoice/bower';
    
    public $css = [
        'css/invoie-gridview.css',
    ];
    
    public $jsOptions = [
        'position' => \yii\web\View::POS_HEAD,
    ];
}
