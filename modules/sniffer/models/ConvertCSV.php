<?php


namespace app\modules\tools\models;


use app\modules\system\helpers\Inflector;
use yii\web\ServerErrorHttpException;

class ConvertCSV
{
    private function send($content){

        \Yii::$app->response->sendContentAsFile($content, md5(date('YmdHi')).'.csv');

    }

    private function convertUsername($file){

        $fp = fopen($file->tempName, 'r');
        if($fp)
        {
            $line = fgetcsv($fp, 1000, ";");
            $data = implode(';',$line)."\r\n";
            $mapKeys = array_flip($line);
            if(empty($mapKeys['username'])){throw new ServerErrorHttpException('Проверьте CSV файл: поле username не считывается!');}
            $first_time = true;
            do {
                if ($first_time == true) {
                    $first_time = false;
                    continue;
                }

                $str = $line[$mapKeys['lastname']].' '.$line[$mapKeys['firstname']];

                $line[$mapKeys['username']] = rand(0,100) . Inflector::translit(
                        Inflector::nameShort($str), '_', true
                    );

                $line[$mapKeys['email']] = $line[$mapKeys['username']] . '@dvuimvd.ru';
                $line[$mapKeys['password']] = 'dvui'.rand(1000,9999).'%';

                $array = implode(';',$line);
                $data .= "$array \r\n";

            }while( ($line = fgetcsv($fp, 1000, ";")) != FALSE);


        }
        $this->send($data);

    }

    public function init($file = null, $action)
    {
        if(empty($file)){
            return false;
        }

        switch($action){
            default:
                return false;
                break;

            case 'convertUsername':
                $this->convertUsername($file);
                break;
        }

    }



}