<?php

use yii\helpers\Html;
use app\modules\system\helpers\GridHelper;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Модули';
$this->params['breadcrumbs'][] = ['label' => Yii::$app->controller->module->name, 'url' => '/system/modules'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box-body">
    <div class="col-md-12">
        <?= GridHelper::initWidget([
            'dataProvider' => $dataProvider,
            'columns' => [
                ['class' => '\yii\grid\SerialColumn',
                    'headerOptions' => [
                        'width' => 50,
                        'class' => 'text-center'
                    ],
                    'contentOptions' => [
                        'class' => 'text-center'
                    ],
                ],
                [   'attribute' => 'id',
                    'headerOptions' => [
                        'width' => 50,
                        'class' => 'text-center'
                    ],
                    'contentOptions' => ['class' => 'text-center'],
                ],

                [
                    'attribute' => 'name',
                ],
                [
                    'attribute' => 'status',
                ],
            ],
            'ActionColumnButtons' => [
                'toggle' => function ($url,$model){
                    $icons = ($model['status']) ? function(){return ['fa fa-eye', 'btn btn-info','unregister'];} : function(){return ['fa fa-eye-slash', 'btn btn-outline-info','register'];} ;

                    return Html::a('<i class="'.$icons()[0].'"></i>', 'modules/'.$icons()[2].'?id=' . $model['id'],
                        ['class' => $icons()[1], 'data-method' => 'post']);
                },
            ],
            'ActionColumnHeader' => '&nbsp;',
            'buttonsOptions' => ['template' => '{toggle}'],


        ]);?>

    </div>
</div>