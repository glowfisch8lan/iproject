<?php

namespace app\modules\av\modules\student\models\plugins;


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use yii\base\Model;
use app\modules\system\helpers\ArrayHelper;
use app\modules\av\modules\student\models\StudentsApi;

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
        ];

    }

    /**
     * Наполнение модели данными
     *
     */
    public function fetchData()
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

        foreach ($marks as $key => $value)
        {

            $date = strtotime( preg_replace('/\s.*/m', '', $value['lesson_date']) );
            switch($this->session){
                case false:
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
                    break;
                case true:
                    if (
                        $date >= strtotime($datetime[0]) &&
                        $date <= strtotime($datetime[1]) &&
                        $value['control_type_id'] != null
                    )
                    {
                        $arr[] = $value;
                    }
                    break;

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
     * @param bool $session Вернуть только результаты сессий
     * @return array Идентификаторы отфильтрованных дисциплин
     */

    public function collectDisciplines()
    {
        $index = 0;

        foreach ($this->students as $student)
        {

            $id = $student['id'];
            $marksArray = $this->filterMarks($this->getMarks($id) , [$this->startDate , $this->endDate], $options['session']);
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

    public function generate()
    {

        $this->fetchData();

        /*
         * Начинаем генерацию XLS документа;
        */

        $spreadsheet = new Spreadsheet();

        $spreadsheet->getDefaultStyle()
            ->getFont()
            ->setName('Times New Roman')
            ->setSize(12);

        $sheet = $spreadsheet->getActiveSheet();

        $sheet->getDefaultColumnDimension()
            ->setWidth(20);

        $sheet ->getColumnDimension('A')
            ->setWidth(5);

        $sheet->getColumnDimension('B')
            ->setWidth(30);


        foreach (['A' => 'right', 'B' => 'left', 'C:Z' => 'center'] as $key => $value)
        {
             $sheet->getStyle($key)->getAlignment()
                ->setHorizontal($value);
        }

            $sheet->setCellValue('A1', '№');
            $sheet->setCellValue('B1', 'ФИО');

            $sheet->getStyle("C1:Y1")
            ->getAlignment()
            ->setWrapText(true); //включить перенос текста в колонках;

        $mapLetters = $this->createColumnsArray('ZZ'); //Создаем индексную карту документа;

        /*
         * Наполняем документ
         *
         */

        #
        # Студенты: ФИО
        #
        $index = 1;
        foreach ($this->students as $student)
        {
            $index++;
            $sheet->setCellValue('A' . $index, $index - 1);
            $sheet->setCellValue('B' . $index, $this->getShortName((object)$student));
        }

        #
        # Дисциплины
        #
        $list_disciplines = $this->collectDisciplines(); //собираем список дисциплин

        $index = 0;
        foreach ($list_disciplines as $id)
        {

            $discipline = $this->getDisciplineName($id);

            if($discipline)
            {
                 $index++;
                 $sheet->setCellValue($mapLetters[$index + 1] . 1, $discipline['name_short']);
                 $sheet->getColumnDimension($mapLetters[$index + 1])->setWidth(25);
                 $this->mapDisciplines[$id] = $mapLetters[$index + 1];
            }

        }

        $sheet->setCellValue($mapLetters[$index + 2] . 1, 'Ср. балл');


        #
        # Оценки студентов
        #
        $index = 1;
        foreach ($this->students as $student)
        {
            $index++;

            $marks = $this->filterMarks($this->getMarks($student['id']) , [$this->startDate , $this->endDate]);
            $marksArrByDiscipline = $this->filterReMarks($marks);

            /* Заполняем оценки по ячейкам */
                foreach ($marksArrByDiscipline as $id => $value)
                {
                    if (!empty($this->mapDisciplines[$id])) $sheet->setCellValue($this->mapDisciplines[$id].$index, implode(' ', $value['marks']));
                }
                /* Заполняем средний балл студента */
                $sheet->setCellValue($mapLetters[count($this->mapDisciplines) + 2] . $index, $this->getAverageMarksStudent($marksArrByDiscipline));
        }

        #
        # Ср. балл дисциплины
        #

        $marksArr = $this->filterReMarks($this->filterMarks($this->marks, [$this->startDate , $this->endDate]));
        $sum = $this->getAverageMarksDiscipline($marksArr);
        foreach ($sum as $id => $value)
        {
            if(!empty($this->mapDisciplines[$id])) {$sheet->setCellValue($this->mapDisciplines[$id] . (count($this->students) + 2) , $value['average']);}
        }
        $sheet->setCellValue('B' . (count($this->students) + 2) , 'Ср. балл');


        #
        # Кол-во оценок
        #

        $countMarkArr = $this->countMarks($marksArr);
        $additionFieldName = ['Кол-во 5', 'Кол-во 4', 'Кол-во 3', 'Кол-во 2/', 'Кол-во 2'];
        $additionFieldValue = ['5', '4', '3', '2/', '2'];

        $index = 4; //смещение вниз листа;
        foreach ($additionFieldName as $key => $value)
        {
            $sheet->setCellValue('B' . (count($this->students) + $index) , $value);
            $index++;
        }

        $index = 4;
        foreach ($additionFieldValue as $key => $value)
        {
            $sheet->setCellValue('C' . (count($this->students) + $index) , $countMarkArr['count'][$value]);
            $index++;
        }

        /*
         *  Отдаем XLS документ;
        */
        //header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Ведомость успеваемости ' . $this
                ->group['name'] . '.xlsx"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');

        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        die();
    }
}
