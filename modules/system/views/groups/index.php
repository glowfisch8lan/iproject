<?php

use yii\helpers\Html;
use app\modules\system\helpers\Grid;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Группы';
$this->params['breadcrumbs'][] = ['label' => Yii::$app->controller->module->name, 'url' => '/system/settings'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box-body">
    <div class="col-md-12">
        <?= Grid::initWidget([
            'dataProvider' => $dataProvider,
//            'searchModel' => $searchModel,
            'columns' => [

                    [
                        'class' => \yii\grid\SerialColumn::class,
                        'header' => '',
                        'headerOptions' => [
                            'width' => 25,
                            ],
                    ],

                    [
                         'format' => 'raw',
                        'attribute' => 'name',
                        'label' => 'Группа',
                        'value' => function($model){
                            return Html::a($model->name, ['/system/groups/view', 'id' => $model->id], ['class' => 'link']);
                            }
                    ],

                    [
                        'attribute' => 'description',
                        'label' => 'Описание'
                    ],

            ],

             'buttonsOptions' => ['template' => '{view} {update} {delete}', 'headerWidth' => 200],


        ]);?>

        </div>
    </div>
</div>
