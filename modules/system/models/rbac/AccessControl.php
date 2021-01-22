<?php

namespace app\modules\system\models\rbac;

use Yii;
use app\modules\system\models\users\Groups;
use app\modules\system\models\users\Users;
use yii\db\Exception;
use yii\helpers\Json;
use yii\web\ServerErrorHttpException;

class AccessControl
{

    /*
     *  Принимает id пользователя, доступ которого надо проверить;
     */
    public static function checkAccess( $id, $rule ): bool{

        $users = new Users();

        $data = [];

        foreach($users->getUserGroups($id) as $val){
            $data[] = $val['id'];
        }

        $groupPermissions = Groups::getPermissions($data);
        $arr = [];
        foreach( $groupPermissions as $val )
        {
            $arr[] = Json::Decode($val['permissions']);
        }

        if(count($arr) < 2 && empty($arr[0])){
            Yii::$app->user->logout();
            throw new ServerErrorHttpException('В группе отсутствуют разрешения, дальнейшая работа невозможна!');
        }
        foreach($arr as $val ){
            if(in_array($rule, $val)){
                return true; //access granted;
            }
        }

        return false; //access deny;
    }


}
