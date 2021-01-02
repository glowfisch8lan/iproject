<?php

use app\modules\system\helpers\GridHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\staff\models\WorkersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Сотрудники';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box-body">
    <div class="col-md-12">
        <?= GridHelper::initWidget([
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'columns' => [
                    'id',
                [
                    'attribute' => 'worker',
                    'value' => function($model){
                        return $model->lastname . ' ' .$model->firstname . ' ' . $model->middlename;
                    }
                ],
                'birthday',
            ],


        ]);?>



</div>
</div>
