<?php


namespace app\modules\system\helpers;


class ArrayHelper extends \yii\helpers\ArrayHelper
{

    /*
     * Перестройка индексного массива к  массиву вида [[ $key, $value ]];
     */
    public static function indexMap($array,$key){

        $dataArray = [];
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

    /**
     * Рекурсивный поиск в массиве
     *
     * @return array | keys
     */
    public static function recursiveArraySearch($needle, $haystack)
    {

        $arr = null;
        foreach ($haystack as $key => $value)
        {
            $current_key = $key;

            if (array_search($needle, $value))
            {
                $arr[] = $current_key;
            }
        }

        return $arr;

    }
    /**
     * Рекурсивный поиск в массиве
     *
     * @return array | keys
     */
    public static function ArrayValueFilter($array, $key, $value)
    {

        $arr = null;
        foreach($array as $k => $v){
            if($v[$key] == $value){
                $arr[] = $v;
            }
        }

        return $arr;

    }
}