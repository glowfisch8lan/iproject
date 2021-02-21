<?php
/**
 * TODO:
 * 1. Валидация url;
 * 2. Связь с контроллером очередей;
 *
 *
 *
 */

namespace app\modules\metrica;

class Module extends \app\modules\system\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\metrica\controllers';
    public $name = "Метрика";
    public $defaultController = 'index';
    public $modelNamespace = 'app\modules\metrica\models';
    public $link = 'metrica';
    public $icon = 'fas fa-arrow-right';
    public $visible = 'viewMetrica';
    public $description = 'Модуль поиска и анализа метрической информации';
    public $sort = 3;

    public $routes = [
        [   'route' => '/metrica/analyze',
            'name' => 'Анализ данных',
            'access' => 'viewMetricaAnalyze',
            'description' => 'Доступ к подразделу Анализ данных',
            'visible' => true,
        ],
        [   'route' => '/metrica/patterns',
            'name' => 'Паттерны',
            'access' => 'viewMetricaPatterns',
            'description' => 'Доступ к подразделу Паттерны',
            'visible' => true,
        ],
//        [   'route' => '/metrica/settings',
//            'name' => 'Настройки',
//            'access' => 'viewMetricaAnalyze',
//            'description' => 'Доступ к подразделу Анализ данных',
//            'visible' => true,
//        ],
    ];
    public $SettingsMenuItems = [
            [
            'id' => 'general',
            'name' => 'Основные',
            'isActive' => 'active'
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
    }

    public function bootstrap($app)
    {
        $app->getUrlManager()->addRules([
            [
                'class' => 'yii\web\UrlRule',
                'pattern' => 'metrica',
                'route' => '/tools/index'
            ]
        ], false);

    }
}
