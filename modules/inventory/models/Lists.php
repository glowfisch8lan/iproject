<?php

namespace app\modules\inventory\models;

use Yii;
use yii\db\ActiveRecord;
/**
 * This is the model class for table "staff_lists".
 *
 * @property int $id
 * @property string $name
 * @property string $list
 * @property string $module
 */
class Lists extends \app\modules\system\models\interfaces\lists\models\Lists
{


    public static function tableName()
    {
        return 'inventory_lists';
    }

    public function init()
    {
        $this->table_name = 'inventory_lists';
        parent::init();
    }
}
