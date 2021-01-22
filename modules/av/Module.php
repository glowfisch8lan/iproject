<?php

namespace app\modules\av;

/**
 * av module definition class
 */
class Module extends \yii\base\Module
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
    public $layout = '/main';
    public $routes = [
        [   'route' => '/av/student/reports',
            'name' => 'Студент - Отчеты',
            'access' => '',
            'description' => 'Отчеты модуля "Студент"',
            'visible' => true,
        ],
    ];
    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
