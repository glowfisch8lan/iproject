<?php

namespace app\modules\staff\models;

use Yii;

/**
 * This is the model class for table "staff_state".
 *
 * @property int $id
 * @property int|null $workers_id ID сотрудника
 * @property int|null $vacancies_id ID вакансии
 *
 * @property StaffVacancies $vacancies
 * @property StaffWorkers $workers
 */
class State extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'staff_state';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['workers_id', 'vacancies_id'], 'integer'],
            [['vacancies_id'], 'exist', 'skipOnError' => true, 'targetClass' => Vacancies::className(), 'targetAttribute' => ['vacancies_id' => 'id']],
            [['workers_id'], 'exist', 'skipOnError' => true, 'targetClass' => Workers::className(), 'targetAttribute' => ['workers_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'workers_id' => 'ID сотрудника',
            'vacancies_id' => 'ID вакансии',
            'workers'      => 'ФИО'
        ];
    }

    /**
     * Gets query for [[Vacancies]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVacancies()
    {
        return $this->hasOne(Vacancies::className(), ['id' => 'vacancies_id']);
    }

    /**
     * Gets query for [[Workers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getWorkers()
    {
        return $this->hasOne(Workers::className(), ['id' => 'workers_id']);
    }
}
