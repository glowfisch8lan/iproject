<?php

use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model app\modules\staff\models\Vacancies */

$this->title = 'Добавить значение в справочник';
$this->params['breadcrumbs'][] = ['label' => $model->parent->name, 'url' => '/staff/list-items?id='. $model->parent->id];
$this->params['breadcrumbs'][] = $this->title;

?>
<?= $this->render('_form', [
    'model' => $model
]) ?>