<?php

use yii\helpers\Html;
use app\modules\system\helpers\Grid;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['breadcrumbs'][] = ['label' => Yii::$app->controller->module->name, 'url' => ['/' . Yii::$app->controller->module->id . '/lists']];
$this->title = 'Справочник';
?>

        <div class="box-body">
            <div class="col-md-12">
                <?=Grid::initWidget([
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
                    ],
                    'ActionColumn' =>
                        [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{update} {delete}',
                        'headerOptions' => [
                            'width' => 150,
                        ],
                        'header' => '<div class="text-center"><a href="/'. $model->module->id . '/lists/create" class="btn btn-outline-info disabled" ><i class="fa fa-plus"></i></a></div>',
                        'contentOptions'=> ['style'=>'text-align: center;'],
                        'buttons' => [
                            'update' => function ($url,$model,$key) {
                                return Html::a('<i class="fa fa-pencil"></i>', 0,
                                    ['class' => 'btn disabled btn-outline-info', 'data-method' => 'post']);
                            },
                            'delete' => function ($url,$model,$key) {
                                return Html::a('<i class="fa fa-trash"></i>', 0,
                                    ['class' => 'btn disabled btn-outline-danger',
                                        'data' => [
                                            'confirm' => 'Вы действительно хотите удалить данный паттерн?',
                                            'method' => 'post',
                                        ]]);
                            }
                        ],
                    ],
                ]);?>
            </div>
        </div>
