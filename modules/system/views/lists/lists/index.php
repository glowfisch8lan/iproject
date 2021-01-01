<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['breadcrumbs'][] = ['label' => Yii::$app->controller->module->name, 'url' => ['/' . Yii::$app->controller->module->id . '/lists']];
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
                            'attribute' => 'name',
                            'value' => function($data){
                                return
                                    Html::a($data->name, [ '/' . $data->module->id . '/list-items', 'id' => $data->id], ['class' => 'link']);
                            }
                        ],

                        [
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{update} {delete}',
                            'headerOptions' => [
                                'width' => 150,
                            ],
                            'header' => '<div class="text-center"><a href="/'. $model->module->id . '/lists/create" class="btn btn-outline-info disabled btn-rounded" ><i class="fas fa-plus"></i></a></div>',
                            'contentOptions'=> ['style'=>'text-align: center;'],
                            'buttons' => [
                                'update' => function ($url,$model,$key) {
                                    return Html::a('<i class="fas fa-pen"></i>', 0,
                                        ['class' => 'btn disabled btn-outline-info btn-rounded', 'data-method' => 'post']);
                                },
                                'delete' => function ($url,$model,$key) {
                                    return Html::a('<i class="fas fa-trash"></i>', 0,
                                        ['class' => 'btn disabled btn-outline-danger btn-rounded',
                                            'data' => [
                                                'confirm' => 'Вы действительно хотите удалить данный паттерн?',
                                                'method' => 'post',
                                            ]]);
                                }
                            ],
                        ],
                    ],
                ]); ?>
            </div>
        </div>
