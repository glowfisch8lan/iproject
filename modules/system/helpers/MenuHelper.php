<?php


namespace app\modules\system\helpers;

use Yii;
use app\modules\system\models\rbac\AccessControl;

class MenuHelper
{

    private static function buildMenu($data){
        $user_id = Yii::$app->user->identity->id;
        $code = null;
        foreach($data as $params){
            if(AccessControl::checkAccess($user_id,$params->visible)) {
                $code .= '<li class="sidebar-menu-items"><a href="#' . $params->id . '" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle no-caret-down menu-link-dropdown"><i class="' . $params->icon . '"></i> ' . $params->name . '</a><ul class="collapse list-unstyled dropdown-a" id="' . $params->id . '">';

                foreach ($params->routes as $route) {
                    $code .= (AccessControl::checkAccess($user_id, $route['access']) && $route['visible']) ? '<li><a href="' . $route['route'] . '" id="'.$route['access'].'">' . $route['name'] . '</a></li>' : null;
                }
                $code .= '</ul></li>';
            }
        }
        return $code;
    }
    public static function widget($data){
        return self::buildMenu($data);
    }
}