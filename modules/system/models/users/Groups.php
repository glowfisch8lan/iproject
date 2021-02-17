<?php

namespace app\modules\system\models\users;


use app\modules\system\models\settings\Settings;
use Yii;
use yii\db\ActiveRecord;
use app\modules\system\models\users\Users;
use app\modules\system\models\interfaces\modules\Modules;
use app\modules\system\components\behaviors\CachedBehavior;
use app\modules\system\models\cache\Cache;
class Groups extends ActiveRecord
{

    use Cache;

 /*@var $name <- колонка в таблице system_groups
 * @var $description;
 * @var $permissions;
 * @var $listPermissions;
 */

    public $listMembers; //здесь хранится список пользователей, которых нужно добавить в группу
    public $listUsers; //здесь хранится список всех пользователей
    public $action; //переменная, в которую записываем действие, которое нужно выполнить actionView()
    public $usedPermissions; //переменная, в которую записываем список используемых разрешений группой

    protected $cache;

    /**
     * Users constructor. Определяем в какой кеш будем сбрасывать данные
     */
    public function __construct()
    {
        $this->cache = Yii::$app->cacheGroups;
    }

    public static function tableName()
    {
        return 'system_groups';
    }

    public function rules(){
        return [
            ['name', 'required',  'message' => 'Пожалуйста, заполните имя группы!'],
            [['description', 'permissions', 'listMembers', 'action'], 'safe']
        ];

    }

    public function attributeLabels()
    {
        return [
            'name' => 'Группа',
            'permissions' => 'Разрешения',
            'description' => 'Описание'
        ];
    }

    public function behaviors()
    {
        return [
            'CachedBehavior' => [
                'class' => CachedBehavior::class,
                'cache' => Yii::$app->cacheGroups
            ]
        ];
    }

    /**
     * Разрешения для группы
     *
     * @param $id Идентификатор группы
     * @return array | 'permissions' => '['permission',...]
     */
    public static function getPermissions($group_id){

        /**
         * Кеширование списка групп
         */

        $response = self::cache()->get('permissions'.$group_id[0]);

        if(!$response)
        {
            $response = (new \yii\db\Query())->select('permissions')->from('system_groups')
            ->where(['or', ['id' => $group_id ]])->one();
            self::cache()->set('permissions'.$group_id[0], $response);

        }

        return $response;
    }

    /**
     * Список участников группы
     *
     * @param $id Идентификатор группы
     * @return array
     */
    public function getGroupMembers($id){

        return (new \yii\db\Query())
            ->select(' 
                system_users_groups.user_id as id,
                system_users.login as login,
                system_users.name as name,
                system_groups.name as group
                ')
            ->from('system_users_groups')
            ->join('LEFT JOIN', 'system_users', 'system_users_groups.user_id = system_users.id')
            ->join('LEFT JOIN', 'system_groups', 'system_users_groups.group_id = system_groups.id')
            ->where('group_id = :group_id')
            ->addParams([':group_id' => (int) $id])
            ->all();

    }

    /**
     * Удалить пользователя из группы
     *
     * @param $user_id Идентификатор пользоватедя;
     * @param $group_id Идентификатор группы
     * @return boolean
     */

    public static function removeGroupMember($user_id, $group_id){

        return (new \yii\db\Query())
            ->createCommand()
            ->delete('system_users_groups', ['user_id' => (int) $user_id, 'group_id' => (int) $group_id])
            ->execute();


    }

    /**
     * Список всех групп
     *
     * @return array
     */
    public static function getAllGroupList(): array{
        return self::find()->asArray()->all();
    }

    public static function addMembers($array): bool{
        /*
         *  Принимает массив вида [['user_id', 'group_id'], ['user_id' => 'group_id']];
         */
        if(!empty($array)){
            $fields = ['user_id', 'group_id'];
            $db = Yii::$app->db;
            $sql = $db->queryBuilder->batchInsert('system_users_groups', $fields, $array);
            $product_insert_count = $db->createCommand($sql . ' ON DUPLICATE KEY UPDATE user_id = VALUES(user_id), group_id = VALUES(group_id)')->execute();
            return true;
        }
        return false;
    }

    public static function removeAllGroupMember($user_id){
        return (new \yii\db\Query())
            ->createCommand()
            ->delete('system_users_groups', ['user_id' => (int) $user_id ])
            ->execute();
    }
}