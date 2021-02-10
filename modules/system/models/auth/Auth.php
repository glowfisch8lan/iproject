<?php


namespace app\modules\system\models\auth;

use Yii;
use yii\db\Exception;
use yii\helpers\Url;
use app\modules\system\helpers\ArrayHelper;
use app\modules\system\models\users\Groups;
use app\modules\system\models\users\Users;
use app\modules\system\models\users\LoginForm;
use app\modules\system\models\auth\LDAP;

class Auth
{
    protected static $_instance;
    public $hash;
    public $auth;

    private function __construct() {
    }

    public static function getInstance() {
        if (self::$_instance === null) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }

    public function process($login, $password)
    {
//        $modules = [
//            ['class' => 'app\modules\system\models\auth\Ldap']
//            ];
//        foreach($modules as $module)
//        {
            $auth = new LDAP();
            $result = $auth->authenticate($login, $password);
            return $result;
//        }

//        $result = $this->authenticate($username, $password);
//        if (is_array($result)) {
//            return self::login($result);
//        } else {
//            throw new \yii\base\Exception('Ошибка входа');
//        }

    }
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

    public function createUser($userData): bool
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

        if(!Groups::addMembers(ArrayHelper::indexMap($user->groups, $user->id)))
            throw new Exception('Ошибка создания групп для пользователя LDAP');

        return true;
    }
    public function syncUserGroups($login, $password)
    {
        $auth = new LDAP();
        $user = Users::findByUsername($login);
        $result = $auth->authenticate($login, $password);
        $groups = Users::getUserGroups($user->id);

        var_dump($result);

        foreach($groups as $group){
            array_search($group['id'], $result['groupsIds']);
            $key = array_search($group['id'], $result['groupsIds']);
                if(!$key){

                    Groups::removeGroupMember($user['id'], $group['id']);
                    Groups::addMembers([
                            [$user['id'], $result['groupsIds'][$key]]
                        ]);
                }
        }
        return;
    }
}