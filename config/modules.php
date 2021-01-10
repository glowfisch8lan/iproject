<?php

$modules = function(){
    $list = array_map(function($item) {
        return basename($item);
    }, glob(realpath(__DIR__ . '/../modules') . '/*'));
    $result = null;
    foreach($list as $item){
        $result[$item] = [
            'class' => '\\app\\modules\\' . $item . '\\Module',
        ];
    }
    return $result;
};