<?php

namespace app\modules\typography;

/**
 * feedback module definition class
 */
class Module extends \app\modules\system\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\typography\controllers';
    public $name = "Типография";
    public $defaultController = 'index';
    public $modelNamespace = 'app\modules\typography\models';
    public $link = 'typography';
    public $icon = 'fa fa-book';
    public $visible = 'viewTypography';
    public $description = "Модуль обработки задач типографии";

    public $routes = [
        [   'route' => '/typography/incoming',
            'name' => 'Печать по-требованию',
            'access' => 'viewTypographyPrintOnDemand',
            'description' => 'Доступ к входящим заявкам на печать',
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