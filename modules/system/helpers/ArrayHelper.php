<?php


namespace app\modules\system\helpers;


class ArrayHelper extends \yii\helpers\ArrayHelper
{

    /*
     * Перестройка индексного массива к  массиву вида [[ $key, $value ]];
     */
    public static function indexMap($array,$key){

        $dataArray = [];var_dump($key);
        foreach($array as $value) {
            $dataArray[] = [(int) $key, (int) $value];
        }
        return $dataArray;
    }

    /*
     * Перестройка массива к  массиву вида [[ $key => $value1 . $value2 . $valueN ]];
     */
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