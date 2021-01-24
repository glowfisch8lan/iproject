<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\metrica\models\analyze\Analyze */

$this->title = 'Create Analyze';
$this->params['breadcrumbs'][] = ['label' => 'Analyzes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="analyze-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
