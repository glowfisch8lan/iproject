<?php

namespace app\modules\metrica\controllers;

use Yii;

use app\modules\staff\models\Lists;
use app\modules\staff\models\ListItems;

/**
 * Default controller for the `metrica` module
 */
class ListItemsController extends \app\modules\system\models\interfaces\lists\controllers\ListItemsController
{

  public function newModel($class){
          $class = Yii::$app->controller->module->modelNamespace.'\\'.$class;
          return new $class();
  }
  public function init()
  {
      parent::init();
  }
}