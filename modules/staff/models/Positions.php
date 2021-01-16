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
class Positions extends ActiveRecord
{


    public static function tableName()
    {
        return 'staff_positions';
    }

    public static function getListPositions($row){
      if(count($row) === 1){
        $data = self::find()->asArray()->all();
        foreach($data as $key => $value){
              $result[$value[key($row)]] = $value[$row[key($row)]];
        }
        return $result;
      }

    }

    // public function init()
    // {
    //     $this->table_name = 'staff_lists';
    //     parent::init();
    // }
}
