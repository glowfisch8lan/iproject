<?php


namespace app\modules\system\models\auth;


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

    }
}