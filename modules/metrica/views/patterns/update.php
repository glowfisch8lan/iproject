<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\metrica\models\patterns\Patterns */

$this->title = 'Update Patterns: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Patterns', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="patterns-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
