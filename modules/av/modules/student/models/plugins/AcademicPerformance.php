<?php

namespace app\modules\av\modules\student\models\plugins;


use yii\base\Model;
use app\modules\system\helpers\ArrayHelper;
use app\modules\av\modules\student\models\StudentsApi;
use app\modules\av\models\students\reports\grade_sheet\Report;

/**
 * academicPerformance model for the `student` module
 */
class AcademicPerformance extends Model
{
    public $group;
//    public $startDate;
//    public $endDate;

    public $students;
    public $curriculumDisciplines;
    public $startDate = '01.09.2020';
    public $endDate = '30.01.2021';
    public $marks;
    public $markValues;

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
                'message' => 'Заполните поля!'
            ],
        ];

    }

    /**
     * Наполнение модели данными
     *
     * @return null
     */
    public function fetchDataGradeSheet()
    {
        $this->group = StudentsApi::getGroup($this->group);
        $this->students = StudentsApi::getStudentsByGroup($this->group['id']);
        $this->curriculumDisciplines = StudentsApi::getCurriculumDisciplines($this->group['education_plan_id']);
        $this->marks = StudentsApi::getMarksByGroup($this->group['id']);
        $this->markValues = StudentsApi::getMarksValues();

        return 0;
    }

    /**
     * Все оценки студента
     *
     * @return null
     */
    public function getMarks($id)
    {
        foreach ($this->marks as $key => $mark)
        {
            if ($mark['student_id'] == $id)
            {
                $arr[] = $mark;
            }
        }
        return $arr;

    }

    public function filterMarks($marks, $datetime)
    {
        foreach ($marks as $key => $value)
        {

            $date = strtotime($value['datetime']);

            if (
                $date >= strtotime($datetime[0]) &&
                $date <= strtotime($datetime[1]) &&
                $value['mark_value_id'] >= 1 &&
                $value['mark_value_id'] <= 5 &&
                $value['class_type_id'] != null
            )
            {
                $arr[] = $value;
            }

        }
        return $arr;

    }

    public function getDisciplineName($id)
    {


        $index = ArrayHelper::recursiveArraySearch($id, $this->curriculumDisciplines);


        if (empty($this->curriculumDisciplines[$index[0]]['name']))
        {
            return 0;
        }

        $discipline['name'] = $this->curriculumDisciplines[$index[0]]['name'];
        $discipline['name_short'] = $this->curriculumDisciplines[$index[0]]['name_short'];

        return $discipline;

    }

    public function collectDisciplines()
    {

        $index = 0;

        foreach ($this->students as $student)
        {
            //фильтруем оценки, составляем таблицу оценок. Делаем выборку тех дисциплин, у которых есть оценки за выбранный период времени
            $marksArray = $this->filterMarks($this->getMarks($student['id']) , [$this->startDate , $this->endDate]);
            foreach ($marksArray as $marks)
            {
                $collection[$marks['curriculum_discipline_id']] = $index;
                $index++;
            }


        }

        ksort($collection);
        return array_keys($collection);

    }

    public function getDisciplines()
    {

        $list_disciplines = $this->collectDisciplines(); //собираем список дисциплин

        $index = 0;
        $arr = [];
        foreach ($list_disciplines as $id)
        {

            $discipline = $this->getDisciplineName($id);

            if($discipline)
            {
                $index++;
                $arr[] = $discipline['name_short'];
            }

        }
        return $arr;

    }

    public function getStudentsName()
    {

        $arr = [];
        foreach ($this->students as $student)
        {
            $arr[] = $this->getShortName((object)$student);
        }
        return $arr;

    }
    /**
     * Сокращенные ФИО обучающегося.
     *
     * @return string
     */
    public function getShortName($student)
    {
        return $student->family_name . ' ' . mb_substr($student->name, 0, 1) . '.' . ($student->surname != '' ? mb_substr($student->surname, 0, 1) . '.' : '');
    }

    public function getStudentMarks($id)
    {
        return $this->filterMarks($this->getMarks($id) , [$this->startDate , $this->endDate]);
    }

    public function getMarkValue($id)
    {

        foreach ($this->markValues as $key => $value)
        {
            if ($value['id'] == $id)
            {
                $result = ['name' => $value['name'], 'name_short' => $value['name_short']];
            }

        }

        if (empty($result))
        {
            return 0;
        }

        return $result;
    }

    public function filterReMarks($studentMarks)
    {
        foreach ($studentMarks as $key => $value)
        {
            if ($value['mark_value_id'] < 5)
            {
                $marksArrByDiscipline[$value['curriculum_discipline_id']]['marks'][$value['id']] = $this->getMarkValue($value['mark_value_id']) ['name_short'];
                if (!empty($value['parent_id']))
                {
                    $marksArrByDiscipline[$value['curriculum_discipline_id']]['parent_id'][$value['id']] = $value['parent_id'];
                }

            }
        }

        foreach ($marksArrByDiscipline as $key => $value)
        {

            $id = $key;
            $marks = $value['marks'];
            //$parent_id = ;
            if(!empty($value['parent_id'])){
                $parent_id = $value['parent_id'];
                /* Обрабатываем отработки */
                foreach ($parent_id as $last => $parent)
                {
                    if (array_key_exists($parent, $marks))
                    {
                        $marksArrByDiscipline[$key]['marks'][$last] = '(' . $marksArrByDiscipline[$key]['marks'][$parent] . '/' . $marksArrByDiscipline[$key]['marks'][$last] . ')';
                        unset($marksArrByDiscipline[$key]['marks'][$parent]);

                    }
                }
            }

        }

        return $marksArrByDiscipline;
    }
//    public function init()
//    {
//        return 0;
//    }
}
