<?php

namespace app\modules\inventory\controllers;

use Yii;
use app\modules\inventory\models\Lists;
use app\modules\inventory\models\ListItems;

/**
 * Default controller for the `staff` module
 */
class ListItemsController extends \app\modules\system\models\interfaces\lists\controllers\ListItemsController
{

    protected function getLists(){
        return new Lists();
    }

    protected function getListItems(){
        return new ListItems();
    }

}
