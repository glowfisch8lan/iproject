<?php

namespace app\modules\typography\models;

use Yii;
use app\modules\staff\models\Units;
/**
 * This is the model class for table "typography_orders".
 *
 * @property int $id
 * @property resource $sender Отправитель
 * @property int|null $sender_unit_id Подразделение отправителя
 * @property int|null $receiver Получатель
 * @property int|null $receiver_unit_id Подразделение получателя
 * @property int $comment Комментарий
 * @property string $file_uuid UUID каталога
 *
 * @property Units $receiverUnit
 * @property Units $senderUnit
 */
class Orders extends \yii\db\ActiveRecord
{
    public $verifyCode;
    public $file;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'typography_orders';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sender', 'comment', 'file_uuid'], 'required'],
            [['sender', 'file_uuid'], 'string'],
            [['sender_unit_id', 'receiver', 'receiver_unit_id', 'comment', 'status'], 'integer'],
            [['receiver_unit_id'], 'exist', 'skipOnError' => true, 'targetClass' => Units::className(), 'targetAttribute' => ['receiver_unit_id' => 'id']],
            [['sender_unit_id'], 'exist', 'skipOnError' => true, 'targetClass' => Units::className(), 'targetAttribute' => ['sender_unit_id' => 'id']],
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
            'sender_unit_id' => 'Подразделение отправителя',
            'receiver' => 'Получатель',
            'receiver_unit_id' => 'Подразделение получателя',
            'comment' => 'Комментарий',
            'file_uuid' => 'UUID каталога',
            'status' => 'Статус',
        ];
    }

    /**
     * Gets query for [[ReceiverUnit]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReceiverUnit()
    {
        return $this->hasOne(Units::className(), ['id' => 'receiver_unit_id']);
    }

    /**
     * Gets query for [[SenderUnit]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSenderUnit()
    {
        return $this->hasOne(Units::className(), ['id' => 'sender_unit_id']);
    }
}
