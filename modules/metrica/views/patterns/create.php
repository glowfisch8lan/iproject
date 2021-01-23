<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\metrica\models\patterns\Patterns */

$this->title = 'Create Patterns';
$this->params['breadcrumbs'][] = ['label' => 'Patterns', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

