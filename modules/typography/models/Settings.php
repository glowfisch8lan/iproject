<?php

namespace app\modules\typography\models;

use Yii;

/**
 * This is the model class for table "system_settings".
 *
 * @property int $id
 * @property string $name Настройка
 * @property string $value Значение
 * @property int|null $user_id Владелец
 * @property int|null $type Тип: 1 - системная
 *
 * @property SystemUsers $user
 */
class Settings extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'system_settings';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'value'], 'required'],
            [['user_id', 'type'], 'integer'],
            [['name', 'value'], 'string', 'max' => 65],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => SystemUsers::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Настройка',
            'value' => 'Значение',
            'user_id' => 'Владелец',
            'type' => 'Тип: 1 - системная',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(SystemUsers::className(), ['id' => 'user_id']);
    }
}
