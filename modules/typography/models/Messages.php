<?php

namespace app\modules\feedback\models;

use Yii;
use app\modules\staff\models\Units;
/**
 * This is the model class for table "feedback_messages".
 *
 * @property int $id
 * @property string $sender Отправитель
 * @property int|null $unit_d Получатель
 * @property int $subject Тема
 * @property string $text Содержание
 * @property string|null $callback Обратная связь
 *
 * @property Units $unit_id Подразделение
 */
class Messages extends \yii\db\ActiveRecord
{
    public $verifyCode;
    public $unitSender;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'feedback_messages';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sender', 'subject', 'text', 'subject'], 'required'],
            [['sender', 'text', 'callback','subject', 'unitSender'], 'string'],
            [['unit_id', 'status'], 'integer'],
            [['unit_id'], 'exist', 'skipOnError' => true, 'targetClass' => Units::className(), 'targetAttribute' => ['unit_id' => 'id']],
            ['verifyCode', 'captcha', 'captchaAction' => '/feedback/default/captcha', 'on' => 'guest_feedback'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sender' => 'Отправитель',
            'unit_id' => 'Подразделение-получатель',
            'subject' => 'Тема',
            'unitSender' => 'Подразделение отправителя',
            'text' => 'Содержание',
            'callback' => 'Телефон обратной связи',
            'status' => 'Статус',
            'verifyCode' => 'Введите код:'
        ];
    }

    /**
     * Gets query for [[Destination0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUnit()
    {
        return $this->hasOne(Units::className(), ['id' => 'unit_id']);
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     * @param string $email the target email address
     * @return bool whether the model passes validation
     */
    public function contact()
    {

    }
}
