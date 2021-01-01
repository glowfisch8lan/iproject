<?php

namespace app\modules\staff\models;

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
        return 'staff_lists';
    }

    public function init()
    {
        parent::init();
    }
}
