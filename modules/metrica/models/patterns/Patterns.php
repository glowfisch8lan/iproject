<?php

namespace app\modules\metrica\models\patterns;

use Yii;

/**
 * This is the model class for table "metrica_patterns".
 *
 * @property int $id
 * @property string $name
 * @property string $pattern
 * @property int|null $user_id Автор
 * @property string|null $value
 *
 * @property SystemUsers $user
 */
class Patterns extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'metrica_patterns';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'pattern'], 'required'],
            [['name', 'pattern', 'value'], 'string'],
            [['user_id'], 'integer'],
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
            'name' => 'Name',
            'pattern' => 'Pattern',
            'user_id' => 'User ID',
            'value' => 'Value',
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
