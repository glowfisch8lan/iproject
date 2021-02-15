<?php

namespace app\modules\av\modules\student\models\plugins;

use app\modules\av\modules\student\models\StudentsApi;
use yii\base\Model;
use DateTime;
/**
 * Journal model for the `student` module
 */
class Journal extends Model
{

    public $group;
    public $discipline = null;
    public $academicPerformance = null;

    public function attributeLabels()
    {
        return [
            'group' => 'Группа'
        ];
    }

    public function rules(){
        return [
            [
                ['group'],
                'required',
                'message' => 'Заполните поля!',

            ],
            [
                ['discipline'],
                'required',
                'message' => 'Заполните поля!',

            ]
        ];
    }

    /**
     * Фильтрация оценок студента, выборка нужных оценок за определнный период;
     * Условия выборки: Дата выставления оценки, оценки, полученные только на парах (исключены экзамены и результаты сессий), оценки вида 5,4,3,2
     *
     * @param array $marks Массив оценок,
     * [0 =>
    array (size=10)
    'id' => string '159740' (length=6)
    'journal_lesson_id' => string '16887' (length=5)
    'student_id' => string '2510' (length=4)
    'mark_type_id' => string '1' (length=1)
    'mark_value_id' => string '2' (length=1)
    'datetime' => string '2020-09-14 04:34:19' (length=19)
    'parent_id' => null
    'control_type_id' => null
    'curriculum_discipline_id' => string '14547' (length=5)
    'class_type_id' => string '2' (length=1),
     * 1 => ... ]
     * @param array $datetime [0 => (string) Начальная дата, 1 => (string) Конечная дата] Период для фильтрации
     * @return array
     */
    public function filterMarks($marks, $datetime)
    {

        foreach ($marks as $key => $value)
        {

            // = strtotime( preg_replace('/\s.*/m', '', $value['lesson_date']) );
            $date = new DateTime($value['lesson_date']);

            $date =  $date->format('d.m.Y');

            if (
                strtotime($date) >= strtotime($datetime[0]) &&
                strtotime($date) <= strtotime($datetime[1]) &&
                $value['curriculum_discipline_id'] != null
            )
            {
                $arr[] = $value;
            }
        }

        return $arr;
    }

    public function filterDiscipline( $marks, $id)
    {
        foreach ($marks as $key => $value)
        {
            if (
                $value['curriculum_discipline_id'] == $id
            )
            {
                $arr[] = $value;
            }
        }

        return $arr;
    }

    /**
     * Оценки студента
     *
     * @param integer $id Идентификатор студента
     * @return array | null
     */
    public static function getMarkValue($id)
    {
        $markValues = StudentsApi::getMarksValues();
        foreach ($markValues as $key => $value) if ($value['id'] == $id) $result = ['name' => $value['name'], 'name_short' => $value['name_short']];
        if (empty($result)) return;
        return $result;
    }
}
