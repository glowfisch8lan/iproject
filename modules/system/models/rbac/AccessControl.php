<?php

namespace app\modules\system\models\rbac;

use Yii;
use app\modules\system\models\users\Groups;
use app\modules\system\models\users\Users;
use yii\helpers\Json;

class AccessControl
{

    /*
     *  Принимает id пользователя, доступ которого надо проверить;
     */
    public static function checkAccess( $id, $rule ): bool{

        $users = new Users();
        $group = new Groups();
        $data = [];

        foreach($users->getUserGroups($id) as $val){
            $data[] = $val['id'];
        }

        $groupPermissions = $group->getPermissions($data);
        $arr = [];
        foreach( $groupPermissions as $val )
        {
            $arr[] = Json::Decode($val['permissions']);
        }


        foreach($arr as $val ){
            if(in_array($rule, $val)){
                return true; //access granted;
            }
        }

        return false; //access deny;
    }


}
