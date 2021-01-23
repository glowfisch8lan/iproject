<?php

use yii\helpers\Html;
use app\modules\system\helpers\Grid;
use yii\helpers\ArrayHelper;
use app\modules\staff\models\Units;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\staff\models\StateSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Штат';
$this->title = 'Модули';
$this->params['breadcrumbs'][] = ['label' => Yii::$app->controller->module->name, 'url' => '/staff/state'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box-body">
    <div class="col-md-12">
        <?= Grid::initWidget([
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'columns' => [ ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'workers',
                'value' => function($model){
                    return $model->workers->lastname . ' ' .$model->workers->firstname . ' ' . $model->workers->middlename;
                }
            ],
            [
                'attribute' => 'vacancies.position',
                'value' => 'vacancies.position.name'
            ],
            [
                'attribute' => 'vacancies.unit',
                'value' => 'vacancies.unit.name'
            ]],


        ]);?>
    </div>
</div>
