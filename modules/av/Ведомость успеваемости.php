<?php
namespace app\custom\modules\student\models\reports\journal_dvui;

use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;

use app\modules\load\models\groups\Groups;
use app\modules\student\models\students\Students;
use app\modules\student\models\lists\MarkValues;

use app\modules\plan\models\helpers\Calendar;

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
class Report extends \app\models\interfaces\Report
{
    public $name = "Ведомость успеваемости ДВЮИ (Разработчик: Кафедра ИиТО ОВД)";
    public $description = "Печать ведомости успеваемости для группы обучающихся ДВЮИ";
    public $sort = 80;

    public function getParams()
    {
        $groups = (new Query())->select(['p.id', 'p.name', 'p.start_date', 'p.education_plan_id'])
            ->from('load_groups p')
            ->leftJoin('plan_education_plans s', 'p.education_plan_id = s.id')
            ->where(['s.data_type' => ['plan', 'category']])
            ->andWhere(['education_level_id' => 5])
            ->andWhere(['or', ['not like', 'p.name', '%ФЗО', false], ['like', 'p.name', 'ФЗО%', false]])
            ->all();

        $planStartYears = ArrayHelper::map((new Query())->select(['p.id', 'p.custom_start_year', 's.start_date'])
            ->from('plan_education_plans p')
            ->leftJoin('(SELECT * FROM plan_semesters WHERE course = 1 AND semester = 1) s', 's.education_plan_id = p.id')
            ->where(['data_type' => ['plan', 'category', ], ])
            ->all() , 'id', function ($item)
        {
            return isset($item['custom_start_year']) ? $item['custom_start_year'] : Calendar::getEducationYear($item['start_date']);
        });

        usort($groups, function ($a, $b) use ($planStartYears)
        {
            if ($planStartYears[$a['education_plan_id']] != $planStartYears[$b['education_plan_id']]) return $planStartYears[$b['education_plan_id']] - $planStartYears[$a['education_plan_id']];

            return strcmp($a['name'], $b['name']);
        });

        usort($groups, function ($a, $b) use ($planStartYears)
        {

            if ($planStartYears[$a['education_plan_id']] != $planStartYears[$b['education_plan_id']])
            {
                return $planStartYears[$b['education_plan_id']] - $planStartYears[$a['education_plan_id']];
            }

            return strcmp($a['name'], $b['name']);
        });

        return ['groupId' => ['name' => 'Группа', 'type' => 'list', 'required' => true, 'items' => ArrayHelper::map($groups, 'id', function ($item) use ($planStartYears)
        {
            return ($planStartYears[$item['education_plan_id']] > 0 ? '[Набор ' . $planStartYears[$item['education_plan_id']] . '] ' : '') . $item['name'];
        }) , ],

        'startDate' => ['name' => 'Дата начала', 'type' => 'date', 'required' => true, 'default' => '01.09.' . Calendar::getEducationYear() , ],

        'endDate' => ['name' => 'Дата окончания', 'type' => 'date', 'required' => true, 'default' => date('d.m.Y') ]];

    }

    public $group;
    public $students;
    public $marks; //все оценки по студентам группы;
    public $curriculumDisciplines;
    public $mapDisciplines;
    public $mapLetters;
    public $markValues;
    private $sheet;

    private function fetchData()
    {

        /*
         * загружаем информацию об учебной группе, получаем список только активных студентов
        */

        $this->group = Groups::findOne($this->getParam('groupId'));
        $this->students = Students::find()
            ->where(['group_id' => $this
            ->group
            ->id])
            ->andWhere(['active' => 1])
            ->all();

        $this->curriculumDisciplines = (new Query())
            ->select(['p.id', 'p.code', 'p.discipline_id', 'p.level', 's.name', 's.name_short'])
            ->from('plan_curriculum_disciplines p')
            ->leftJoin('plan_disciplines s', 'p.discipline_id = s.id')
            ->where(['p.education_plan_id' => $this
            ->group->education_plan_id, 'p.type' => null, 'p.level' => 3, ])
            ->andWhere(['not', ['s.name' => null]])
            ->orderBy(['left_node' => SORT_ASC])
            ->all();

        $this->marks = (new Query())
            ->select(['s.id', 's.journal_lesson_id', 's.student_id', 's.mark_type_id', 's.mark_value_id', 's.datetime', 's.parent_id', 'p.curriculum_discipline_id'])
            ->from('student_marks s')
            ->leftJoin('student_journal_lessons p', 's.journal_lesson_id = p.id')
            ->where(['in', 'student_id', array_column($this->students, 'id') ])
            ->andWhere(['not', ['mark_value_id' => null]])
            ->all();

        $this->markValues = MarkValues::find()
            ->asArray()
            ->all();

    }

