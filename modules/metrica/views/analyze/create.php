<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\metrica\models\analyze\Analyze */

$this->title = 'Создать';
$this->params['breadcrumbs'][] = ['label' => 'Analyzes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
