<?php

namespace app\modules\system;

use Yii;
use app\modules\system\models\rbac\AccessControl;
use yii\web\ForbiddenHttpException;
use app\modules\system\models\interfaces\modules\Modules;
/**
 * user module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\system\controllers';
    public $name = "Система";
    public $defaultController = 'index';
    public $modelNamespace = 'app\modules\system\models';
    public $link = 'system';
    public $icon = 'fa fa-cog';
    public $visible = 'viewSystem';
    public $description = "Описание отсутствует";
    public $layout = '@app/modules/system/views/layouts/main';


    public $routes = [
        [   'route' => '/system/modules',
            'name' => 'Модули',
            'access' => 'readSystemModules',
            'description' => 'Доступ к подразделу Модули',
            'visible' => true

        ],
        [   'route' => '/system/updates',
            'name' => 'Обновление',
            'access' => 'viewSystemUpdates',
            'description' => 'Доступ к системе Обновлений',
            'visible' => true

        ],
        [   'route' => '/system/users',
            'name' => 'Пользователи',
            'access' => 'viewUsers',
            'description' => 'Доступ к подразделу Пользователи',
            'visible' => true
        ],
        [   'route' => '/system/groups',
            'name' => 'Группы',
            'access' => 'viewGroups',
            'description' => 'Доступ к подразделу Группы',
            'visible' => true
        ],
    ];

    private $excludedRules = [
        ['route' => '/system/default', 'name' => 'Главная страница', 'module' => 'system'] //альтернатива /my;
    ];

    public function behaviors() {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['login'],
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }


    /*
     * Либо true, либо исключение;
     */
    private function verifyAccess(){

        $act = '/' . Yii::$app->controller->module->id . '/' . Yii::$app->controller->id;

        foreach($this->excludedRules as $eRule){
            if($eRule['route'] != $act){

                //Проверка доступа к целому модулю;
                if(!AccessControl::checkAccess(Yii::$app->user->identity->id,$this->visible)){
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');
                }

                //Провера доступа к подразделу модуля;
                $action = '/' . Yii::$app->controller->module->id . '/' . Yii::$app->controller->id;
                $user_id = Yii::$app->user->identity->id;

                foreach($this->routes as $route){

                    if($route['route'] == $action) {
                        if(!AccessControl::checkAccess($user_id, $route['access'])){
                            throw new ForbiddenHttpException('You are not allowed to perform this action.');
                        }
                    }
                }
            }
        }
        return false;
    }

    public function beforeAction($action)
    {

        /*
         *  Проверка регистрации модуля в системе: в случае отсутствия регистрации - выбросить исключение;
         */
        if(!Modules::checkRegister(Yii::$app->controller->module->id))
        {
            throw new ForbiddenHttpException('Модуль не зарегистрирован! Пожалуйста, зарегистрируйте модуль в системе.');
        }
        /*
         * Если пользователь неГость - проверка прав доступа к модулю | категории | действию;
         */
        (!(Yii::$app->user->isGuest)) ? $this->verifyAccess() : false;

        return parent::beforeAction($action);
    }

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

    }
}
