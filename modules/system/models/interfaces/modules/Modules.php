<?php


namespace app\modules\system\models\interfaces\modules;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\httpclient\Exception;
use yii\web\NotFoundHttpException;
use app\modules\system\models\users\Groups;
use yii\web\ServerErrorHttpException;

/**
 * Class ModuleInterface
 *
 * @package app\models\interfaces
 *
 * Класс для разработки модулей.
 */
class Modules extends \yii\base\Module
{
    /**
     * @var string Название модуля.
     */
    public $name;
    /**
     * @var string иконка модуля (для вывода в меню)
     */
    public $iconClass;
    /**
     * @var string Описание модуля.
     */
    public $description;
    /**
     * @var int Индекс сортировки (для меню).
     */
    public $sort = 10000;
    /**
     * @var string ID контроллера по умолчанию.
     */
    public $defaultController;

    /**
     * @var array Пункты меню модуля.
     */
    protected $menuItems = [];


    public static function findModule($id)
    {

        if (!(Yii::$app->getModule($id))) {
            return false;
        }
        return Yii::$app->getModule($id);

    }

    /**
     * Проверка регистрации модуля;
     *
     * @return bool
     */
    public static function checkRegister($name): bool
    {
        $file = Yii::getAlias('@app') . '/modules/' . $name . '/' . md5($name);
        if (file_exists($file)) {
            return true;
        }
        return false;
    }

    /**
     * Регистрация модуля;
     * Автоматически добавляет все разрешения модуля к группе Администраторов;
     *
     * @return bool
     */
    public static function register($id): bool
    {

        $module = self::findModule($id);
        $permissions = Json::Decode(Groups::getPermissions(Yii::$app->user->id)['permissions']);

        if(!in_array($module->visible, $permissions))
            $permissions[] = $module->visible;


        foreach($module->routes as $route)
        {
            if(!in_array($route['access'], $permissions))
                $permissions[] = $route['access'];

        }

        $group = Groups::findOne(1);

        $group->permissions =  Json::encode($permissions);
        if(!$group->save())
            throw new ServerErrorHttpException('При регистриации модуля возникли проблемы: ошибка обновления прав Администратора.');


        //Groups::removeAllGroupMember(1); //Удаляем все группы пользователя;
        //Groups::addMembers(ArrayHelper::indexMap($, 1)); //Добавляем список групп в system_users_groups заново;

        if (!$module)
            throw new NotFoundHttpException('Модуль не установлен! Проверьте конфигурацию.');


        if (self::checkRegister($module->id))
            return true;


        $path = Yii::getAlias('@app') . '/modules/' . $module->id . '/';
        $result = file_put_contents($path . md5($module->id), md5($module->id));

        if (!$result)
            return false;

        return true;
    }


    /**
     * Отмена регитсрации модуля в системе;
     *
     * @return bool
     */
    public static function unregister($id): bool
    {

        $module = self::findModule($id);

        if (!$module) {
            throw new NotFoundHttpException('Модуль не установлен! Проверьте конфигурацию.');
        }

        $file = Yii::getAlias('@app') . '/modules/' . $module->id . '/' . md5($module->id);
        if (file_exists(realpath($file))) {
            return unlink($file);
        }
        return false;
    }


    public static function getAllPermissions(): array
    {
        $list = self::getListModules();
        foreach ($list as $module) {
            $modules[$module] = ArrayHelper::map(
                Yii::$app->getModule($module)->routes,
                'route',
                'access');
        }
        return $modules;
    }

    /**
     * Получение списка всех модулей.
     *
     * @return array|false
     */
    public static function getListModules($filter = true): array
    {

        $list = array_map(function ($item) {
            return basename($item);
        }, glob(realpath(Yii::getAlias('@app') . '/modules/') . '/*'));

        $list = ($filter) ? self::filterActive($list) : $list;
        return $list;
    }

    private static function filterActive($modules): array
    {
        $items = [];
        foreach ($modules as $module) {
            if (self::checkRegister($module)) {
                $items[] = $module;
            }
        }
        return $items;
    }

    /**
     * Создание списка модулей для вывода в GridView.
     *
     * @return array|false
     */
    public static function createArrayDataProvider(): array
    {
        $modules = self::getAllModules($includeDisabledModules = true);
        $data = [];

        foreach ($modules as $module) {
            $description = (empty($module->description)) ? 'Описание отсутствует' : $module->description;

            $data[] = ['id' => $module->id, 'name' => $module->name, 'status' => self::checkRegister($module->id), 'description' => $description];
        }
        return $data;
    }

    /**
     * Получение данных всех модулей.
     *
     * @return array;
     */
    public static function getAllModules($includeDisabledModules = false): array
    {
        $modules = [];
        $list = self::getListModules($filter = !($includeDisabledModules) ? true : false);
        foreach ($list as $module) {
            $modules[] = Yii::$app->getModule($module);
        }
        // uasort – сортирует массив, используя пользовательскую функцию
        usort($modules,function($first,$second){ return $first->sort > $second->sort; });

        return $modules;
    }
}