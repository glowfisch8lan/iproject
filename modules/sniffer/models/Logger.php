<?php


namespace app\modules\sniffer\models;


class Logger
{

    private static $dbFile = '@app/modules/sniffer/db/db.json';

    public static function getAll(){

        $data = json_decode(file_get_contents(\Yii::getAlias(self::$dbFile)), 1);
        $data = ($data) ? $data : [];
        return $data;

    }

    public function set($data = []){
        if(!empty($data)) {
            $array = self::getAll();
            if(!is_array($array)){
                $array = [];
            }
            return array_merge($array, $data);
        }
    }

    public function write($data = [])
    {
        if(!empty($data)){
            file_put_contents(\Yii::getAlias(self::$dbFile), json_encode($this->set($data)));
            return true;
        }

    }
}