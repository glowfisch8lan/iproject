<?php

namespace app\modules\system\models\settings;

use Yii;
use app\modules\system\models\users\Users;
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

    public $settings;

    private static $validotorRules = [

    ];

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
            [['name', 'value'], 'validateSettings'],
//            [['user_id'], 'integer'],
//            [['name', 'value'], 'string', 'max' => 65],
//            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'id']],
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
        return $this->hasOne(Users::className(), ['id' => 'user_id']);
    }

    /**
     * Установка параметра: модуль.категория.параметр
     *
     * @param $code
     * @param $value
     */
    public static function setValue($name, $value, $user_id = null)
    {
        return Yii::$app->db->createCommand('INSERT INTO `system_settings` (`id`, `name`, `value`, `user_id`) VALUES (NULL, :name, :value, :user_id) ON DUPLICATE KEY UPDATE name=:name, value=:value, user_id = :user_id;')
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

    public function formSettings($array)
    {

        /**
         * Settings
         */
        $arr = [];
        if(array_keys($array))
            $this->arr($array);

        return;
    }

    /**
     * Формирует параметр name -> value из ключей вложенного массива;
     *
     * @param $array
     * @param null $key_last
     */
    private function arr($array, $key_last = null)
    {

            foreach($array as $key => $value){
                $_old_key = (is_null($key_last)) ? null : $key_last . '.' ;

                if(!array_keys($value))
                {
                    $_old_key = (is_null($key_last)) ? null : $key_last . '.' ;
                    $this->settings[$_old_key.$key] = $value;
                }

                $this->arr($value, $_old_key.$key);
            }

    }

    public function validateSettings($attribute, $params){
        $this->addError('value', 'Имя слишsком слабый');
        $this->addError('name', 'Имя слишsком слабый');
    }

}