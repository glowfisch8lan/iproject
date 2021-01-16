<?php

/* @var $this yii\web\View */
/* @var $searchModel app\modules\staff\models\StateSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use app\modules\system\helpers\Grid;
use app\modules\system\helpers\ArrayHelper;
use app\modules\staff\models\Units;

$this->title = 'Входящие';
$this->params['breadcrumbs'][] = ['label' => Yii::$app->controller->module->name, 'url' => '/system/feedback'];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="box-body">
    <div class="col-md-12">
        <?= Grid::initWidget([
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'columns' => [
                [
                    'attribute' => 'id',
                    'headerOptions' => [
                            'width' => 50,
                            'class' => 'text-center'
                        ],
                    'contentOptions' => [
                        'class' => 'text-center'
                    ],
                    'filter' => ''
                ],
                'sender',
                'subject',
                [
                    'attribute' => 'unit',
                    'value' => 'unit.name_short',
                    'filter'=> ArrayHelper::map(Units::find()->asArray()->all(), 'name_short', 'name'),
                    'label' => 'Подразделение-получатель',
                    'contentOptions' => [
                        'class' => 'text-center'
                    ],
                    'headerOptions' => [
                        'width' => 350,
                        'class' => 'text-center'
                    ],
            ],
                [
                    'format' => 'raw',
                    'attribute' => 'status',
                    'headerOptions' => [
                        'class' => 'text-center'
                    ],
                    'contentOptions' => ['class' => 'text-center'],
                    'value' => function($model){
                        return ($model->status) ? '<span class="text-success"><strong>Выполнено</strong></span>' : '<span class="text-danger"><strong>Активна</strong></span>';
                        },
                ],
            ],
            'buttonsOptions' => [
                    'template' => '{view}{delete}'
                    ],
            'pagination' => [
                'forcePageParam' => false,
                'pageSizeParam' => false,
                'pageSize' => 5
            ]

        ]);?>
</div>
</div>
