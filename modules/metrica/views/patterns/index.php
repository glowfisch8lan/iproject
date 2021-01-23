<?php

use yii\widgets\Pjax;
use app\modules\system\helpers\ArrayHelper;
use app\modules\system\helpers\Grid;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\metrica\models\patterns\PatternSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Паттерны';
$this->params['breadcrumbs'][] = ['label' => Yii::$app->controller->module->name, 'url' => '/metrica'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box-body">
    <div class="col-md-12">
        <? Pjax::begin([
            'timeout' => 5000
        ]); ?>
        <?= Grid::initWidget([
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'columns' => [
             [
                     'attribute' => 'id',
                     'filter' => '',
                 'contentOptions' => [
                         'width' => 30
                         ],
             ],
            'name:ntext',
            'pattern:ntext',

            ],
            'buttonsOptions' => [
                'template' => '{update} {delete}'
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
