<?php


namespace app\modules\av\models;

use Yii;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use yii\base\Model;
use yii\helpers\HtmlPurifier;

class Reports extends Model
{

    public $filename;
    public $html;

    public function rules(){
       return [
          [
              ['html', 'filename'],
              'required'
          ],
          [
          ['html', 'filename'],
              'safe'
          ]
      ];
    }

    public function generate()
    {

        $html = HtmlPurifier::process(base64_decode($this->html));
        $filename = ($this->filename == 'undefined') ? 'Отчет' : $this->filename;

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Html();
        $spreadsheet = $reader->loadFromString($html);
        $spreadsheet->getDefaultStyle()
            ->getFont()
            ->setName('Times New Roman')
            ->setSize(12);

//        $sheet = $spreadsheet->getActiveSheet();
//
//        foreach ($sheet->getRowIterator() as $row) {
//            $cellIterator = $row->getCellIterator();
//            $cellIterator->setIterateOnlyExistingCells(TRUE);
//            foreach ($cellIterator as $cell) {
//                    $cell->setHyperlink(null);
//            }
//
//        }

        //

        /*
         *  Отдаем XLS документ;
        */
        //header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$this->filename.'.xlsx"');
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