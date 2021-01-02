<?php


namespace app\modules\system\helpers;


use Yii;
use yii\grid\GridView;
use yii\helpers\Html;

class GridHelper extends GridView
{
   public static function initWidget($data = []){
       $dataProvider = $data['dataProvider'];
       $searchModel = $data['searchModel'];
       $buttons =   [
           'class' => 'yii\grid\ActionColumn',
           'template' => '{update} {delete}',
           'headerOptions' => [
               'width' => 150,
           ],
           'header' =>  '<div class="text-center"><a href="/'. Yii::$app->controller->module->id . '/' . Yii::$app->controller->id .  '/create" class="btn btn-outline-info btn-rounded"}\'><i class="fa fa-plus" aria-hidden="true"></i></a></div>',

           'contentOptions'=> ['style'=>'text-align: center;'],
           'buttons' => [

               'update' => function ($url,$model) {
                   return Html::a('<i class="fas fas-pencil" aria-hidden="true"></i>', $url,
                       ['class' => 'btn btn-outline-info btn-rounded',
                           'data' => [
                               'method' => 'post'
                           ]]);
               },

               'delete' => function ($url, $model){
                   return Html::a('<i class="fa fa-trash-o" aria-hidden="true"></i>', $url,
                       ['class' => 'btn btn-outline-danger btn-rounded',
                           'data' => [
                               'confirm' => 'Вы действительно хотите удалить данный паттерн?',
                               'method' => 'post'
                           ]]);
               },
           ],
       ];
       $columns = $data['columns'];
       $columns[] = $buttons;

       return GridView::widget([
           'dataProvider' => $dataProvider,
           'filterModel' => $searchModel,
           'tableOptions' => [
               'class' => 'table table-bordered table-hover'
           ],
           'pager' => [
               'class' => '\yii\widgets\LinkPager',
               'pageCssClass' => 'page-item',
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


