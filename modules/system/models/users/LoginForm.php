<?php

namespace app\modules\system\models\users;

use Yii;
use yii\base\Model;
use app\modules\system\models\users\Users;
use app\modules\system\models\auth\LDAP;
use app\modules\system\models\auth\Auth;
/**
 * LoginForm is the model behind the login form.
 *
 * @property Users|null $user This property is read-only.
 *
 */
class LoginForm extends Model
{
    public $login;
    public $password;
    public $rememberMe = true;
    public static $sessionDuration = 2592000;

    private $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['login', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params): bool
    {
        if (!$this->hasErrors()) {

            /* Ищем пользователя в локальной базе*/
            $user = $this->getUser();
            /* Проверяем пароль локального пользователя; */
            $result = $user && $user->validatePassword($this->password);

            if ($result){
                return true;
            }


            //Включить LDAP авторизацию;
            /* Если пользователь с таким логином не найден, то запускаем процесс аутенфикации через LDAP и создание нового пользователя */
            $userData = Auth::getInstance()->process($this->login, $this->password);
            if (is_array($userData)) {
                if(Auth::getInstance()->createUser($userData)){
                    return true;
                }
            }

            if (isset($result[ 'error' ]))
                $this->addError($attribute, $result[ 'error' ]);

            $this->addError($attribute, 'Неверное имя пользователя или пароль.');
            return false;
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            $user = Users::findByUsername($this->login);
            return Yii::$app->user->login($user, $this->rememberMe ? 3600*24*30 : 0);
        }
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return Users|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = Users::findByUsername($this->login);
        }
        return $this->_user;
    }

    public function attributeLabels()
    {
        return [
            'login' => 'Логин',
            'rememberMe' => 'Запомнить',
            'password' => 'Пароль'
        ];
    }
}
