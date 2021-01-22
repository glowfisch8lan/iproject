<?php

namespace app\modules\inventory;

/**
 * inventory module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\inventory\controllers';
    public $name = "Инвентаризация";
    public $defaultController = 'index';
    public $modelNamespace = 'app\modules\inventory\models';
    public $link = 'av';
    public $icon = 'fa fa-angle-double-right';
    public $visible = 'viewInventory';
    public $layout = '/main';
    public $routes = [
        [   'route' => '/inventory/student/reports',
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
    }
}
