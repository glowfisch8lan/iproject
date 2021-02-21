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
    public $icon = 'fas fa-graduation-cap';
    public $visible = 'viewAv';
//    public $layout = '/main';
    public $sort = 1;
    public $routes = [
        [   'route' => '/av/plugins',
            'name' => 'Плагины',
            'access' => 'viewAvPlugins',
            'description' => 'Общий доступ к Плагинам',
            'visible' => true,
        ],
        [   'route' => '/av/plugins/load?module=student&id=academicPerformance&controller=academicPerformance',
            'name' => 'Успеваемость',
            'access' => 'accessAvStudentGradeSheet',
            'description' => 'Доступ к плагину Успеваемость',
            'visible' => false,
        ],
        [   'route' => '/av/plugins/load?module=student&id=journal&controller=journal',
            'name' => 'Электронный журнал',
            'access' => 'accessAvStudentJournal',
            'description' => 'Доступ к плагину Электронный журнал',
            'visible' => false,
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
                        'actions' => ['ajax'],
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
