<?php

namespace app\modules\system\models\interfaces\lists\models;

use Yii;
class Lists extends \yii\db\ActiveRecord
{

    public $module;

    public static function tableName(){}

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'list'], 'required'],
            [['name', 'list'], 'string', 'max' => 45]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название'
        ];
    }
    
    public static function getTableName($id){
        return self::findOne($id)->list;
    }

    public function init()
    {

        $this->module = Yii::$app->controller->module;
        parent::init();
    }
}
