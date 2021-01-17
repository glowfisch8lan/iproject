<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\modules\system\helpers\Grid;
use app\modules\system\helpers\ArrayHelper;
use app\modules\staff\models\Units;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\typography\models\OrdersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Заявки печати по-требованию';
$this->params['breadcrumbs'][] = $this->title;
?>
'sender',
'sender_unit_id',
'receiver',
'receiver_unit_id',
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
                [
                    'attribute' => 'senderUnit.name',
                    'filter'=> ArrayHelper::map(Units::find()->asArray()->all(), 'name_short', 'name'),
                    'contentOptions' => [
                        'class' => 'text-center'
                    ],
                    'label' => 'Подразделение отправителя',
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
                        return ($model->status) ? '<span class="text-success"><strong>Выполнено</strong></span>' : '<span class="text-warning"><strong>В обработке</strong></span>';
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