    private function recursiveArraySearch($needle, $haystack)
    {

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

    private function collectDisciplines()
    {

        $index = 0;
        foreach ($this->students as $student)
        {

            $marksArray = $this->filterMarks($this->getMarks($student->id) , [$this->getParam('startDate') , $this->getParam('endDate') ]);

            foreach ($marksArray as $marks)
            {
                $collection[$marks['curriculum_discipline_id']] = $index;
                $index++;
            }

        }
        ksort($collection);

        return array_keys($collection);
    }

    private function getDisciplineName($id)
    {

        $index = $this->recursiveArraySearch($id, $this->curriculumDisciplines);
        if (!empty($this->curriculumDisciplines[$index[0]]['name']))
        {
            $discipline->name = $this->curriculumDisciplines[$index[0]]['name'];
            $discipline->name_short = $this->curriculumDisciplines[$index[0]]['name_short'];

            return $discipline;
        }
        else return 0;

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

    private function fillHeaderDisciplines()
    {

        $list_disciplines = $this->collectDisciplines();

        $index = 0;

        foreach ($list_disciplines as $id)
        {
            $discipline = $this->getDisciplineName($id);

            if ($discipline)
            {
                $index++;
                $this
                    ->sheet
                    ->setCellValue($this->mapLetters[$index + 1] . 1, $discipline->name_short);
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
                ->setCellValue('B' . $index, $student->shortName);

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
            $marks = & $value['marks'];
            $parent_id = & $value['parent_id'];

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

        return $marksArrByDiscipline;
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

    /*
     *	Считаем средний балл студента слева-направо в таблице Excel;
     * 	$marksArrByDiscipline
    */
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

    private function countMarks($marksArrByDiscipline)
    {

        foreach ($marksArrByDiscipline as $key => $value)
        {

            foreach ($value['marks'] as $mark => $markValue)
            {

                if (preg_match_all("/\(([0-9]+)\/([0-9]+)\)/", $markValue, $var))
                {

                    $countMarkArr['count'][$var[2][0]]++;
                    $countMarkArr['count'][$var[1][0] . '/']++;
                }
                else
                {
                    $countMarkArr['count'][$markValue]++;
                }
            }
        }
        return $countMarkArr;
    }

    private function getAverageMarksDiscipline($marksArr)
    {

        foreach ($marksArr as $id => $value)
        {

            $index = 0;

            foreach ($value as $type => $arr)
            {
                if ($type == 'marks')
                {

                    foreach ($arr as $key => $mark)
                    {

                        if (preg_match_all("/\(([0-9]+)\/([0-9]+)\)/", $mark, $var))
                        {

                            $sum[$id]['average'] += $var[1][0];
                            $sum[$id]['average'] += $var[2][0];
                            $index++;
                        }
                        else
                        {

                            $sum[$id]['sum'] += $mark;
                            $index++;
                        }
                    }
                }
            }
            $sum[$id]['count'] = $index;

            $sum[$id]['average'] = round($sum[$id]['sum'] / $sum[$id]['count'], 2);

        }
        return $sum;
    }

    public function generate()
    {
        $this->fetchData();

        /*
         * Начинаем генерацию XLS документа;
        */

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()
            ->setCreator(Yii::$app
            ->user
            ->identity
            ->login)
            ->setTitle($this->name)
            ->setDescription($this->description);

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

        $arr1 = ['A' => 'right', 'B' => 'left', 'C:Z' => 'center'];
        foreach ($arr1 as $key => $value)
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
        $this->mapLetters = $this->createColumnsArray('ZZ');

        /* Наполняем документ данными */

        $this->fillHeaderDisciplines($this->sheet); //Заполняем заголовок дисциплин;
        $this->fillListStudents($this->sheet); //Заполняем список студентов;
        

        /* Выставляем оценки студентам */
        $index = 1;

        foreach ($this->students as $student)
        {

            $index++;

            if (!empty($index))
            {
                $studentMarks = $this->filterMarks($this->getMarks($student->id) , [$this->getParam('startDate') , $this->getParam('endDate') ]);
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
        $marksArr = $this->filterReMarks($this->filterMarks($this->marks, [$this->getParam('startDate') , $this->getParam('endDate') ]));

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
            ->group->name . '.xlsx"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');

        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }
}

