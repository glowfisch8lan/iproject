<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\feedback\models\Messages */

$this->title = 'Заявка #'.$model->id;
$this->params['breadcrumbs'][] = ['label' => 'Входящие', 'url' => ['/feedback/incoming']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="box-body pt-3 pl-5">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <h5 class="card-header"><?= Html::encode($this->title) ?></h5>
                <div class="card-body">
                    <h5 class="card-title"></h5>
                    <p class="card-text">
                        <?= Html::a('Выполнено', ['complete', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
                        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
                            'class' => 'btn btn-danger',
                            'data' => [
                                'confirm' => 'Are you sure you want to delete this item?',
                                'method' => 'post',
                            ],
                        ]) ?>

                        <?= DetailView::widget([
                            'model' => $model,
                            'attributes' => [
                            'sender',
                            'unit_id',
                            'subject',
                            'text']
                        ]) ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>