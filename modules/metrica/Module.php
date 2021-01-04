<?php

namespace app\modules\metrica;
use Yii;
use yii\base\BootstrapInterface;

class Module extends \yii\base\Module implements BootstrapInterface
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\metrica\controllers';
    public $name = "Метрика";
    public $defaultController = 'index';
    public $modelNamespace = 'app\modules\metrica\models';
    public $link = 'metrica';
    public $icon = 'fa fa-bar-chart';
    public $visible = 'viewMetrica';

    public $routes = [
        [   'route' => '/metrica/state',
            'name' => 'Анализ данных',
            'access' => 'viewMetrica',
            'description' => 'Доступ к подразделу Анализ данных',
            'visible' => true,
        ],
    ];
    public function behaviors(){
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function init()
    {

        parent::init();

    }

    public function bootstrap($app)
    {
        $app->getUrlManager()->addRules([
            [
                'class' => 'yii\web\UrlRule',
                'pattern' => 'metrica',
                'route' => '/tools/index'
            ]
        ], false);

    }
}
