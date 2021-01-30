<?php


namespace app\modules\system\helpers;


use Yii;
use yii\grid\GridView;
use yii\helpers\Html;


class Grid extends GridView
{


    /*
     *  ActionColumnHeader кнопка через определение $data['ActionColumnHeader']
     *  ActionColumnButtons кнопка через определение $data['ActionColumnButtons']
     */

    public static $gridData;

    /*
     * Параметры GridView
     */

    public static $gridParams = [
        'enableActionColumn' => true,
        'enableAction' => false
    ];

    protected static function config($key,$value){
        self::$gridParams[$key] = $value;
    }

    public static function collectColumns(){

        $data = self::$gridData;


        /*
         * ActionColumn
         */

        /*
         * 1. Формируем кнопки-действия
         */

        /* Вспомогательные функции */
        $headerCallback = function($url){
            return Html::a('<i class="fa fa-plus" aria-hidden="true"></i></i>', $url,
                ['class' => 'btn btn-outline-info']);
        };
        $urlCreate = '/'. Yii::$app->controller->module->id . '/' . Yii::$app->controller->id .  '/create';


        /* Скелет Buttons */
        $ActionColumnButtonsDefault = [
            'view' => function ($url,$model) {
                return Html::a('<i class="fa fa-eye"></i>', $url,
                    ['class' => 'btn btn-outline-info', 'data-method' => 'post']);
            },
            'update' => function ($url,$model) {
                return Html::a('<i class="fa fa-pencil" aria-hidden="true"></i>', $url,
                    ['class' => 'btn btn-outline-info',
                        'data' => [
                            'method' => 'post'
                        ]]);
            },

            'ajax' => function ($url,$model) {
                return Html::a('<i class="fa fa-pencil" aria-hidden="true"></i>', $url,
                    ['class' => 'btn btn-outline-info',]);
            },

            'delete' => function ($url, $model){
                return Html::a('<i class="fa fa-trash" aria-hidden="true"></i>', $url,
                    ['class' => 'btn btn-outline-danger',
                        'data' => [
                            'confirm' => 'Вы действительно хотите удалить данную позицию?',
                            'method' => 'post'
                        ]]);
            },
        ];

        /* Условия для основного скелета */

        /* Параметры Header */
        $headerActionColumn = empty($data['searchModel']) ? $headerCallback($urlCreate) : null; //Наличие или отсутствие searchModel;
        $headerActionColumn = !empty($data['ActionColumnHeader']) ? $data['ActionColumnHeader'] : $headerActionColumn; //Выбираем header: по-умолчанию или пользовательский;
        $ActionColumnHeaderWidth = !empty($data['buttonsOptions']['headerWidth']) ? $data['buttonsOptions']['headerWidth'] : 150; //Выбираем ширину колонки: по-умолчанию или пользовательский;
        /* END */

        $ActionColumnButtons = !empty($data['ActionColumnButtons']) ? $data['ActionColumnButtons'] : $ActionColumnButtonsDefault; //Выбираем конфигурацию кнопок-действий: по-умолчанию или пользовательские;
        $buttonsTemplate = !empty($data['buttonsOptions']['template']) ? $data['buttonsOptions']['template'] : '{update} {delete}'; //Выбираем какие кнопки отобразить: шаблон;


        /* Скелет Action Column Default */
        $ActionColumnDefault =   [
            'class' => 'app\modules\system\components\gridviewer\CustomActionColumns',
            'header' => $headerActionColumn,
            'template' => $buttonsTemplate,
            'headerOptions' => [
                'width' => $ActionColumnHeaderWidth,
                'style' => 'text-align:center'
            ],
            'filterOptions' => ['style' =>'text-align: center;'],
            'contentOptions'=> ['style' =>'text-align: center;'],
            'buttons' => $ActionColumnButtons
        ];


        /* Условия формирования скелета */
        $ActionColumn = (!empty($data['ActionColumn'])) ? $data['ActionColumn'] : $ActionColumnDefault; //Если $data['ActionColumn'] не пустой, то присвоить Action Column из данной переменной, иначе по-умолчанию.

        /*
         * 2. Параметры пагинации для Провайдера;
         */
        $pagerDefault =  [
            'forcePageParam' => false,
            'pageSizeParam' => false,
            'pageSize' => 10
        ];

        /* Условие выбора пагинации */
        $data['dataProvider']->pagination = !empty($data['pagination']) ? $data['pagination'] : $pagerDefault; //Выбираем пагинацию по-умолчанию, либо переопределенную пользователем;

        /*
         * 3. Сборка Columns
         */
        $columns = $data['columns'];


        $enableActionColumn = function() use(&$columns,$ActionColumn){
            $columns[] = $ActionColumn;
        };

        (self::$gridParams['enableActionColumn']) ? $enableActionColumn() : false;

        return $columns;
    }

    public static function initWidget($input = [], $params = []){

        self::$gridData = $input;
        $data = self::$gridData;

        $dataProvider = $data['dataProvider'];
        $searchModel = !empty($data['searchModel']) ? $data['searchModel'] : null;


        foreach($params as $key => $value){
            self::config($key, $value);
        }

        $columns = self::collectColumns();

        return GridView::widget([
           'dataProvider' => $dataProvider,
           'filterModel' => $searchModel,

           'tableOptions' => [
               'class' => 'table table-bordered table-hover table-responsive'
           ],
           'pager' => [
               'class' => '\yii\widgets\LinkPager',
               'options' => [
                   'class' => 'pagination justify-content-start'
                   ],
               'pageCssClass' => 'page-item ',
               'disabledPageCssClass' => 'disabled',
               'linkContainerOptions' => [
                   'class' => 'page-item'
               ],
               'disabledListItemSubTagOptions' => [
                   'class' => 'page-link'
               ],
               'linkOptions' => [
                   'class' => 'page-link'
               ]
           ],
           'rowOptions'=> [
               'class' => 'table-hover'
           ],
           'headerRowOptions' => [
               'class' => ''
           ],
           'columns' => $columns,
       ]);
        Pjax::end();
    }

}


