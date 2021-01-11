<?php


namespace app\modules\system\components\gridviewer;

use Yii;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\helpers\Html;

class CustomActionColumns extends ActionColumn
{
    /*
     * Переопределили функцию GridView для того, чтобы выводить кнопку "Create" чуть ниже в таблице;
     */
    protected function renderFilterCellContent()
    {
        $url = '/'. Yii::$app->controller->module->id . '/' . Yii::$app->controller->id .  '/create';
        $urlDeleteAll = '/'. Yii::$app->controller->module->id . '/' . Yii::$app->controller->id .  '/deleteAll';
        return Html::a('<i class="fa fa-plus" aria-hidden="true"></i></i>', $url,
            ['class' => 'btn btn-outline-info']);
    }
}


