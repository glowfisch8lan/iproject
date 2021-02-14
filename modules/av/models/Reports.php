<?php


namespace app\modules\av\models;

use Yii;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use yii\helpers\HtmlPurifier;

class Reports
{


    public function generate()
    {

        $req = Yii::$app->request;
        $html = HtmlPurifier::process(base64_decode($req->post('h')));
        $filename = ($req->post('filename') == 'undefined') ? 'Отчет' : $req->post('filename');
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
        header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
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