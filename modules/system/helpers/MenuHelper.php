<?php


namespace app\modules\system\helpers;

use Yii;
use app\modules\system\models\rbac\AccessControl;

class MenuHelper
{

    private static function formData($data): string{
        $user_id = Yii::$app->user->identity->id;
        $code = null;
        foreach($data as $params){
            if(AccessControl::checkAccess($user_id,$params->visible)) {
                $code .= '<li><a href="#' . $params->id . '" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle no-caret-down">';
                $code .= '<i class="' . $params->icon . '"></i> ' . $params->name . '</a>';
                $code .= '<ul class="collapse list-unstyled dropdown-a" id="' . $params->id . '">';

                foreach ($params->routes as $route) {
                    if (AccessControl::checkAccess($user_id, $route['access']) && $route['visible']) {
                        $code .= '<li><a href="' . $route['route'] . '">' . $route['name'] . '</a></li>';
                    }
                }
                $code .= '</ul></li>';
            }
        }
        return $code;
    }
    public static function widget($data): string{
        return self::formData($data);
    }
}