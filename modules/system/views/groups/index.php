<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Группы';
$this->params['breadcrumbs'][] = ['label' => Yii::$app->controller->module->name, 'url' => '/system/settings'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box-body">
    <div class="col-md-12">

            <?= GridView::widget([
                'dataProvider'  => $dataProvider,
                'tableOptions'  => [
                    'class'     => 'table table-bordered table-hover',
                    'attribute' => 'dataTables'
                ],
                'columns' => [
                    /**
                     * Столбец нумерации. Отображает порядковый номер строки
                     */
                    [
                        'class' => \yii\grid\SerialColumn::class,
                        'header' => '',
                        'headerOptions' => [
                            'width' => 25,
                        ],
                    ],
                    /**
                     * Перечисленные ниже поля модели отображаются как колонки с данными без изменения
                     */
                    [
                        'format' => 'raw',
                        'attribute' => 'name',
                        'header' => 'Группа',
                        'value' => function($data){

                            return
                                Html::a($data->name, ['/system/groups/view', 'id' => $data->id], ['class' => 'link']);
                        }
                    ],
                    [
                        'attribute' => 'description',
                        'header' => 'Описание'

                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view} {update} {delete}',
                        'header' => '<div class="text-center"><a href="/'. Yii::$app->controller->module->id . '/'.Yii::$app->controller->id.'/create" class="btn btn-outline-info btn-rounded"}\'><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
  <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
</svg></a></div>',
                        'headerOptions' => [
                            'width' => 140,
                        ],
                        'contentOptions'=> ['style'=>'text-align: center;'],
                        'buttons' => [
                            'view' => function ($url,$model,$key) {
                                return Html::a('<i class="fas fa-eye"></i>', $url,
                                    ['class' => 'btn btn-outline-info btn-rounded', 'data-method' => 'post']);
                            },
                            'update' => function ($url,$model,$key) {
                                return Html::a('<i class="fas fa-pen"></i>', $url,
                                    ['class' => 'btn btn-outline-info btn-rounded', 'data-method' => 'post']);
                            },
                            'delete' => function ($url,$model,$key) {
                                if( ($model->name == 'Пользователи') xor ($model->name == 'Администраторы') ) {
                                   return false;
                                }
                                else{
                                    return Html::a('<i class="fas fa-trash"></i>', $url,
                                        ['class' => 'btn btn-outline-danger btn-rounded',
                                            'data' => [
                                                'confirm' => 'Вы действительно хотите удалить данную группу?',
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
</div>
