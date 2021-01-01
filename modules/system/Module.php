<?php

namespace app\modules\system;

use Yii;
use app\modules\system\models\rbac\AccessControl;
use yii\web\ForbiddenHttpException;
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

    public $routes = [
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

    public function behaviors(): array{
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

    private function checkAccessModule(): bool{

        if(!AccessControl::checkAccess(Yii::$app->user->identity->id,$this->visible)){
            throw new ForbiddenHttpException('You are not allowed to perform this action.');
        }
        return true;
    }

    private function checkAccessCategory(): bool{
        $action = '/' . Yii::$app->controller->module->id . '/' . Yii::$app->controller->id;
        $user_id = Yii::$app->user->identity->id;

        foreach($this->routes as $route){

            if($route['route'] == $action) {

                if(!AccessControl::checkAccess($user_id, $route['access'])){
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');
                }
            }
        }
        return true;
    }

    public function beforeAction($action)
    {
        if(!(Yii::$app->user->isGuest)){

            $act = '/' . Yii::$app->controller->module->id . '/' . Yii::$app->controller->id;

            foreach($this->excludedRules as $eRule){
                if($eRule['route'] != $act){
                    //Проверка доступа к целому модулю;
                    $this->checkAccessModule();
                    //Провера доступа к подразделу модуля;
                    $this->checkAccessCategory();
                }
            }
        }
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
