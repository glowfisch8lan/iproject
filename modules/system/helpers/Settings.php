<?php


namespace app\modules\system\helpers;


class Settings
{

    public static function menu($menuItems){
        $form1 = null;
        foreach($menuItems as $item){
            $form1 .= '<li class="nav-item"><a class="nav-link '.(empty($item['isActive'])?null:$item['isActive']).'" id="'.$item['id'].'-tab" data-toggle="tab" href="#'.$item['id'].'" role="tab" aria-controls="'.$item['id'].'" aria-selected="true">'.$item['name'].'</a></li>';
        }
        return (!empty($form1)) ? $form1 : false;
    }
}