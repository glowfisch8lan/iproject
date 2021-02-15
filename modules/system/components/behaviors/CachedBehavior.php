<?php
namespace app\modules\system\components\behaviors;

use Yii;
use yii\base\Behavior;
use yii\web\Controller;

class CachedBehavior extends Behavior
{
    public $actions;
    public $cache;

    public function events()
    {
        return [
            Controller::EVENT_BEFORE_ACTION  => 'deleteCache',
        ];
    }

    public function deleteCache()
    {
        $action = Yii::$app->controller->action->id; //название текущего действия
        if(array_search($action, $this->actions)=== false) return;

        $this->cache->flush();
    }
}