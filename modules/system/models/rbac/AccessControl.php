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

    /**
     *
     * @param int $user_id
     * @param string $rule
     * @return bool
     * @throws ServerErrorHttpException
     */

    public static function checkAccess( int $user_id, string $rule ): bool{


        $arr = [];
        foreach(Users::getUserGroups($user_id) as $group){
            $arr[] = Json::Decode(Groups::getPermissions($group['id'])['permissions']);
        }

        if(empty($arr)){
            Yii::$app->user->logout();
            throw new ServerErrorHttpException('В группе отсутствуют разрешения, дальнейшая работа невозможна!');
        }

        foreach($arr as $val )
            if(in_array($rule, $val)) return true; //access granted;

        return false; //access deny;
    }


}
