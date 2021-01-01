<?php


namespace app\modules\system\models\interfaces\modules;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class ModuleInterface
 *
 * @package app\models\interfaces
 *
 * Класс для разработки модулей.
 */
class Module extends \yii\base\Module
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


    public static function register(){

    }

    /**
     * Проверка регистрации модуля;
     *
     * @return bool
     */
    public static function checkRegister($name): bool{
        $file = Yii::getAlias('@app') . '/modules/' . $name . '/' . md5($name);
        if(file_exists($file)){
            return true;
        }
        else return false;
    }

    private static function filterActive($modules): array{
        $items = [];
        foreach ($modules as $module) {
            if (self::checkRegister($module)) {
                $items[] = $module;
            }
        }
        return $items;
    }


    /**
     * Получение списка всех модулей.
     *
     * @return array|false
     */
    public static function getListModules(): array{

        $list = array_map(function ($item) {
            return basename($item);
        }, glob(realpath(Yii::getAlias('@app') . '/modules/') . '/*'));

        $list = self::filterActive($list);
        return $list;
    }

    /**
     * Получение данныхвсех модулей.
     *
     * @return array;
     */
    public static function getAllModules(): array{

        $modules = [];
        $list = self::getListModules();
        foreach($list as $module){
            $modules[] = Yii::$app->getModule($module);
        }
        return $modules;
    }

    public static function getAllPermissions(): array{
        $list = self::getListModules();
        foreach($list as $module){
            $modules[$module] = ArrayHelper::map(
                Yii::$app->getModule($module)->routes,
                'route',
                'access');
        }
        return $modules;
    }
}