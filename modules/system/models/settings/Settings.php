<?php

namespace app\modules\system\models\settings;

use Yii;

/**
 * Это модель класса 'Settings' для "system_settings".
 *
 * @property int $id
 * @property string $name Настройка
 * @property string $value Значение
 * @property int|null $user_id Владелец
 *
 * @property SystemUsers $user
 */
class Settings extends \yii\db\ActiveRecord
{

    //TODO Сделать кеширование Настроек;

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
            [['user_id'], 'integer'],
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
            'user_id' => 'Владелец'
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

    /**
     * Установка параметра: модуль.категория.параметр
     *
     * @param $code
     * @param $value
     */
    public static function setValue($name, $value, $user_id = null)
    {
        return Yii::$app->db->createCommand('INSERT INTO `system_settings` (`id`, `name`, `value`, `user_id`) VALUES (NULL, :name, :value, :user_id);')
            ->bindValues([':name' => $name, ':value' => $value, ':user_id' => $user_id])
            ->execute();
    }

    /**
     * Получение настройки
     *
     * @param $code
     * @param $value
     */
    public static function getValue($name)
    {
        return self::find()->where(['name' => $name])->one()->value;
    }
}