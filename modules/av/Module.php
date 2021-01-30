<?php

namespace app\modules\av;

/**
 * av module definition class
 */
class Module extends \app\modules\system\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\av\controllers';
    public $name = "Автор-ВУЗ";
    public $defaultController = 'index';
    public $modelNamespace = 'app\modules\av\models';
    public $link = 'av';
    public $icon = 'fa fa-angle-double-right';
    public $visible = 'viewAvtorVuz';
//    public $layout = '/main';
    public $routes = [
        [   'route' => '/av/reports',
            'name' => 'Отчеты',
            'access' => 'viewAvtorVuz',
            'description' => 'Отчеты',
            'visible' => true,
        ],
        [   'route' => '/av/plugins',
            'name' => 'Плагины',
            'access' => 'viewAvtorVuz',
            'description' => 'Плагины',
            'visible' => true,
        ],
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
                        'controllers'=>['av/plugins'],
                        'allow' => true,
                        'actions' => ['ajax', 'lti'],
                        'roles' => ['?'],
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

        // custom initialization code goes here
    }
}
