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
    public $link = 'metrica';
    public $icon = 'fa fa-wrench';
    public $visible = 'viewTools';

    public $routes = [
        [   'route' => '/tools/state',
            'name' => 'Штат',
            'access' => 'viewTools',
            'description' => 'Доступ к подразделу Штаты',
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
