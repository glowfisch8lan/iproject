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
class FontAwesomeAsset extends AssetBundle
{
    public $sourcePath = '@bower/font-awesome/';
    public $css = [
        'css/fontawesome.css',
        'css/solid.css',
        'css/brands.css',
    ];
    public $js = [
        'js/solid.js',
        'js/brands.js'
    ];
    public $depends = [
    ];
}
