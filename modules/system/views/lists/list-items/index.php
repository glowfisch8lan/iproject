<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->params['breadcrumbs'][] = ['label' => Yii::$app->controller->module->name, 'url' => ['/' . Yii::$app->controller->module->id . '/lists']];

$this->params['breadcrumbs'][] = array(
    'label'=> $model->parent->name,
    'url'=>Url::toRoute('/'. Yii::$app->controller->module->id . '/' . 'list-items?id=' . $model->parent->id)
);


$this->title = 'Справочник';
?>

        <div class="box-body">
            <div class="col-md-12">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => [
                        [    'format' => 'raw',
                            'attribute' => 'id',
                            'headerOptions' => [
                                'width' => 50,
                                'class' => 'text-center'
                            ],
                            'contentOptions' => ['class' => 'text-center'],
                        ],

                        [
                            'format' => 'raw',
                            'attribute' => 'name'
                        ],
                        [
                            'format' => 'raw',
                            'attribute' => 'name_short',
                            'headerOptions' => [
                                'class' => 'text-center',
                                'width' => 200,
                            ],
                            'contentOptions' => ['class' => 'text-center'],
                        ],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{update} {delete}',
                            'headerOptions' => [
                                'width' => 150,
                            ],
                            'header' => '<div class="text-center"><a href="/'. $model->module->id . '/list-items/create" class="btn btn-outline-info btn-rounded" data-method="POST" data-params=\'{"parent_id":"'.$model->parent->id.'"}\'><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16"><path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/></svg></a></div>',

                            'contentOptions'=> ['style'=>'text-align: center;'],
                            'buttons' => [

                                'update' => function($url) use($model){
                                    return Html::a('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
  <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"/>
</svg>', $url,
                                        ['class' => 'btn btn-outline-info btn-rounded',
                                          'data' => [
                                              'method' => 'post',
                                              'params' => ['parent_id' => $model->parent->id
                                          ]]]);
                                },

                                'delete' => function($url) use($model){
                                    return Html::a('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
  <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z"/>
</svg>', $url,
                                        ['class' => 'btn btn-outline-danger btn-rounded',
                                            'data' => [
                                                'confirm' => 'Вы действительно хотите удалить данный паттерн?',
                                                'method' => 'post',
                                                'params' => ['parent_id' => $model->parent->id
                                          ]]]);
                                },
                            ],
                        ],
                    ],
                ]); ?>

            </div>
        </div>
