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

    public static function collectColumns(){

        $data = self::$gridData;

        $headerCallback = function($url){
            return Html::a('<i class="fa fa-plus" aria-hidden="true"></i></i>', $url,
                ['class' => 'btn btn-outline-info']);
        };
        $urlCreate = '/'. Yii::$app->controller->module->id . '/' . Yii::$app->controller->id .  '/create';

        $headerActionColumn = empty($data['searchModel']) ? $headerCallback($urlCreate) : null;
        $headerActionColumn = !empty($data['ActionColumnHeader']) ? $data['ActionColumnHeader'] : $headerActionColumn;

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

            'delete' => function ($url, $model){
                return Html::a('<i class="fa fa-trash" aria-hidden="true"></i>', $url,
                    ['class' => 'btn btn-outline-danger',
                        'data' => [
                            'confirm' => 'Вы действительно хотите удалить данную позицию?',
                            'method' => 'post'
                        ]]);
            },
        ];
        $ActionColumnButtons = !empty($data['ActionColumnButtons']) ? $data['ActionColumnButtons'] : $ActionColumnButtonsDefault;
        $buttonsTemplate = !empty($data['buttonsOptions']['template']) ? $data['buttonsOptions']['template'] : '{update} {delete}';
        $ActionColumnHeaderWidth = !empty($data['buttonsOptions']['headerWidth']) ? $data['buttonsOptions']['headerWidth'] : 150;

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

        $pagerDefault =  [
            'forcePageParam' => false,
            'pageSizeParam' => false,
            'pageSize' => 10
        ];


        $ActionColumn = !empty($data['ActionColumn']) ? $data['ActionColumn'] : $ActionColumnDefault;
        $data['dataProvider']->pagination = !empty($data['pagination']) ? $data['pagination'] : $pagerDefault;

        $columns = $data['columns'];
        $columns[] = $ActionColumn;

        return $columns;
    }

    public static function initWidget($input = []){

        self::$gridData = $input;
        $data = self::$gridData;

        $dataProvider = $data['dataProvider'];
        $searchModel = !empty($data['searchModel']) ? $data['searchModel'] : null;

        $columns = self::collectColumns();

        return GridView::widget([
           'dataProvider' => $dataProvider,
           'filterModel' => $searchModel,

           'tableOptions' => [
               'class' => 'table table-bordered table-hover'
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

    }

}


