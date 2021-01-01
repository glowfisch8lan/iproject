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
		'vendor/bootstrap4/css/bootstrap.min.css',
		'css/master.css',
		'vendor/chartsjs/Chart.min.css',
		'vendor/flagiconcss3/css/flag-icon.min.css'

    ];
	public $js = [
    'vendor/bootstrap4/js/bootstrap.bundle.min.js',
    'vendor/fontawesome5/js/solid.min.js',
    'vendor/fontawesome5/js/fontawesome.min.js',
   // 'vendor/chartsjs/Chart.min.js',
  //  'js/dashboard-charts.js',
        'js/script.js'
		
    ];
    public $depends = [
        'yii\web\YiiAsset'
    ];
}