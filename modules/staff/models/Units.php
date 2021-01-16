<?php

namespace app\modules\staff\models;

use Yii;

/**
 * This is the model class for table "staff_units".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $name_short
 * @property int|null $parent_id
 *
 * @property StaffVacancies[] $staffVacancies
 * @property StaffWorkers[] $staffWorkers
 */
class Units extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'staff_units';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['parent_id'], 'integer'],
            [['name', 'name_short'], 'string', 'max' => 45],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'name_short' => 'Name Short',
            'parent_id' => 'Parent ID',
        ];
    }

//    public static function getUnitByID($id){
//        return static::findOne($id);
//    }
//
//    public static function getUnitByName($name){
//        return static::findOne()->where('name = :name', [':name' => $name]);
//    }
    /**
     * Gets query for [[StaffVacancies]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVacancies()
    {
        return $this->hasMany(Vacancies::className(), ['unit' => 'id']);
    }

    /**
     * Gets query for [[StaffWorkers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getWorkers()
    {
        return $this->hasMany(Workers::className(), ['unit' => 'id']);
    }

    public static function getListUnits($row){

      if(count($row) === 1){
        $data = self::find()->asArray()->all();
        foreach($data as $key => $value){
              $result[$value[key($row)]] = $value[$row[key($row)]];
        }
        return $result;
      }
    }
}
