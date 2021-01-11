<?php

namespace app\modules\feedback;

/**
 * feedback module definition class
 */
class Module extends \app\modules\system\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\feedback\controllers';
    public $name = "Обратная связь";
    public $defaultController = 'index';
    public $modelNamespace = 'app\modules\feedback\models';
    public $link = 'feedback';
    public $icon = 'fa fa-comments-o';
    public $visible = 'viewFeedback';
    public $description = "Модуль управления учетными записями и группами, контроля доступа и иным системным функционалом";

    public $routes = [
        [   'route' => '/feedback/incoming',
            'name' => 'Входящие',
            'access' => 'viewFeedback',
            'description' => 'Доступ к входящим заявкам',
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
                        'controllers'=>['feedback/default'],
                        'allow' => true,
                        'actions' => ['index', 'captcha'],
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
