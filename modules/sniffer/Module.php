<?php

namespace app\modules\sniffer;

/**
 * tools module definition class
 */
class Module extends \app\modules\system\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\sniffer\controllers';
    public $name = "Сниффер";
    public $defaultController = 'index';
    public $modelNamespace = 'app\modules\sniffer\models';
    public $link = 'sniffer';
    public $icon = 'fa fa-angle-double-right';
    public $visible = 'viewSniffer';

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
