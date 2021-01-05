<?php

namespace app\modules\metrica;
use Yii;

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
    public $icon = 'fa fa-bar-chart';
    public $visible = 'viewMetrica';
    public $description = 'Модуль поиска и анализа метрической информации';

    public $routes = [
        [   'route' => '/metrica/analyze',
            'name' => 'Анализ данных',
            'access' => 'viewMetricaAnalyze',
            'description' => 'Доступ к подразделу Анализ данных',
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
