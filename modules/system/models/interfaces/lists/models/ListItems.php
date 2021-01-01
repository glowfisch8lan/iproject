<?php

namespace app\modules\system\models\interfaces\lists\models;

use app\modules\system\models\users\Users;
use Yii;
use yii\db\ActiveRecord;
use yii\web\NotFoundHttpException;

class ListItems extends ActiveRecord
{

    protected static $tableName;

    public $module;
    public $parent;
    //public $parent_id;


    public static function tableName(){
        return self::$tableName;
    }

    public static function useTable($tableName){
        static::$tableName = $tableName;
        return new static();
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'name_short', 'parent_id'], 'required'],
            [['name', 'name_short'], 'string'],
            [['parent'], 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Наименование',
            'name_short' => 'Сокр. наименование',
            'parent_id' => 'Родительская категория'
        ];
    }

    public function init()
    {
        $this->module = Yii::$app->controller->module;
        parent::init();
    }
}
