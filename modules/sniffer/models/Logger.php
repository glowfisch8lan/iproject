<?php


namespace app\modules\sniffer\models;


class Logger
{

    private static $dbFile = '@app/modules/sniffer/db/db.json';

    /**
     * Получение всех записей из файловой базы; можно переопределить на иное хранилище, например, Redis или SQL;
     *
     * @return array|mixed
     */
    public static function getAll(){

        $data = json_decode(file_get_contents(\Yii::getAlias(self::$dbFile)), 1);
        $data = ($data) ? $data : [];
        return $data;

    }

    /**
     * Запись значения в базу, в случае расширения базы - переопределить и убрать загрузку всего файла в память;
     *
     * @param array $data
     * @return array
     */

    private function set($data = []){
        if(!empty($data)) {
            $array = self::getAll();
            if(!is_array($array)){
                $array = [];
            }
            return array_merge($array, $data);
        }
    }

    /**
     * Запись значения в базу;
     *
     * @param array $data
     * @return bool
     */
    public function write($data = [])
    {
        if(!empty($data)){
            file_put_contents(\Yii::getAlias(self::$dbFile), json_encode($this->set($data)));
            return true;
        }

    }
}