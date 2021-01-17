<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\typography\models\Orders */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Typography Orders', 'url' => ['index']];
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
                        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
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
                            'receiver_unit_id',
                            'comment',
                            'file_uuid:ntext',
                        ],
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</div>