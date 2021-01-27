<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use app\modules\system\helpers\Grid;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\typography\models\OrdersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Отчеты | Модуль "Студент" | Автор-ВУЗ';
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
                    'id',
                    'module',
                    'name',
                    ],
            'buttonsOptions' => [
                'template' => '{view}'
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