<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\modules\system\assets;

use yii\web\AssetBundle;

/**
 * Asset bundle for the Twitter bootstrap javascript files.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 */

class AirDatePickerAsset extends AssetBundle
{
    public $sourcePath = '@bower/air-datepicker/dist';
    public $css = [
        'css/datepicker.min.css'
    ];
    public $js = [
      'js/datepicker.min.js'
    ];
    public $depends = [
    ];
}
