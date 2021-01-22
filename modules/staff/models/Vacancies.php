<?php

namespace app\modules\staff\models;

use Yii;

/**
 * This is the model class for table "staff_vacancies".
 *
 * @property int $id
 * @property int $unit_id Отдел
 * @property int $position_id Должность
 *
 * @property StaffPositions $position
 * @property StaffUnits $unit
 */
class Vacancies extends \yii\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'staff_vacancies';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['unit_id', 'position_id'], 'required'],
            [['unit_id', 'position_id'], 'integer'],
            [['position_id'], 'exist', 'skipOnError' => true, 'targetClass' => Positions::className(), 'targetAttribute' => ['position_id' => 'id']],
            [['unit_id'], 'exist', 'skipOnError' => true, 'targetClass' => Units::className(), 'targetAttribute' => ['unit_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'   => '№',
            'unit' => 'Отдел',
            'unit_id' => 'Отдел',
            'position' => 'Должность',
            'position_id' => 'Должность',
        ];
    }

    /**
     * Gets query for [[Position]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPosition()
    {
        return $this->hasOne(Positions::className(), ['id' => 'position_id']);
    }

    /**
     * Gets query for [[Unit]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUnit()
    {
        return $this->hasOne(Units::className(), ['id' => 'unit_id']);
    }
}
