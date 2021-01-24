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
 *
 * @property MetricaPatterns $pattern0
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
            [['id', 'url'], 'required'],
            [['id', 'pattern', 'value', 'status'], 'integer'],
            [['url'], 'string', 'max' => 255],
            [['pattern_id'], 'exist', 'skipOnError' => true, 'targetClass' => Patterns::className(), 'targetAttribute' => ['pattern' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'url' => 'URL',
            'pattern_id' => 'Паттерн',
            'value' => 'Значение',
            'status' => 'Состояние'
        ];
    }

    /**
     * Gets query for [[Pattern0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPattern0()
    {
        return $this->hasOne(Patterns::className(), ['id' => 'pattern_id']);
    }
}
