<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
    public $sourcePath = '@webroot/resources/site/';
    public $baseUrl = '@webroot/resources/site/';
    public $publishOptions = [
        //'forceCopy' => true
    ];
    public $css = [
//        'vendor/bootstrap4/css/bootstrap.min.css',
//        'vendor/flagiconcss3/css/flag-icon.min.css',
        'css/jumbotron-narrow.css'

    ];
    public $js = [
//        'vendor/bootstrap4/js/bootstrap.bundle.min.js',
//        'vendor/fontawesome5/js/solid.min.js',
        'vendor/fontawesome5/js/fontawesome.min.js'

    ];

    public $depends = [
        'yii\web\YiiAsset',
//        'app\modules\system\assets\FontAwesomeAsset'

    ];

}

