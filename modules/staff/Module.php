<?php

namespace app\modules\staff;

/**
 * staff module definition class
 */
class Module extends \app\modules\system\Module
{

    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\staff\controllers';
    public $name = "Кадровый учет";
    public $defaultController = 'index';
    public $modelNamespace = 'app\modules\staff\models';
    public $link = 'staff';
    public $icon = 'fa fa-id-card';
    public $visible = 'viewStaff';
    public $description = 'Модуль учета кадров';
    public $sort = 2;
    public $routes = [
        [   'route' => '/staff/state',
            'name' => 'Штат',
            'access' => 'viewState',
            'description' => 'Доступ к подразделу Штаты',
            'visible' => true,
        ],
        [   'route' => '/staff/workers',
            'name' => 'Сотрудники',
            'access' => 'viewWorkers',
            'description' => 'Доступ к подразделу Сотрудники',
            'visible' => true,
        ],
        [   'route' => '/staff/vacancies',
            'name' => 'Вакансии',
            'access' => 'viewVacancies',
            'description' => 'Доступ к подразделу Вакансии',
            'visible' => true,
        ],
        [   'route' => '/staff/lists',
            'name' => 'Справочники',
            'access' => 'viewLists',
            'description' => 'Доступ к подразделу Справочники',
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
