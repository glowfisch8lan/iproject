<?php

namespace app\modules\system\models\events;

use Yii;

/**
 * This is the model class for table "system_events".
 *
 * @property int $id ID
 * @property string $timestamp Время события
 * @property int $user_id Пользователь
 * @property string $module Модуль
 * @property int $event_type Событие
 * @property string|null $text Описание
 * @property int|null $param1 Параметр 1
 * @property int|null $param2 Параметр 2
 * @property string|null $param3 Параметр 3
 */
class Events extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'system_events';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['timestamp'], 'safe'],
            [['user_id', 'event_type'], 'required'],
            [['user_id', 'event_type', 'param1', 'param2'], 'integer'],
            [['text'], 'string'],
            [['module'], 'string', 'max' => 20],
            [['param3'], 'string', 'max' => 40],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'timestamp' => 'Timestamp',
            'user_id' => 'User ID',
            'module' => 'Module',
            'event_type' => 'Event Type',
            'text' => 'Text',
            'param1' => 'Param1',
            'param2' => 'Param2',
            'param3' => 'Param3',
        ];
    }
}
