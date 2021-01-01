<?php

namespace app\modules\metrica\models;

use Yii;

/**
 * This is the model class for table "metrica_request".
 *
 * @property int $id
 * @property string $date
 * @property string $url
 * @property int $user
 * @property string|null $value
 */
class Request extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'metrica_request';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date', 'url', 'user'], 'required'],
            [['date'], 'safe'],
            [['url', 'value'], 'string'],
            [['user'], 'integer']
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function saveMultiple($data)
    {


            $fields = ['date', 'url', 'user'];

            $db = Yii::$app->db;
            $sql = $db->queryBuilder->batchInsert('metrica_request', $fields, $data);
            $db->createCommand($sql)->execute();

    }
}
