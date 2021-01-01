<?php

namespace app\modules\metrica\models;

use Yii;

/**
 * This is the model class for table "metrica_pattern".
 *
 * @property int $id
 * @property string $name
 * @property string $pattern
 * @property int $user
 * @property string|null $value
 *
 * @property SystemUsers $user0
 */
class Pattern extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'metrica_pattern';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'pattern', 'user'], 'required'],
            [['name', 'pattern', 'value'], 'string'],
            [['user'], 'integer']
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
            'user' => 'User',
            'value' => 'Value',
        ];
    }

    public function getAllPatterns(){
        return static::find()->asArray()->all();

    }
}
