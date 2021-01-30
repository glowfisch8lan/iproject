<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use app\modules\system\helpers\Grid;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\typography\models\OrdersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Плагины';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box-body">
    <div class="col-md-12">
        <? Pjax::begin([
            'timeout' => 5000
        ]); ?>
        <?= Grid::initWidget([
            'dataProvider' => $dataProvider,
            'columns' =>
                [
                    [
                        'class' => 'yii\grid\SerialColumn',

                    ],
                    'module.name' => [
                        'attribute' => 'module.name',
                        'label' => 'Модуль',
                    ],
                    'name' => [
                        'attribute' => 'name',
                        'format' => 'raw',
                        'label' => 'Название',
                        'value' => function($data){
                            return
                                Html::a($data['name'], ['/av/plugins/load',
                                    'module' => $data['module']['id'],
                                    'id' => $data['id'],
                                    'category' => $data['category'],
                                    'controller' => $data['controller']
                                ],
                                    ['class' => 'link']);
                        }
                    ]
                ],
            'buttonsOptions' => [
                'template' => '{view} {ajax}'
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