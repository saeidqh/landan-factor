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
class InvoiceListAsset extends \yii\web\AssetBundle{
    public $sourcePath = '@app/modules/invoice/bower';
    
    public $js = [
        'js/invoice.js',
    ];
    public $css = [
        'css/invoice.css'
    ];
    
    public $jsOptions = [
        'position' => \yii\web\View::POS_END,
    ];
}
