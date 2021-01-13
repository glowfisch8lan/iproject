<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\modules\system\helpers\GridHelper;
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
        <?= GridHelper::initWidget([
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
                    'attribute' => 'sender_unit_id',
                    'filter'=> ArrayHelper::map(Units::find()->asArray()->all(), 'name_short', 'name'),
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

<div class="typography-orders-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Typography Orders', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'sender',
            'sender_unit_id',
            'receiver',
            'receiver_unit_id',
            //'comment',
            //'file_uuid:ntext',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
