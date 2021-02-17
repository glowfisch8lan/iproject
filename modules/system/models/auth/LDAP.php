<?php


namespace app\modules\system\models\auth;

use Yii;
use Adldap\Adldap;
use Adldap\Auth\PasswordRequiredException;
use Adldap\Auth\UsernameRequiredException;
use Adldap\Connections\ProviderInterface;
use Adldap\Auth\Events\Failed;
use Adldap\Models\Attributes\DistinguishedName;

use app\modules\system\models\users\Groups;
use DateInterval;
use DateTime;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use app\modules\system\models\auth\Auth;

use app\modules\system\models\settings\Settings;

/**
 * Class LDAP
 * @package app\models\auth
 *
 * Авторизация на LDAP-сервере.
 * Поддерживается Microsoft ActiveDirectory.
 */
class LDAP
{
    const BAD_SYMBOLS = '/\[]:;|=,+*?<>"';

    public $name = 'LDAP (Microsoft ActiveDirectory)';
    private $client;
    private $provider;
    private $connected;
    public  $error;

    public $config = [
        // An array of your LDAP hosts. You can use either
        // the host name or the IP address of your host.
        'hosts'    => ['172.16.20.31'],
        'account_suffix'   => '@dvuimvd.ru',
        // The base distinguished name of your domain to perform searches upon.
        'base_dn'  => 'DC=dvuimvd,DC=ru',

        // The account to use for querying / modifying LDAP records. This
        // does not need to be an admin account. This can also
        // be a full distinguished name of the user account.
        'username' => 'avtorvuz',
        'password' => 'avtorvuz',
        'port' => '389'
    ];

    public function __construct()
    {

        $this->client = new Adldap();
        $this->client->addProvider($this->config);


    }

    /**
     *  Проверка статуса плагина Авторизации;
     *
     * @return bool
     */
    public static function status()
    {
        if(filter_var(Settings::getValue('system.auth.ldap.status'), FILTER_VALIDATE_BOOLEAN))
            return true;

        return false;
    }

    public function getValue($value)
    {
        return $this->config[$value];
    }
    /**
     * LDAP constructor.
     *
     * @param array $settings
     */

    /**
     * Проверка подключения.
     */
    private function isConnected()
    {
        // TODO: кешировать отрицательный результат на 1 минуту с указанными настройками (иначе будет больше время доступа)

        if (is_null($this->connected)) {
            try {
                $this->provider = $this->client->connect();
                $this->connected = true;

            } catch (\Adldap\Auth\BindException $e) {
                $this->error = $e->getMessage() .
                    ($e->getDetailedError()->getDiagnosticMessage() != '' ? ' [' . $e->getDetailedError()->getDiagnosticMessage() . ']' : '');
                $this->connected = false;
            }
        }
        return $this->connected;
    }

    /**
     * Режим администратора или режим обычного пользователя.
     *
     * @return bool
     */
    private function isAdmin()
    {
        return $this->isConnected() && $this->config['username'] != '';
    }

