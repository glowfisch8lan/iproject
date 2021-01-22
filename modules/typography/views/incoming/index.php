<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\modules\system\helpers\Grid;
use app\modules\system\helpers\ArrayHelper;
use app\modules\staff\models\Units;
use app\modules\typography\models\Orders;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\typography\models\OrdersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Печать по-требованию';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box-body">
    <div class="col-md-12">
        <? Pjax::begin([
                'timeout' => 10000
                ]); ?>
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
                [
                    //'attribute' => 'senderUnit.name',
                    'attribute' => 'senderUnit',
                    'value' => 'senderUnit.name',
                    'filter'=> ArrayHelper::map(Units::find()->asArray()->all(), 'id', 'name'),
                    'contentOptions' => [
                        'class' => 'text-center'
                    ],
                    'label' => 'Подразделение отправителя',
                    'headerOptions' => [
                        'width' => 350,
                        'class' => 'text-center'
                    ]
                ],

                [
                    'format' => 'raw',
                    'attribute' => 'status',
                    'headerOptions' => [
                        'class' => 'text-center'
                    ],
                    'contentOptions' => ['class' => 'text-center'],
                    'value' => function($model){
                        return ($model->status) ? '<span class="text-success"><i class="fa fa-check" aria-hidden="true"></i> <strong>Выполнено</strong></span>' : '<span class="text-warning"><i class="fa fa-thumb-tack" aria-hidden="true"></i> <strong>В обработке</strong></span>';
                    },
                    'filter' => ['В обработке', 'Выполнено'],
                ],
            ],
            'buttonsOptions' => [
                'template' => '{view}{delete}'
            ],
            'pagination' => [
                'forcePageParam' => false,
                'pageSizeParam' => false,
                'pageSize' => 10
            ]

        ]);?>
        <? Pjax::end(); ?>
    </div>
</div>