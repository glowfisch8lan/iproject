<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\modules\system\helpers\GridHelper;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->params['breadcrumbs'][] = ['label' => Yii::$app->controller->module->name, 'url' => ['/' . Yii::$app->controller->module->id . '/lists']];

$this->params['breadcrumbs'][] = array(
    'label'=> $model->parent->name,
    'url'=>Url::toRoute('/'. Yii::$app->controller->module->id . '/' . 'list-items?id=' . $model->parent->id)
);


$this->title = 'Справочник';
?>

        <div class="box-body">
            <div class="col-md-12">
                <?= GridHelper::initWidget([
                    'dataProvider' => $dataProvider,
                    'columns' => [[
                        'format' => 'raw',
                        'attribute' => 'id',
                        'headerOptions' => [
                            'width' => 50,
                            'class' => 'text-center'
                        ],
                        'contentOptions' => ['class' => 'text-center'],
                    ],

                    [
                        'format' => 'raw',
                        'attribute' => 'name'
                    ],
                    [
                        'format' => 'raw',
                        'attribute' => 'name_short',
                        'headerOptions' => [
                            'class' => 'text-center',
                            'width' => 200,
                        ],
                        'contentOptions' => ['class' => 'text-center'],
                    ]],


                ]);?>


            </div>
        </div>
