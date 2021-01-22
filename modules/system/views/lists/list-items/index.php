<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\modules\system\helpers\Grid;


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
                <?
                $headerCallback = function($url) use($model){
                    return Html::a('<i class="fa fa-plus" aria-hidden="true"></i></i>', $url,
                        ['class' => 'btn btn-outline-info', 'data' => ['method' => 'post', 'params' => ['parent_id' => $model->parent->id]]]);
                };
                $urlCreate = '/'. Yii::$app->controller->module->id . '/' . Yii::$app->controller->id .  '/create';
                echo Grid::initWidget([
                    'dataProvider' => $dataProvider,
                    'columns' => [[
                        'format' => 'raw',
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
                    ]],
                    'ActionColumnHeader' => $headerCallback($urlCreate),
                    'ActionColumnButtons' => [
                        'update' => function ($url) use($model){
                                return Html::a('<i class="fa fa-pencil" aria-hidden="true"></i>', $url,
                                    ['class' => 'btn btn-outline-info',
                                        'data' => [
                                            'method' => 'post',
                                            'params' => ['parent_id' => $model->parent->id]
                                        ],

                                    ]);
                            },
                        'delete' => function ($url) use($model){
                                return Html::a('<i class="fa fa-trash" aria-hidden="true"></i>', $url,
                                    ['class' => 'btn btn-outline-danger',
                                        'data' => [
                                            'confirm' => 'Вы действительно хотите удалить данную позицию?',
                                            'method' => 'post',
                                            'params' => ['parent_id' => $model->parent->id]
                                        ]]);
                            },
                    ]

                ]);?>


            </div>
        </div>
