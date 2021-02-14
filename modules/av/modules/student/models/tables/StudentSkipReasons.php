<?php
namespace app\modules\av\modules\student\models\tables;

use Yii;
use yii\db\ActiveRecord;

class StudentsSkipReasons extends ActiveRecord
{

    public static function getDb(){
        return Yii::$app->get('db2');
    }

    public static function tableName(){
        return 'student_skip_reasons';
    }



}