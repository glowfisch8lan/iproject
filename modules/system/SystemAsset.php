<?php
namespace app\modules\system;
use yii\web\AssetBundle;

class SystemAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/system/assets';
    public $baseUrl = '@app/modules/system/assets';
    public $publishOptions = [
        ///'forceCopy' => true //отключает кеширование
    ];
    public $css = [
        'vendor/fontawesome/4.7.0/css/font-awesome.min.css',
        'vendor/bootstrap4/css/bootstrap.min.css',
    ];
	public $js = [
        'vendor/bootstrap4/js/bootstrap.bundle.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset'
    ];
}