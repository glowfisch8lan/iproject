<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\staff\models\Vacancies */

$this->title = 'Создать вакансию';
$this->params['breadcrumbs'][] = ['label' => Yii::$app->controller->module->name, 'url' => ['/' . Yii::$app->controller->module->id]];
$this->params['breadcrumbs'][] = ['label' => 'Вакансии', 'url' => ['/' . Yii::$app->controller->module->id . '/' . Yii::$app->controller->id]];
$this->params['breadcrumbs'][] = $this->title;

?>
<?= $this->render('_form', [
    'model' => $model
]) ?>


