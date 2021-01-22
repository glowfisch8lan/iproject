<?php

namespace app\modules\metrica\models\report;

use Yii;
use yii\base\Model;
use yii\web\HttpException;

class Report extends Model
{

    public $template;

    public function rules()
    {
        return [
            [['template'], 'file', 'skipOnEmpty' => false, 'extensions' => 'docx'],
        ];
    }
    public function __construct($config = [])
    {
        Yii::setAlias('@temp', '@runtime/temp/metrica');
        parent::__construct($config);
    }


    public function generate()
    {

        $file = Yii::getAlias('@resources');

        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($file);

        $templateProcessor->setValue('name', '777');
        $templateProcessor->setValue('id', '1111');
        $templateProcessor->setValue('company', '4334');

        $filename = 'HelloWorld.docx';

        header("Content-Description: File Transfer");
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');

        $templateProcessor->saveAs('php://output');

        #$document->save($filename, $format = 'Word2007', $download = false);
        exit();

/*        file_put_contents(Yii::getAlias('@temp').'/test', 'asdsa');

        $filePath = '../uploads/' . $filename . '.' . $expansion;*/


/*        if (file_exists($filePath)) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime  = finfo_file($finfo, $filePath);
            finfo_close($finfo);
            $size  = filesize($filePath);

            header("Content-Type: ".$mime);
            header("Content-Length: ".$size);
            header("Content-Disposition: attachment; filename=\"" . $filename . '.' . $expansion . "\"");
            readfile($filePath);
        } else {
            throw new HttpException(404, 'Файл не найден');
        }*/


    }

}
