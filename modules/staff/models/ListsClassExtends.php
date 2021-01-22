<?php

namespace app\modules\staff\models;

use app\modules\staff\models\Lists;
use app\modules\staff\models\ListItems;

/**
 * This is the model class for table "staff_lists".
 *
 * @property int $id
 * @property string $name
 * @property string $list
 * @property string $module
 */
class ListsClassExtends
{

  public function newLists(){
          $model = new Lists();
          return $model;
  }

}
