<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\typography\models\Orders */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="typography-orders-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'sender')->textInput() ?>

    <?= $form->field($model, 'sender_unit_id')->textInput() ?>

    <?= $form->field($model, 'receiver')->textInput() ?>

    <?= $form->field($model, 'receiver_unit_id')->textInput() ?>

    <?= $form->field($model, 'comment')->textInput() ?>

    <?= $form->field($model, 'file_uuid')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
