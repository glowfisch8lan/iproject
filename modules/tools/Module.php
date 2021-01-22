<?php

namespace app\modules\tools;

/**
 * tools module definition class
 */
class Module extends \app\modules\system\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\tools\controllers';
    public $name = "Инструменты";
    public $defaultController = 'index';
    public $modelNamespace = 'app\modules\tools\models';
    public $link = 'tools';
    public $icon = 'fa fa-wrench';
    public $visible = 'viewTools';

    public $routes = [
        [   'route' => '/tools/converter',
            'name' => 'Конвертер',
            'access' => 'viewToolsGeneratorLogin',
            'description' => 'Доступ к инструменту "Конвертер',
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
                    [
                        'controllers'=>['tools/test'],
                        'allow' => true,
                        'actions' => ['*'],
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
