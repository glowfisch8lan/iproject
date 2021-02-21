<?php
namespace app\modules\system;
use yii\web\AssetBundle;

class SystemAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/system/assets';
    public $baseUrl = '@app/modules/system/assets';
    public $publishOptions = [
        //'forceCopy' => true //отключает кеширование
    ];
    public $css = [
//        'vendor/fontawesome/4.7.0/css/font-awesome.min.css',
//        'vendor/bootstrap4/css/bootstrap.min.css',
    ];
	public $js = [
////        'vendor/base64/base64.js',
//        'vendor/lzw/lzw.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',

        'yii\bootstrap4\BootstrapPluginAsset',

        'app\modules\system\assets\FontAwesomeAsset',
        'app\modules\system\assets\PreloaderAsset',
        'app\modules\system\assets\PopperAsset',
        'app\modules\system\assets\MasterAsset',

//        'app\modules\system\assets\AirDatePickerAsset',
    ];
}