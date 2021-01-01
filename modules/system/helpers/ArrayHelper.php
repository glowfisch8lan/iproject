<?php


namespace app\modules\system\helpers;


class ArrayHelper extends \yii\helpers\ArrayHelper
{

    /*
     * Приводит индексный массив к  массиву вида [[ $key, $value ]];
     */
    public static function indexMap($array,$key){

        $dataArray = [];var_dump($key);
        foreach($array as $value) {
            $dataArray[] = [(int) $key, (int) $value];
        }
        return $dataArray;
    }

    public static function mapMerge($array, $mainKey, $mergeKeys, $separator = null){
        $rows = [];
        foreach($array as $value){

            $string = null;
            foreach($mergeKeys as $key) {
                $string .= (string) $value[$key] . $separator;
            }
            $rows[$value[$mainKey]] = $string;
        }

        return $rows;

    }

}