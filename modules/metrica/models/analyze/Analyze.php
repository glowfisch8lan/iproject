<?php

namespace app\modules\metrica\models\analyze;

use Yii;
use app\modules\metrica\models\patterns\Patterns;

/**
 * This is the model class for table "metrica_analyze".
 *
 * @property int $id
 * @property string $url URL-адрес
 * @property int|null $pattern_id Паттерн
 * @property int|null $value Результат
 */
class Analyze extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'metrica_analyze';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['url'], 'required'],
            [['id', 'pattern_id', 'value', 'status'], 'safe'],
            [['url'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'url' => 'ссылка',
            'pattern_id' => 'Паттерн',
            'value' => 'Идентификатор',
            'status' => 'Состояние',
            'pattern' => 'Паттерн'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPattern()
    {
        return $this->hasOne(Patterns::className(), ['id' => 'pattern_id']);
    }
}
