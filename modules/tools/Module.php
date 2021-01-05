<?php

namespace app\modules\tools;

/**
 * tools module definition class
 */
class Module extends \yii\base\Module
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
        [   'route' => '/tools/generator-login',
            'name' => 'Генератор логинов',
            'access' => 'viewToolsGeneratorLogin',
            'description' => 'Доступ к инструменту "Генератор Логинов',
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

        // custom initialization code goes here
    }
}
