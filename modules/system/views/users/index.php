<?php

use yii\helpers\Html;
use yii\grid\GridView;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = ['label' => Yii::$app->controller->module->name, 'url' => '/system/settings'];
$this->params['breadcrumbs'][] = $this->title;
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
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{update} {delete}',
                        'header' => '<div class="text-center"><a href="/'. Yii::$app->controller->module->id . '/'.Yii::$app->controller->id.'/create" class="btn btn-outline-info btn-rounded"}\'><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
  <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
</svg></a></div>',
                        'headerOptions' => [
                            'width' => 150,
                        ],
                        'contentOptions'=> ['style'=>'text-align: center;'],
                        'buttons' => [
                            'update' => function ($url,$model) {
                                return Html::a('<i class="fas fa-pen"></i>', '/system/users/update?id=' . $model['id'],
                                    ['class' => 'btn btn-outline-info btn-rounded', 'data-method' => 'post']);
                            },
                            'delete' => function ($url,$model) {

                                if( $model['login'] == 'admin') {
                                    return false;
                                }
                                else {

                                    return Html::a('<i class="fas fa-trash"></i>', '/system/users/delete?id=' . $model['id'],
                                        ['class' => 'btn btn-outline-danger btn-rounded',
                                            'data' => [
                                                'confirm' => 'Вы действительно хотите удалить данного пользователя?',
                                                'method' => 'post',
                                            ]]);
                                }
                            },
                        ],
                    ],



                    ],


            ]); ?>
        </div>
    </div>