<?php


namespace app\modules\system\components\gridviewer;

use Yii;
use yii\grid\ActionColumn;
use yii\helpers\Html;

class CustomActionColumns extends ActionColumn
{
    protected function renderFilterCellContent()
    {
        $url = '/'. Yii::$app->controller->module->id . '/' . Yii::$app->controller->id .  '/create';
        return Html::a('<i class="fa fa-plus" aria-hidden="true"></i></i>', $url,
            ['class' => 'btn btn-outline-info']);
    }
}