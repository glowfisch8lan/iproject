<?php

namespace app\modules\system\models\users;



use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use app\modules\system\models\auth\Auth;
use app\modules\system\components\behaviors\CachedBehavior;

class Users extends ActiveRecord implements IdentityInterface
{

    public $permissions;
    public $groups;

    protected $cache;

    /**
     * Users constructor. Определяем в какой кеш будем сбрасывать данные
     */
    public function __construct()
    {
        $this->cache = Yii::$app->cacheUsers;
    }

    /**
     * Правила валидации
     *
     * @return array
     */
    public function rules(){
        return [
            [
                ['login', 'name', 'groups'],
                'required',
                'message' => 'Заполните поля!'
            ],
            [
                ['login', 'name', 'groups', 'password'],
                'required',
                'message' => 'Заполните поля!',
                'on' => 'create'
            ],
            ['login', 'unique'],
            ['password', 'default', 'value' => null],
//            ['password', 'match', 'pattern' => '/[a-z0-9]*/', 'message' => 'Пароль не должен содержать пробелы'],

        ];
    }

    /**
     * Поведение перед сохранением в БД AR
     *
     * @param bool $insert
     * @return bool
     * @throws \yii\base\Exception
     */
    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert)){
            (empty($this->password) && Yii::$app->controller->action->id == 'update') ? null : $this->setPassword($this->password);
          return true;
        }
        return false;
    }

    /**
     * Определяем поведение, очищающее кеш, при записи в БД AR
     *
     * @return array
     */
    public function behaviors()
    {
        return [
            'CachedBehavior' => [
                'class' => CachedBehavior::class,
                'cache' => Yii::$app->cacheUsers
            ]
        ];
    }

    /**
     * Устанавливаем аттрибуты
     *
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'login' => 'Логин',
            'password' => 'Пароль',
            'userGroup' => 'Группа',
            'name' => 'Фамилия, имя и отчество'
        ];
    }

    /**
     * Определяем таблицу, привязанную к моделе
     *
     * @return string
     */
    public static function tableName()
    {
        return 'system_users';
    }

    /**
     * Получить информацию о членстве пользователя в группах
     *
     * @param $user_id
     * @return array | ['group_id','user_login', 'group_name']
     */
    public static function getUserGroups($user_id)
    {

        $cache = Yii::$app->cacheUsers;
        $duration = 12000;

        /**
         * Кеширование
         */
       // $response = $cache->get('userGroupsMembers');
        $response = false;
        if ($response === false) {

            $response = (new \yii\db\Query())
                ->select('system_groups.id as id, system_users.login as login, system_groups.name as group ')
                ->from('system_users')
                ->join('LEFT JOIN', 'system_users_groups', 'system_users.id = system_users_groups.user_id')

                ->join('LEFT JOIN', 'system_groups', 'system_users_groups.group_id = system_groups.id')

                ->where('user_id = :user_id')
                ->addParams([':user_id' => (int) $user_id])
                //->createCommand()->queryAll( \PDO::FETCH_CLASS);
                ->all();
            $cache->set('userGroupsMembers', $response, $duration);
        }
        return $response;


    }

    /**
     * Получить список пользователей
     *
     * @return array
     * @throws \yii\db\Exception
     */
    public function getUsersList(){

        return (new \yii\db\Query())
            ->select('
                `system_users`.`id` AS `id`,
                `system_users`.`login` AS `login`,
                `system_users`.`name` AS `name`

                ')
            ->from('system_users')
            //->join('LEFT JOIN', 'system_users', 'system_users_groups.user_id = system_users.id')
            ->createCommand()->queryAll( \PDO::FETCH_CLASS);

    }

    /**
     * Этот метод находит экземпляр identity class, используя ID пользователя. Этот метод используется, когда необходимо поддерживать состояние аутентификации через сессии.
     * @param int|string $id
     * @return Users|IdentityInterface|null
     */
    public static function findIdentity($id){
        return static::findOne($id);
    }

    /**
     * Этот метод находит экземпляр identity class, используя токен доступа. Метод используется, когда требуется аутентифицировать пользователя только по секретному токену
     * (например в RESTful приложениях, не сохраняющих состояние между запросами).
     *
     * @param mixed $token
     * @param null $type
     * @return void|IdentityInterface|null
     */
    public static function findIdentityByAccessToken($token, $type = null){
    }

    /**
     * Установить пользователю новый пароль
     * @param $password
     * @throws \yii\base\Exception
     */
    public function setPassword($password){
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Поиск пользователя по логину
     *
     * @param $login
     * @return Users|null
     */
    public static function findByUsername($login){
        return static::findOne(['login' => $login]);
    }

    /**
     * Этот метод возвращает ID пользователя, представленного данным экземпляром identity.
     *
     * @return int|mixed|string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Этот метод возвращает ключ, используемый для основанной на cookie аутентификации. Ключ сохраняется в аутентификационной cookie и позже сравнивается с версией, находящейся на сервере,
     * чтобы удостоверится, что аутентификационная cookie верная.
     * @return string|void
     */
    public function getAuthKey(){
        //   return $this->authKey;
    }

    /**
     * Этот метод реализует логику проверки ключа для основанной на cookie аутентификации.
     *
     * @param string $authKey
     * @return bool|void
     */
    public function validateAuthKey($authKey){
        //    return $this->authKey === $authKey;
    }

    /**
     * Проверяет пароль по хешу.
     *
     * @param $password
     * @return bool|mixed
     * @throws \Adldap\Auth\BindException
     * @throws \Adldap\Auth\PasswordRequiredException
     * @throws \Adldap\Auth\UsernameRequiredException
     */
    public function validatePassword($password){
        /* Если пароль не хранится в локальной Базе, авторизуемся через LDAP */
        if ($this->password == '' or $this->password == null) {

            $result = Auth::getInstance()->process($this->login, $password);
            if($result)
            {
                Auth::getInstance()->syncUserGroups($this->login, $password);
            }

            return $result;

        } else {
            /* В ином случае проверяем пароль локально */
            return Yii::$app->security->validatePassword($password, $this->password);
        }
    }

}
