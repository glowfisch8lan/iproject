<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\web\View;
use yii\grid\GridView;

?>
<div class="container-fluid">
    <div class="page-title">
        <h3><?= Html::encode($this->title) ?>
            Результаты
        </h3>
    </div>
    <div class="box box-primary">
        <a href="/metrica/analyze">Назад</a>
        <div class="box-body">

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    [
                        'class' => 'yii\grid\SerialColumn'

                    ],

                           [
                                'attribute' => 'url',
                                'header' => 'URL',
                                'headerOptions' => [
                                    'width' => 50,
                                ],
                            ],
                    [
                        'attribute' => 'metrika_name',
                        'header' => 'Метрика'
                    ],
                    [
                        'attribute' => 'mid',
                        'header' => 'ID',
                        'headerOptions' => [
                            'width' => 100,
                        ],
                    ],
                    
                ],


            ]); ?>

        </div>
    </div>
