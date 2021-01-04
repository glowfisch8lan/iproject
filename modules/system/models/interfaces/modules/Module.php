<?php


namespace app\modules\system\models\interfaces\modules;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

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


    public static function register($id): bool{

        $module = self::findModule($id);

        if(!$module){
            throw new NotFoundHttpException('Модуль не установлен! Проверьте конфигурацию.');
        }

        if(self::checkRegister($module->id)){
            return true;
        }
        $path = Yii::getAlias('@app') . '/modules/' . $module->id . '/';
        if(!file_put_contents($path . md5($module->id), '')){
            return false;
        };

    }

    public static function unregister($id): bool{

        $module = self::findModule($id);

        if(!$module){
            throw new NotFoundHttpException('Модуль не установлен! Проверьте конфигурацию.');
        }

        $file = Yii::getAlias('@app') . '/modules/' . $module->id . '/' . md5($module->id);
        if(file_exists(realpath($file))){
            return unlink($file);
        }
        return false;
    }
    public static function findModule($id){

        if(!(\Yii::$app->getModule($id))){
            return false;
        }
        return \Yii::$app->getModule($id);

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
    public static function getListModules($filter = true): array{

        $list = array_map(function ($item) {
            return basename($item);
        }, glob(realpath(Yii::getAlias('@app') . '/modules/') . '/*'));

        $list = ($filter) ? self::filterActive($list) : $list;
        return $list;
    }

    /**
     * Получение данныхвсех модулей.
     *
     * @return array;
     */
    public static function getAllModules($includeDisabledModules = false): array{

        $modules = [];
        $list = self::getListModules($filter = !($includeDisabledModules) ? true : false);
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

    /**
     * Создание списка модулей для вывода в GridView.
     *
     * @return array|false
     */
    public static function createArrayDataProvider(): array{

        $modules = self::getAllModules($includeDisabledModules = true);
        $data=[];
        foreach($modules as $module){
            $data[] = ['id' => $module->id, 'name' => $module->name, 'status' => self::checkRegister($module->id)];
        }
        return $data;
    }
}