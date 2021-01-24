<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\metrica\models\patterns\Patterns */

$this->title = 'Создание';
$this->params['breadcrumbs'][] = ['label' => Yii::$app->controller->module->name, 'url' => '/metrica'];
$this->params['breadcrumbs'][] = ['label' => 'Паттерны', 'url' => '/metrica/patterns'];
$this->params['breadcrumbs'][] = $this->title;
?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

