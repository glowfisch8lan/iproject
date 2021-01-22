<?php

namespace app\modules\system\models\users;


use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class Users extends ActiveRecord implements IdentityInterface
{

    public $permissions;
    public $groups; // Список груп

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

    public function beforeSave($insert)
    {

        if(parent::beforeSave($insert)){

            (empty($this->password) && Yii::$app->controller->action->id == 'update') ? null : $this->setPassword($this->password);
          return true;
        }
        return false;
    }

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

    public static function tableName()
    {
        return 'system_users';
    }

    /*
     * Получить информацию о членстве пользователя в группах
     */
    public static function getUserGroups($user_id)
    {


        return (new \yii\db\Query())
            ->select('system_groups.id as id, system_users.login as login, system_groups.name as group ')
            ->from('system_users')
            ->join('LEFT JOIN', 'system_users_groups', 'system_users.id = system_users_groups.user_id')

            ->join('LEFT JOIN', 'system_groups', 'system_users_groups.group_id = system_groups.id')

            ->where('user_id = :user_id')
            ->addParams([':user_id' => (int) $user_id])
            ->all();
            //->createCommand()->queryAll( \PDO::FETCH_CLASS);

    }

    public function getUsers(){

        return (new \yii\db\Query())
            ->select('
                `system_users`.`id` AS `id`,
                `system_users`.`login` AS `login`,
                `system_users`.`name` AS `name`,
                `system_users`.`password` AS `password`,
                ')
            ->from('system_users');
    }

    /*
     *  Возвращает объекты
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

    public static function findIdentity($id){
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null){
    }

    public function setPassword($password){
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }

    public static function findByUsername($login){
        return static::findOne(['login' => $login]);
    }

//    public function getId(){
//        return Yii::$app->user->identity->id;
//    }

    public function getId()
    {
        return $this->id;
    }
    public function getAuthKey(){
        //   return $this->authKey;
    }

    public function validateAuthKey($authKey){
        //    return $this->authKey === $authKey;
    }

    public function validatePassword($password){
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    public function getRole()
    {

        return array_values(Yii::$app->authManager->getRolesByUser($this->id))[0];

    }

}
