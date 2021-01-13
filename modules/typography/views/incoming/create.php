<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\typography\models\Orders */

$this->title = 'Create Typography Orders';
$this->params['breadcrumbs'][] = ['label' => 'Typography Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="typography-orders-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