    /**
     * Обработка данных аутентификации (логина и пароля).
     * Функция должна возвращать false, если аутентификация не прошла, или массив атрибутов для создания нового пользователя.
     *
     * @param $login
     * @param $password
     * @return mixed
     * @throws \Adldap\Auth\BindException
     * @throws \Adldap\Auth\PasswordRequiredException
     * @throws \Adldap\Auth\UsernameRequiredException
     */
    public function authenticate($login, $password)
    {

       $this->syncGroups();

        $config = $this->config;
        $config['port'] = '3268';


        $this->client = new Adldap();
        $this->client->addProvider($config);
        $this->connected = null;

        if (!$this->isConnected())
            return false;
        try {

            if($this->provider->auth()->attempt(str_replace($this->getValue('account_suffix'), '', $login), $password)){
            $user = $this->provider->search()->findBy('samaccountname', $login);
            $ldapGroups = array_map(function($item) {

                    return preg_replace('/^iDapp\s*\-\s*/ui', '', $item->getName());
                }, array_filter($user->getGroups()->all(), function($item) {

                    return preg_match('/^iDapp\s*\-\s*/ui', $item->getName());
                }));



                $systemGroups = ArrayHelper::map(Groups::getAllGroupList(), 'id', function($item) {
                    return str_replace(str_split(self::BAD_SYMBOLS), '_', $item['name']);
                });

                $userData = [
                    'name' => $user->getCommonName(),
                    'login' => $login,
                    'password' => $password,
                    'groupsIds' => array_keys(array_intersect($systemGroups, $ldapGroups)),
                ];

                return $userData;
                }
            else {return false;}



//
//                if (empty($ldapGroups)) {
//                    $this->error = 'Пользователь ' . $login .' не входит ни в одну из групп iDapp.';
//                    return false;
//                }
//
//                $systemGroups = ArrayHelper::map(Groups::find()->all(), 'id', function($item) {
//                    return str_replace(str_split(self::BAD_SYMBOLS), '_', $item->name);
//                });
//

//
//
//                return $userData;

        } catch (UsernameRequiredException $e) {
            // The user didn't supply a username.
            return false;
        } catch (PasswordRequiredException $e) {
            // The user didn't supply a password.
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Полная синхронизация списка групп.
     *
     * @return mixed
     * 10 - синхронизация не требуется;
     * 11 - требуются права администратора;
     */
    public function syncGroups()
    {

        if (!$this->isAdmin())
            return false;

        $groups = Groups::getAllGroupList();
        if(empty($groups))
            return false;

        $groups = ArrayHelper::map($groups, 'id', function($item) {
            return str_replace(str_split(self::BAD_SYMBOLS), '_', $item['name']);;
        });

        $ldapGroups = array_map(function($item) {
            return preg_replace('/^iDapp\s*\-\s*/ui', '', $item->getName());
        }, array_filter($this->provider->search()->groups()->get()->all(), function($item) {
            return preg_match('/^iDapp\s*\-\s*/ui', $item->getName());
        }));

        $arr = array_diff($groups, $ldapGroups);
        if(empty($arr))
            return ['status' => 10];

        foreach ($arr as $groupName)
        {
           $this->addGroup($groupName);
        }


    }

    /**
     * Добавление группы.
     *
     * @param $name
     * @param $description
     * @return mixed
     */
    public function addGroup($name, $description = 'Описание')
    {

        if(!$this->test()['status'])
            return ['status' => 11];

        $this->isConnected();
        $group = $this->provider->make()->group();

        $dn = $group->getDnBuilder();
        $dn->addCn('iDapp - '.$name);
        $dn->addCn('Users');

        //$group->setDn('CN=Тестовая,OU=iDapp,OU=Группы,OU=ДВЮИ МВД РФ,DC=dvuimvd,DC=ru');
        $group->setDn($dn);
        $group->setDescription($description);
        if($group->save())
            return true;

        return false;
    }

    /**
     * Удаление группы.
     *
     * @param $name
     * @return mixed
     * @throws \Adldap\Models\ModelDoesNotExistException
     */
    public function deleteGroup($name)
    {
        if (!$this->isAdmin())
            return false;

        $name = str_replace(str_split(self::BAD_SYMBOLS), '_', $name);

        $group = $this->provider->search()->findByDn('cn=Автор-ВУЗ - ' . $name . ',CN=Users,' . $this->getValue('base_dn'));
        $group->delete();
    }

    /**
     * Тестирование модуля авторизации.
     */
    public function test()
    {
        if ($this->isConnected()) {
            if ($this->isAdmin()) {
                return [
                    'status' => 1,
                    'message' => 'Соединение установлено с правами администратора.',
                ];
            } else {
                return [
                    'status' => 1,
                    'message' => 'Соединение установлено без прав администратора. Автоматическая синхронизация групп будет недоступна.',
                ];
            }
        } else {
            return [
                'status' => 0,
                'message' => $this->error,
            ];
        }
    }

}