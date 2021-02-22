<?php


namespace app\modules\system\helpers;


class Inflector extends \yii\helpers\Inflector
{

    /*
     * Сокращение ФАМИЛИЯ ИМЯ ОТЧЕСТВО до ФАМИЛИЯ ИО
     */
    public static function nameShort($str, $replacement = null){

        preg_match_all('/ (.)/iu',$str,$m);
        $m2 = explode(' ', $str);
        return $m2[0] .' '. $m[1][0] . $replacement . $m[1][1] . $replacement;
    }
    public static function translit($str, $replacement = null, $lowercase = false){
        return Inflector::slug($str, $replacement , $lowercase);
    }
}