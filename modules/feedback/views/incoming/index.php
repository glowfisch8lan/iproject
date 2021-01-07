<?php

/* @var $this yii\web\View */
/* @var $searchModel app\modules\staff\models\StateSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use app\modules\system\helpers\GridHelper;
use app\modules\system\helpers\ArrayHelper;
use app\modules\staff\models\Units;

$this->title = 'Входящие';
$this->params['breadcrumbs'][] = ['label' => Yii::$app->controller->module->name, 'url' => '/system/feedback'];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="box-body">
    <div class="col-md-12">
        <?= GridHelper::initWidget([
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'columns' => [
                'id',
                'sender',
                'subject',
                'text',
                [
                    'attribute' => 'unit',
                    'value' => 'unit.name_short',
                    'filter'=> ArrayHelper::map(Units::find()->asArray()->all(), 'name_short', 'name'),
                    'label' => 'Подразделение-получатель'
            ]

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
