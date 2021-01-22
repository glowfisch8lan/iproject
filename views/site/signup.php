<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;


$form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'username')->textInput(['autofocus' => true]);?>
        <?= $form->field($model, 'password')->passwordInput(); ?>

    <div class="form-group">
            <?= Html::submitButton('Регистрация', ['class' => 'btn btn-success']) ?>
    </div>

<?php ActiveForm::end(); ?>