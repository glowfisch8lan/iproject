<?php


namespace app\modules\system\helpers;


use Yii;
use yii\grid\GridView;
use yii\helpers\Html;

class GridHelper extends GridView
{

    protected function renderFilterCellContent()
    {
        return Html::button('Reset', ['class' => 'btn btn-primary']);
    }

    public static function initWidget($data = []){
        $dataProvider = $data['dataProvider'];

        $headerCallback = function($url){
            return Html::a('<i class="fa fa-plus" aria-hidden="true"></i></i>', $url,
                ['class' => 'btn btn-outline-info']);
        };
        $urlCreate = '/'. Yii::$app->controller->module->id . '/' . Yii::$app->controller->id .  '/create';
        $headerActionColumn = empty($data['searchModel']) ? $headerCallback($urlCreate) : null;

        $ActionColumnDefault =   [
           'class' => 'app\modules\system\components\gridviewer\CustomActionColumns',
           'header' => $headerActionColumn,
           'template' => '{update} {delete}',
           'headerOptions' => [
               'width' => 150,
               'style' => 'text-align:center'
           ],
           'filterOptions' => ['style' =>'text-align: center;'],
           'contentOptions'=> ['style' =>'text-align: center;'],
           'buttons' => [

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
           ],
       ];
        $pagerDefault =  [
           'forcePageParam' => false,
           'pageSizeParam' => false,
           'pageSize' => 10
       ];

       $searchModel = !empty($data['searchModel']) ? $data['searchModel'] : null;
       $ActionColumn = !empty($data['ActionColumn']) ? $data['ActionColumn'] : $ActionColumnDefault;
       $dataProvider->pagination = !empty($data['pagination']) ? $data['pagination'] : $pagerDefault;

       $columns = $data['columns'];
       $columns[] = $ActionColumn;


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


