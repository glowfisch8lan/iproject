<?php

use yii\helpers\Html;
use app\modules\system\helpers\GridHelper;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = ['label' => Yii::$app->controller->module->name, 'url' => '/system/settings'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box-body">
    <div class="col-md-12">
        <?= GridHelper::initWidget([
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'columns' => [
                [   'attribute' => 'id',
                    'headerOptions' => [
                        'width' => 50,
                        'class' => 'text-center'
                    ],
                    'contentOptions' => ['class' => 'text-center'],
                ],

                [
                    'format' => 'raw',
                    'attribute' => 'login',
                    'value' => function($data){
                        return
                            Html::a($data['login'], ['/system/users/update', 'id' => $data['id']], ['class' => 'link']);
                    }
                ],
                [
                    'attribute' => 'name',
                ],
                [
                    'attribute' => 'name',
                ],
                ],
            'ActionColumnButtons' =>
                [
                'update' => function ($url,$model) {
                    return Html::a('<i class="fa fa-pencil" aria-hidden="true"></i>', $url,
                        ['class' => 'btn btn-outline-info',
                            'data' => [
                                'method' => 'post'
                            ]]);
                },

                'delete' =>
                    function($url, $model){
                        return ($model['login'] == 'admin' && $model['id'] == 1) ? null : Html::a('<i class="fa fa-trash" aria-hidden="true"></i>', $url,
                            ['class' => 'btn btn-outline-danger',
                                'data' => [
                                    'confirm' => 'Вы действительно хотите удалить данную позицию?',
                                    'method' => 'post'
                                ]]);
                            },
                    ]

        ]);?>

        </div>
    </div>