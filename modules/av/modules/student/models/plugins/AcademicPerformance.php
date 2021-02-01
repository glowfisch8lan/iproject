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
    public $startDate = '01.01.2020';
    public $endDate = '01.02.2021';
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
     */
    public function fetchDataGradeSheet()
    {
        $this->group = StudentsApi::getGroup($this->group);
        $this->students = StudentsApi::getStudentsByGroup($this->group['id']);
        $this->curriculumDisciplines = StudentsApi::getCurriculumDisciplines($this->group['education_plan_id']);
        $this->marks = StudentsApi::getMarksByGroup($this->group['id']);
        $this->markValues = StudentsApi::getMarksValues();

        return;
    }

    /**
     * Все оценки студента
     *
     * @param integer $id Идентификатор студента
     * @return array
     */
    public function getMarks(int $id)
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

    /**
     * Имя и сокращенное название дисциплины по ее ID
     * Поиск осуществляется рекурсивно в уже загруженном перечне дисциплин, изучаемых по учебному плану.
     * Это сделано для экономия ресурсов и снижения количества запросов к СУБД.
     *
     * @param integer $id Идентификатор дисциплины
     * @return sarray | 0
     */
    public function getDisciplineName($id)
    {
        $index = ArrayHelper::recursiveArraySearch($id, $this->curriculumDisciplines);

        if (empty($this->curriculumDisciplines[$index[0]]['name'])) return 0;

        $discipline['name'] = $this->curriculumDisciplines[$index[0]]['name'];
        $discipline['name_short'] = $this->curriculumDisciplines[$index[0]]['name_short'];

        return $discipline;
    }

    /**
     * Список дисцплин (сокращенные названия), изучаемых по учебному плану за установленный период;
     *
     * @return array Идентификаторы отфильтрованных дисциплин
     */
    public function collectDisciplines()
    {
        $index = 0;
        foreach ($this->students as $student)
        {
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

    /**
     * Список дисцплин (сокращенные названия), изучаемых по учебному плану за установленный период;
     *
     * @return array
     */
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
                $index++; $arr[] = $discipline['name_short'];
            }

        }
        return $arr;

    }

    /**
     * Список Фамилия И.О. студентов группы
     *
     * @return array
     */
    public function getStudentsName()
    {
        $arr = [];
        foreach ($this->students as $student) $arr[] = $this->getShortName((object)$student);
        return $arr;

    }

    /**
     * Сокращенные ФИО обучающегося.
     *
     * @param object $student Студент как объект со свойствами
     * @return string
     */
    public function getShortName($student)
    {
        return $student->family_name . ' ' . mb_substr($student->name, 0, 1) . '.' . ($student->surname != '' ? mb_substr($student->surname, 0, 1) . '.' : '');
    }

    /**
     * Оценки студента
     *
     * @param integer $id Идентификатор студента
     * @return array
     */
    public function getStudentMarks($id)
    {
        return $this->filterMarks($this->getMarks($id) , [$this->startDate , $this->endDate]);
    }

    /**
     * Оценки студента
     *
     * @param integer $id Идентификатор студента
     * @return array | null
     */
    public function getMarkValue($id)
    {
        foreach ($this->markValues as $key => $value) if ($value['id'] == $id) $result = ['name' => $value['name'], 'name_short' => $value['name_short']];
        if (empty($result)) return;
        return $result;
    }

    /**
     * Средний балл студента
     *
     * @param integer $id Идентификатор студента
     * @return array | null
     */
    public function getAverageMarksStudent($marksArr)
    {

        $sumMark = 0;
        $p = 0;

        foreach ($marksArr as $id => $value)
        {

            foreach ($value['marks'] as $idMark => $mark)
            {

                if (preg_match_all("/\(([0-9]+)\/([0-9]+)\)/", $mark, $var))
                {
                    $mark = (int)$var[1][0] + (int)$var[2][0];
                    $p++;
                }

                $sumMark += (int)$mark;
                $p++;
            }

        }

        if ($p != 0)
        {
            return round($sumMark / $p, 2);
        }
        else return 0;
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
}
