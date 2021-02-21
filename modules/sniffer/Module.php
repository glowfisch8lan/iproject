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
    public $icon = 'fab fa-hackerrank';
    public $visible = 'viewSniffer';
    public $layout = '/main';
    public $sort = 8;

    public $routes = [
        [   'route' => '/sniffer',
            'name' => 'Сниффер-ссылка',
            'access' => 'viewSnifferLink',
            'description' => 'Доступ к снифферу-ссылке [Видимость в меню]',
            'visible' => true,
        ],
        [   'route' => '/sniffer/journal',
            'name' => 'Журнал',
            'access' => 'viewSnifferJournal',
            'description' => 'Доступ к журналу',
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
                        'controllers'=>['sniffer/default'],
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => ['?'],
                    ],
                    [
                        'controllers'=>['sniffer/journal'],
                        'allow' => true,
                        'actions' => ['index'],
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
