<?php


namespace app\modules\system\models\auth;

use Adldap\Adldap;
use Adldap\Auth\PasswordRequiredException;
use Adldap\Auth\UsernameRequiredException;
use Adldap\Connections\ProviderInterface;
use Adldap\Auth\Events\Failed;
use Adldap\Models\Attributes\DistinguishedName;

use DateInterval;
use DateTime;
use yii\helpers\ArrayHelper;

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
    /**
     * @var ProviderInterface
     */
    private $provider;
    private $connected;

    private $config = [
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
    ];
    /**
     * LDAP constructor.
     *
     * @param array $settings
     */
    public function __construct($settings = [])
    {
        // создание клиента LDAP
        $this->client = new Adldap();
        $this->client->addProvider($this->config);
    }

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
     * Полная синхронизация списка групп.
     *
     * @return mixed
     */
    public function syncGroups()
    {
        if (!$this->isAdmin())
            return false;

        $groups = ArrayHelper::map(Groups::find()->all(), 'id', function($item) {
            return str_replace(str_split(self::BAD_SYMBOLS), '_', $item->name);
        });

        $ldapGroups = array_map(function($item) {
            return preg_replace('/^Автор\-ВУЗ\s*\-\s*/ui', '', $item->getName());
        }, array_filter($this->provider->search()->groups()->get()->all(), function($item) {
            return preg_match('/^Автор\-ВУЗ\s*\-\s*/ui', $item->getName());
        }));

        // только добавляем, не удаляем имеющиеся группы
        foreach (array_diff($groups, $ldapGroups) as $groupName)
            $this->addGroup($groupName);
    }

    /**
     * Добавление группы.
     *
     * @param $name
     * @return mixed
     */
    public function addGroup()
    {
        var_dump($this->test());
        $this->isConnected();
        $group = $this->provider->make()->group();


        $dn = $group->getDnBuilder();
        $dn->addCn('iDapp - Администраторы');
        $dn->addCn('Users');
        var_dump($dn);

        $group->setDn('CN=Тестовая,OU=iDapp,OU=Группы,OU=ДВЮИ МВД РФ,DC=dvuimvd,DC=ru');

        //$group->setDn($dn);
        $group->setDescription('Группа Администраторов');
        var_dump($group->save());
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