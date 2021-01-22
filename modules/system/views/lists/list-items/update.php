<?php
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['breadcrumbs'][] = ['label' => Yii::$app->controller->module->name, 'url' => ['/' . Yii::$app->controller->module->id . '/lists']];

$this->params['breadcrumbs'][] = array(
    'label'=> $model->parent->name,
    'url'=>Url::toRoute('/'. Yii::$app->controller->module->id . '/' . 'list-items?id=' . $model->parent->id)
);
$this->title = 'Добавление поля';
?>
<?= $this->render('_form', [
    'model' => $model
]) ?>