<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\typography\models\Orders */

$this->title = 'Update Typography Orders: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Typography Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="typography-orders-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
