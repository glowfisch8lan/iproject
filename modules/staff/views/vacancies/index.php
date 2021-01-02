<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\modules\system\helpers\GridHelper;
use app\modules\staff\models\Positions;
use app\modules\staff\models\Units;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\staff\models\VacanciesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Вакансии';
$this->params['breadcrumbs'][] = ['label' => Yii::$app->controller->module->name, 'url' => ['/' . Yii::$app->controller->module->id]];

$this->params['breadcrumbs'][] = $this->title;
?>

<div class="box-body">
    <div class="col-md-12">
    <?=GridHelper::initWidget([
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'columns' => [
                [
                    'attribute' => 'id',
                    'contentOptions' => [
                        'class' => 'text-center',
                        'width' => '25'
                    ],

                ],
                [
                    'attribute' => 'position',
                    'value' => 'position.name',
                    'filter'=> \app\modules\system\helpers\ArrayHelper::map(Positions::find()->asArray()->all(), 'name', 'name'),
                ],
                [
                    'attribute' => 'unit',
                    'value' => 'unit.name',
                    'filter'=> ArrayHelper::map(Units::find()->asArray()->all(), 'name', 'name'),
                ],
            ]
    ]);?>

</div>
</div>

