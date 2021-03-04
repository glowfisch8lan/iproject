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

    ];

    public $depends = [
        'yii\web\YiiAsset',

        'yii\bootstrap4\BootstrapPluginAsset',

        'app\modules\system\assets\FontAwesomeAsset',
        'app\modules\system\assets\PreloaderAsset',
        'app\modules\system\assets\PopperAsset',
        'app\modules\system\assets\FileUploadAsset',

    ];

}

