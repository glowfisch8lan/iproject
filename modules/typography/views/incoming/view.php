<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Json;
/* @var $this yii\web\View */
/* @var $model app\modules\typography\models\Orders */

$this->title = 'Заявка №' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Типография', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="box-body">
    <div class="col-md-12">
        <div class="col-lg-12 user-form">
            <div class="card">
                <div class="card-header"><i class="fa fa-user-circle" aria-hidden="true"></i> Заявка #<?=$model->id?></div>
                <div class="card-body">
                    <p>
                        <?echo ($model->status) ?  Html::a('<i class="fa fa-thumb-tack" aria-hidden="true"></i> В обработку', ['working', 'id' => $model->id], ['class' => 'btn btn-warning']) : Html::a('<i class="fa fa-check" aria-hidden="true"></i> Выполнено', ['complete', 'id' => $model->id], ['class' => 'btn btn-success']); ?>
                        <?= Html::a('<i class="fa fa-trash-o" aria-hidden="true"></i> Удалить', ['delete', 'id' => $model->id], [
                            'class' => 'btn btn-danger',
                            'data' => [
                                'confirm' => 'Are you sure you want to delete this item?',
                                'method' => 'post',
                            ],
                        ]) ?>
                    </p>

                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [

                            'sender',
                            'sender_unit_id',
                            'receiver',
                            'status',
                            'receiver_unit_id',
                            'comment',
                            [
                                'label'  => 'Файл',
                                'value'  => function ($data) {
                                    $file = Json::decode($data->file_uuid);
                                    $filename = $file[1] . '.' . base64_decode($file[2]);
                                    return Html::a($file[1].'.'.base64_decode($file[2]).'</i>',
                                        ['get-file', 'uuid' => $file[0],'file'=> $filename],
                                        [
//                                            'class' => '',
//                                            'data' => [
//                                                'method' => 'post',
//                                            ],
                                        ]);
                                },
                                'format' => 'html',
                            ],
                        ],
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</div>