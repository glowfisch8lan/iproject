<?php


namespace app\modules\system\models\auth;
use app\modules\system\helpers\ArrayHelper;
use app\modules\system\models\users\Groups;
use Yii;
use app\modules\system\models\users\Users;
use app\modules\system\models\users\LoginForm;
use yii\db\Exception;
use yii\helpers\Url;

class Auth
{

    /**
     * Регистрация в системе пользователя с данными $userData, полученными от провайдера аутентификации, и его авторизация.
     *
     * [
     *   'name',
     *   'login',
     *   'groupsIds',
     *   'picture',
     *   'student' => [
     *     'family_name',
     *     'name',
     *     'surname',
     *     'sex',
     *     'birthday',
     *     'group_name',
     *     'registration',
     *   ]
     * ]
     *
     * @param $userData
     * @return bool|array
     */

    protected static function login($userData)
    {
        // пробуем найти указанного пользователя
        $user = Users::findOne(['login' => $userData[ 'login' ]]);

        if (is_null($user)) {
            $user = new Users();
        }
        $user->attributes = $userData;
        $user->groups = $userData['groupsIds'];

        if(!$user->save())
            throw new Exception('Error save user LDAP');

        Groups::addMembers(ArrayHelper::indexMap($user->groups, $user->id));

        Yii::$app->user->login($user, LoginForm::$sessionDuration);
        Yii::$app->getResponse()->redirect('/my');


    }
}