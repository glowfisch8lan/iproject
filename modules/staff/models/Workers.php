<?php

namespace app\modules\staff\models;

use Yii;

/**
 * This is the model class for table "staff_workers".
 *
 * @property int $id
 * @property string $firstname Имя
 * @property string $middlename Отчество
 * @property string $lastname Фамилия
 * @property string|null $birthday День рождения
 * @property string|null $personal_fields Персональные поля
 * @property string|null $additinal_lists Дополнительные справочники пользователя
 */
class Workers extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'staff_workers';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['firstname', 'middlename', 'lastname'], 'required'],
            [['birthday'], 'safe'],
            [['firstname', 'middlename', 'lastname', 'personal_fields', 'additinal_lists'], 'string', 'max' => 45],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'worker' => 'ФИО',
            'firstname' => 'Имя',
            'middlename' => 'Отчество',
            'lastname' => 'Фамилия',
            'birthday' => 'День рождения',
            'personal_fields' => 'Персональные поля',
            'additinal_lists' => 'Дополнительные справочники пользователя',
        ];
    }
}
