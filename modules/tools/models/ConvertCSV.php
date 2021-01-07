<?php


namespace app\modules\tools\models;


use app\modules\system\helpers\Inflector;

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
            $line[] = 'profile_field_group';
            $data = implode(';',$line)."\r\n";
            $mapKeys = array_flip($line);

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
                $line[$mapKeys['profile_field_group']] = 0;

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