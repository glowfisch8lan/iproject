<?php

namespace app\modules\av\modules\student\models\plugins;

use Yii;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use yii\base\Model;
use app\modules\system\helpers\ArrayHelper;
use app\modules\av\modules\student\models\StudentsApi;
use app\modules\system\models\cache\Cache;

/**
 * academicPerformance model for the `student` module
 */

class AcademicPerformance extends Model
{
    public $group;
    public $students;
    public $curriculumDisciplines;
    public $startDate = '01.01.2020';
    public $endDate;
    public $marks;
    public $markValues;
    public $session = false;
    public $isSkip = false;
    public $skipReasons;

    public $report = null;
    
    public $mapDisciplines;

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
                ['startDate'],
                'required',
                'message' => 'Заполните поля!',

            ],
            [
                ['endDate'],
                'required',
                'message' => 'Заполните поля!',

            ],
            [
                ['session'],
                'required',
                'message' => 'Заполните поля!',

            ],
            [
                ['isSkip'],
                'required',
                'message' => 'Заполните поля!',

            ],
            [
                ['report'],
                'required',
                'message' => 'Заполните поля!',

            ],
        ];

    }

    /**
     * Данные о студентах в конкретной группе
     *
     * @param $id
     * @return mixed
     */
    public static function getStudentsByGroup($id){
        return StudentsApi::getStudentsByGroup($id);
    }

    /**
     * Данные о группе
     *
     * @param $id
     * @return mixed
     */
    public static function getGroup($id)
    {
        return StudentsApi::getGroup($id);
    }

    /**
     * Все дисциплины из учебного плана
     *
     * @param $education_plan_id
     * @return mixed
     */
    public static function getCurriculumDisciplines($education_plan_id)
    {
        return StudentsApi::getCurriculumDisciplines($education_plan_id);
    }

    /**
     * Все оценки в группе
     *
     * @param $group
     * @return mixed
     */
    public static function getMarksByGroup($group)
    {
        return StudentsApi::getMarksByGroup($group);
    }

    /**
     * Справочник оценка - значение
     * @return mixed
     */
    public static function getMarksValues()
    {
        return StudentsApi::getMarksValues();
    }

    /**
     * Справочник причин пропусков
     *
     * @return mixed
     */
    public static function getSkipReasons()
    {
        return StudentsApi::getSkipReasons();
    }

    /**
     * Наполнение модели данными
     *
     */
    //TODO: сделать кеширование
    public function fetchData()
    {
        $cache = Yii::$app->cache;
        $duration = 1200;


        /**
         *  Кеширование причин пропусков
         */
        $this->skipReasons = $cache->get('skipReasons');
        if ($this->skipReasons === false) {
            $this->skipReasons = self::getSkipReasons();
            $cache->set('skipReasons', $this->skipReasons, $duration);
        }


        $this->markValues = self::getMarksValues();
        $this->group = self::getGroup($this->group);
        $this->students = self::getStudentsByGroup($this->group['id']);
        $this->curriculumDisciplines = self::getCurriculumDisciplines($this->group['education_plan_id']);
        $this->marks = self::getMarksByGroup($this->group['id']);

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
     * Изменено условие выборки : оценки по занятиям;
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
     *   'lesson_date' => string '2020-01-20' (length=10)
     * 1 => ... ]
     * @param array $datetime [0 => (string) Начальная дата, 1 => (string) Конечная дата] Период для фильтрации
     * @return array
     */
    public function filterMarks($marks, $datetime)
    {

        $arr = null;
        $arr2 = null;
        $arr3 = null;


        /*
        * Фильтр: Дата
        */
        foreach ($marks as $key => $value)
        {

            $date = strtotime( preg_replace('/\s.*/m', '', $value['lesson_date']) );
                    if (
                        $date >= strtotime($datetime[0]) &&
                        $date <= strtotime($datetime[1])
                    )
                    {
                        $arr[] = $value;
                    }

        }

        /*
         * Фильтр: Результаты сессии
         */
        foreach ($arr as $key => $value)
        {

            switch($this->session){
                case false:
                    if (
                        $value['class_type_id'] != null
                    )
                    {
                        $arr2[] = $value;
                    }
                    break;
                case true:
                    if (
                        $value['control_type_id'] != null
                    )
                    {
                        $arr2[] = $value;
                    }
                    break;

            }

        }

        /*
         * Фильтр по отработкам
         */
        foreach($arr2 as $key => $value)
        {
            switch($this->isSkip){
                case false:
                    if(
                        $value['mark_value_id'] >= 1 &&
                        $value['mark_value_id'] <= 5
                    )
                    {
                        $arr3[] = $value;
                    }
                    break;
                case true:
                    if(
                        ($value['mark_value_id'] >= 1 &&
                        $value['mark_value_id'] <= 5)  ||
                        $value['mark_value_id'] == null
                    )
                    {
                        $arr3[] = $value;
                    }

                    break;

            }
        }
        return $arr3;
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
     * @param bool $session Вернуть только результаты сессий
     * @return array Идентификаторы отфильтрованных дисциплин
     */

    public function collectDisciplines()
    {
        $index = 0;

        foreach ($this->students as $student)
        {

            $id = $student['id'];
            $marksArray = $this->filterMarks($this->getMarks($id) , [$this->startDate , $this->endDate]);
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
    public function getStudentMarks($id, $startDate = false, $endDate = false)
    {
        if(!$startDate)
            $startDate = $this->startDate;
        if(!$endDate)
            $endDate = $this->endDate;

        return $this->filterMarks($this->getMarks($id) , [$startDate, $endDate]);
    }

    /**
     * Получить тип Оценки студента
     *
     * @param integer $id Идентификатор оценки
     * @return array | null
     */
    public function getMarkValue($id)
    {
        foreach ($this->markValues as $key => $value) if ($value['id'] == $id) $result = ['name' => $value['name'], 'name_short' => $value['name_short']];
        if (empty($result)) return;
        return $result;
    }

    /**
     * Получить информацию о пропуске
     *
     * @param integer $id Идентификатор пропуска
     * @return array | null
     */
    public function getSkipReasonValue($id)
    {
        foreach ($this->skipReasons as $key => $value) if ($value['id'] == $id) $result = ['name' => $value['name'], 'name_short' => $value['name_short']];
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

    /**
     *  Отсеивание оценок 2,3,4,5, пропуск и сортировка их по дисциплинам
     *
     * @param $studentMarks
     * @return mixed
     */
    public function filterReMarks($studentMarks)
    {

        foreach ($studentMarks as $key => $value)
        {

            /**
             * 2,3,4,5
             */
            if ($value['mark_value_id'] < 5)
            {

                $marksArrByDiscipline[$value['curriculum_discipline_id']]['marks'][$value['id']] = $this->getMarkValue($value['mark_value_id'])['name_short']; //Записываем оценку в массив

                if (!empty($value['parent_id']))
                {
                    $marksArrByDiscipline[$value['curriculum_discipline_id']]['parent_id'][$value['id']] = $value['parent_id']; //Записываем родителя оценки. если эт отработка
                }

            }
            /**
             * Если mark_value_id = null, значит это пропуск, проверить наличие skipReason
             */
            if($value['mark_value_id'] == null)
            {
                if($value['skip_reason_id'] != null)
                {
                    $marksArrByDiscipline[$value['curriculum_discipline_id']]['marks'][$value['id']] = $this->getSkipReasonValue($value['skip_reason_id'])['name_short']; //Записываем оценку в массив
                }
                if (!empty($value['parent_id']))
                {
                    $marksArrByDiscipline[$value['curriculum_discipline_id']]['parent_id'][$value['id']] = $value['parent_id']; //Записываем родителя оценки. если эт отработка
                }
            }

        }

        /**
         * Отрабатываем пропуска и формируем оценки типа (2/3)
         */
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

    /**
     *  Создание массива из букв от A до Z для карты Excel
     *
     * @param $end_column
     * @param string $first_letters
     * @return array
     */
    public function createColumnsArray($end_column, $first_letters = '')
    {
        $columns = array();
        $length = strlen($end_column);
        $letters = range('A', 'Z');

        // Iterate over 26 letters.
        foreach ($letters as $letter)
        {
            // Paste the $first_letters before the next.
            $column = $first_letters . $letter;

            // Add the column to the final array.
            $columns[] = $column;

            // If it was the end column that was added, return the columns.
            if ($column == $end_column) return $columns;
        }

        // Add the column children.
        foreach ($columns as $column)
        {
            // Don't itterate if the $end_column was already set in a previous itteration.
            // Stop iterating if you've reached the maximum character length.
            if (!in_array($end_column, $columns) && strlen($column) < $length)
            {
                $new_columns = $this->createColumnsArray($end_column, $column);
                // Merge the new columns which were created with the final columns array.
                $columns = array_merge($columns, $new_columns);
            }
        }

        return $columns;
    }

    public function getAverageMarksDiscipline($marksArr)
    {
        $sum = [];
        foreach ($marksArr as $id => $value) //Дисциплина
        {
            $index = 0;

            foreach ($value as $type => $arr)  //Оценки, в том числе и отработанные
            {
                if ($type == 'marks')
                {
                    $sum[$id]['sum'] = (empty($sum[$id]['average'])) ? null : $sum[$id]['average'];

                    /*
                     * @key - id оценки;
                     * @mark - величина оценки;
                     *
                     * @sum array
                     * ['Идентификатор Дисциплины] => [ 'sum' => 'Общая сумма оценок Дисциплины', 'average' => 'Средний балл Дисциплины', 'Количество оценок в дисциплине']
                     *
                     */
                    foreach ($arr as $key => $mark)
                    {


                        /*
                         * Обрабатываем ситуацию, в которой оценка исправлена - (2/3)
                         */
                        if (preg_match_all("/\(([0-9]+)\/([0-9]+)\)/", $mark, $var))
                        {

                            //Прибавляем две оценки в общую сумма оценок и счетчик увеличиваем на два;
                            $sum[$id]['sum'] += $var[1][0]; // раз оценка;
                            $sum[$id]['sum'] += $var[2][0]; // два оценка;

                            $index++;

                        }
                        else
                        {

                            $sum[$id]['sum'] += $mark;


                        }
                        $index++;
                    }
                }
            }
            $sum[$id]['count'] = $index;
            $sum[$id]['average'] = round($sum[$id]['sum'] / $sum[$id]['count'], 2);

        }
        return $sum;
    }

    /**
     * Считаем количество оценок в массиве;
     *
     * @param $marksArrByDiscipline
     * @return int
     */
    private function countMarks($marksArrByDiscipline)
    {
        $countMarkArr['count'] = [];

        //$key - id Дисциплины
        //$value - оценки дисциплины.
        /*
         * Считаем все оценки перебирая каждую дисциплину;
         */

        foreach ($marksArrByDiscipline as $key => $value)
        {

            /*
             * Если нет оценок в дисциплине - вернуть 0;
             */
            if(empty($value['marks']))
                return 0;

            foreach ($value['marks'] as $mark => $markValue) {


                if (preg_match_all("/\(([0-9]+)\/([0-9]+)\)/", $markValue, $var)) {

                    /*
                     * Проверка на существование переменной;
                     */
                    if(empty($countMarkArr['count'][$var[2][0]])){$countMarkArr['count']['count'][$var[2][0]] = 0;}
                    if(empty($countMarkArr['count'][$var[1][0] . '/'])){$countMarkArr['count'][$var[1][0] . '/'] = 0;}

                    $countMarkArr['count'][$var[2][0]]++;
                    $countMarkArr['count'][$var[1][0] . '/']++;
                } else {

                    /*
                    * Проверка на существование переменной;
                    */
                    if(empty($countMarkArr['count'][$markValue])){$countMarkArr['count'][$markValue] = 0;}

                    $countMarkArr['count'][$markValue]++;
                }
            }
        }
        return $countMarkArr;
    }

    /**
     * Подсчет оценок студента по ID
     *
     * @param $id
     * @param $startDate
     * @param $endDate
     * @return int
     */
    public function countMarksStudent($id,$startDate,$endDate)
    {
        $marks = $this->getStudentMarks($id, $startDate, $endDate);
        $marksArrByDiscipline = $this->filterReMarks($marks);
        return $this->countMarks($marksArrByDiscipline);
    }

    /**
     * Средний балл по факультету за выбранный период
     *
     * @param $faculty
     * @param $startDate
     * @param $endDate
     * @return false|float
     */
    public function getAverageFaculty($faculty, $startDate, $endDate)
    {
        $sum = 0;
        $index = 0;
        $arr['above4'] = 0;
        $arr['less3'] = 0;
        $arr['count2'] = 0;
        $arr['count2corrected'] = 0;

        foreach($faculty['items'] as $key => $group){

            $students = $this->getStudentsByGroup($group['id']);
            $this->group = self::getGroup($group['id']); //заполняем информацию о группе для дальнейшего наполнения данными;
            $this->fetchData();

            foreach($students as $student)
            {
                $p = $this->getAverageStudent($student['id'],$startDate,$endDate);
                $c = $this->countMarksStudent($student['id'],$startDate,$endDate);
                if(!is_null($c['count']['2'])){$arr['count2'] += 1;}
                if(!is_null($c['count']['2/'])){$arr['count2corrected'] += 1;}
                if($p >= 4 ){$arr['above4'] += 1;}
                if($p <= 3 ){$arr['less3'] += 1;}
                $sum += $p;
                $index++;

            }
        }

        return ['count' => $arr, 'average' => round($sum/$index,2)];
    }

    /**
     * Средний балл группы
     *
     * @param $group
     * @param $startDate
     * @param $endDate
     * @return false|float
     */
    public function getAverageGroup($group, $startDate, $endDate)
    {
        $students = $this->getStudentsByGroup($group['id']);

        $this->group = self::getGroup($group['id']); //заполняем информацию о группе для дальнейшего наполнения данными;
        $this->fetchData();

        $sum = 0;
        $index = 0;
        $arrStudentBall = [];
        foreach($students as $student)
        {
            $p = $this->getAverageStudent($student['id'],$startDate,$endDate);
            $arrStudentBall[] = $p;
            $sum += $p;
            $index++;
        }

        return [$arrStudentBall, 'average' => round($sum/$index,2)];
    }

    /**
     * Средний балл студента за период
     *
     * @param $id
     * @param $startDate
     * @param $endDate
     * @return array|null
     */
    public function getAverageStudent($id,$startDate,$endDate)
    {
        $marks = $this->getStudentMarks($id, $startDate, $endDate);
        $marksArrByDiscipline = $this->filterReMarks($marks);
        return $this->getAverageMarksStudent($marksArrByDiscipline);
    }

    public static function getGroupList(){
        return StudentsApi::getGroupList();
    }
}
