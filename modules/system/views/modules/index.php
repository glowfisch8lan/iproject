<?php

use yii\helpers\Html;
use app\modules\system\helpers\Grid;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Модули';
$this->params['breadcrumbs'][] = ['label' => Yii::$app->controller->module->name, 'url' => '/system/modules'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box-body">
    <div class="col-md-12">
        <?= Grid::initWidget([
            'dataProvider' => $dataProvider,
            'columns' => [
//                ['class' => '\yii\grid\SerialColumn',
//                    'headerOptions' => [
//                        'width' => 50,
//                        'class' => 'text-center'
//                    ],
//                    'contentOptions' => [
//                        'class' => 'text-center'
//                    ],
//                ],
                [   'attribute' => 'id',
                    'label' => 'Идентификатор',
                    'headerOptions' => [
                        'width' => 50,
                        'class' => 'text-center'
                    ],
                    'contentOptions' => ['class' => 'text-center'],
                ],

                [
                    'attribute' => 'name',
                    'label' => 'Название модуля',
                ],
                [
                    'attribute' => 'description',
                    'label' => 'Описание модуля',
                ],
                [
                    'attribute' => 'status',
                    'label' => 'Статус модуля',
                    'value' => function ($model){return ($model['status']) ? 'Зарегистрирован' : 'Незарегистрирован';},
                ],
            ],
            'ActionColumnButtons' => [
                'toggle' => function ($url,$model){

                    $icons = (($model['status'])) ? function(){return ['fa fa-eye', 'btn btn-info','unregister'];} : function(){return ['fa fa-eye-slash', 'btn btn-outline-info','register'];} ;
                    $classButton = ($model['id'] != 'system') ? $icons()[1] : 'btn btn-dark disabled';
                    return Html::a('<i class="'.$icons()[0].'"></i>', '/system/modules/'.$icons()[2].'?id=' . $model['id'],
                        ['class' => $classButton, 'data-method' => 'post']);
                },
            ],
            'ActionColumnHeader' => '&nbsp;',
            'buttonsOptions' => ['template' => '{toggle}'],


        ]);?>

    </div>
</div>