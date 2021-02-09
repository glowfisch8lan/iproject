<?php

namespace app\modules\av\modules\student\models\plugins\reports\gradesheet;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Report extends \app\modules\av\modules\student\models\plugins\AcademicPerformance
{
    /**
     * Генерация отчета Ведомость Успеваемости
     *
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function generateGradeSheet()
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