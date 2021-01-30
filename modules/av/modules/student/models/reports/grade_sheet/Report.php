<?php
namespace app\modules\av\models\students\reports\grade_sheet;

use Yii;
use app\modules\av\modules\student\models\StudentsApi;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * Class Report
 *
 * @package app\custom\modules\student\models\reports\journal_dvui
 */
class Report
{

    public $name = 'Ведомость успеваемости';
    public $group;
    public $students;
    public $marks; //все оценки по студентам группы;
    public $curriculumDisciplines;
    public $mapDisciplines;
    public $mapLetters;
    public $markValues;
    private $sheet;

    private $startDate = '01.09.2020';
    private $endDate = '26.01.2021';


    private function fetchData()
    {

        /*
         * загружаем информацию об учебной группе, получаем список только активных студентов
        */

        $this->group = StudentsApi::getGroup(24);

        $this->students = StudentsApi::getStudentsByGroup($this->group['id']);

        $this->curriculumDisciplines = StudentsApi::getCurriculumDisciplines($this->group['education_plan_id']);

        $this->marks = StudentsApi::getMarksByGroup($this->group['id']);

        $this->markValues = StudentsApi::getMarksValues();


    }

    private function createColumnsArray($end_column, $first_letters = '')
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

    private function collectDisciplines()
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

    private function getMarks($id)
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

    private function filterMarks($marks, $datetime)
    {

        foreach ($marks as $key => $value)
        {

            $date = strtotime($value['datetime']);

            if ($date >= strtotime($datetime[0]) && $date <= strtotime($datetime[1]))
            {
                $arr[] = $value;
            }

        }
        return $arr;

    }

    private function getDisciplineName($id)
    {


        $index = $this->recursiveArraySearch($id, $this->curriculumDisciplines);


        if (!empty($this->curriculumDisciplines[$index[0]]['name']))
        {

            $discipline['name'] = $this->curriculumDisciplines[$index[0]]['name'];
            $discipline['name_short'] = $this->curriculumDisciplines[$index[0]]['name_short'];

            return $discipline;
        }
        else return 0;

    }

    private function recursiveArraySearch($needle, $haystack)
    {

        $arr = null;
        foreach ($haystack as $key => $value)
        {
            $current_key = $key;

            if (array_search($needle, $value))
            {
                $arr[] = $current_key;
            }
        }

        return $arr;

    }

    private function fillHeaderDisciplines()
    {

        $list_disciplines = $this->collectDisciplines(); //собираем список дисциплин

        $index = 0;

        foreach ($list_disciplines as $id)
        {

            $discipline = $this->getDisciplineName($id);

            if($discipline)
            {
                $index++;
                $this
                    ->sheet
                    ->setCellValue($this->mapLetters[$index + 1] . 1, $discipline['name_short']);
                $this
                    ->sheet
                    ->getColumnDimension($this->mapLetters[$index + 1])->setWidth(25);
                $this->mapDisciplines[$id] = $this->mapLetters[$index + 1];
            }

        }

        $this
            ->sheet
            ->setCellValue($this->mapLetters[$index + 2] . 1, 'Ср. балл');

        return 0;

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

    private function fillListStudents()
    {

        $index = 1;
        foreach ($this->students as $student)
        {
            $index++;
            $this
                ->sheet
                ->setCellValue('A' . $index, $index - 1);
            $this
                ->sheet
                ->setCellValue('B' . $index, $this->getShortName((object)$student));

        }
        return 0;
    }

    private function filterReMarks($studentMarks)
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

    private function getMarkValue($id)
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

    private function getAverageMarksStudent($marksArrByDiscipline)
    {

        $sumMark = 0;
        $p = 0;

        foreach ($marksArrByDiscipline as $id => $value)
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

    private function getAverageMarksDiscipline($marksArr)
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

    private function fillCountMarks($countMarkArr)
    {
        $additionFieldName = ['Кол-во 5', 'Кол-во 4', 'Кол-во 3', 'Кол-во 2/', 'Кол-во 2'];
        $additionFieldValue = ['5', '4', '3', '2/', '2'];

        $index = 4; //смещение вниз листа;
        foreach ($additionFieldName as $key => $value)
        {
            $this
                ->sheet
                ->setCellValue('B' . (count($this->students) + $index) , $value);
            $index++;
        }

        $index = 4;
        foreach ($additionFieldValue as $key => $value)
        {
            $this
                ->sheet
                ->setCellValue('C' . (count($this->students) + $index) , $countMarkArr['count'][$value]);
            $index++;
        }
        return 0;
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

        $this->sheet = $spreadsheet->getActiveSheet();

        $this
            ->sheet
            ->getDefaultColumnDimension()
            ->setWidth(20);

        $this
            ->sheet
            ->getColumnDimension('A')
            ->setWidth(5);
        $this
            ->sheet
            ->getColumnDimension('B')
            ->setWidth(30);


        foreach (['A' => 'right', 'B' => 'left', 'C:Z' => 'center'] as $key => $value)
        {
            $this
                ->sheet
                ->getStyle($key)->getAlignment()
                ->setHorizontal($value);
        }

        $this
            ->sheet
            ->setCellValue('A1', '№');
        $this
            ->sheet
            ->setCellValue('B1', 'ФИО');

        $this
            ->sheet
            ->getStyle("C1:Y1")
            ->getAlignment()
            ->setWrapText(true); //включить перенос текста в колонках;

        $this->mapLetters = $this->createColumnsArray('ZZ'); //Создаем индексную карту документа;

        /*
         * Наполняем документ
         *
         */

        $this->fillHeaderDisciplines($this->sheet); //Заполняем заголовок дисциплин;
        $this->fillListStudents($this->sheet); //Заполняем список студентов;

        /* Выставляем оценки студентам */
        $index = 1;

        foreach ($this->students as $student)
        {
            $student = (object) $student;
            $index++;
            if (!empty($index))
            {
                $studentMarks = $this->filterMarks($this->getMarks($student->id) , [$this->startDate , $this->endDate]);

                $marksArrByDiscipline = $this->filterReMarks($studentMarks);

                /* Заполняем оценки по ячейкам */

                foreach ($marksArrByDiscipline as $id => $value)
                {
                    if (!empty($this->mapDisciplines[$id]))
                    {
                        $this
                            ->sheet
                            ->setCellValue($this->mapDisciplines[$id] . $index, implode('', $value['marks']));
                    }
                }
                /* Заполняем средний балл студента */
                $this
                    ->sheet
                    ->setCellValue($this->mapLetters[count($this->mapDisciplines) + 2] . $index, $this->getAverageMarksStudent($marksArrByDiscipline));

            }
        }
        /* Считаем средний балл каждой дисциплины */
        $marksArr = $this->filterReMarks($this->filterMarks($this->marks, [$this->startDate , $this->endDate]));

        $sum = $this->getAverageMarksDiscipline($marksArr);


        foreach ($sum as $id => $value)
        {
            if (!empty($this->mapDisciplines[$id]))
            {
                $this
                    ->sheet
                    ->setCellValue($this->mapDisciplines[$id] . (count($this->students) + 2) , $value['average']);
            }
        }

        $this
            ->sheet
            ->setCellValue('B' . (count($this->students) + 2) , 'Ср. балл');

        /* Расчет "Кол-во оценок" */
        $countMarkArr = $this->countMarks($marksArr);
        $this->fillCountMarks($countMarkArr);

        /*
         *  Отдаем XLS документ;
        */
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
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