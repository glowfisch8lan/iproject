<?php

use app\modules\system\helpers\Grid;
use yii\widgets\Pjax;


$this->title = '';
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
                    'id',
                    'url',
                'value',
                [
                    'attribute' => 'pattern',
                    'value' => 'pattern.name'
                ],
                'status',

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