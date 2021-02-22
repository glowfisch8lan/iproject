<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="col-lg-12 user-form">
    <div class="card">
        <div class="card-header"></div>
        <div class="card-body">
            <!-- <h5 class="card-title">Введите новые учетные данные</h5>-->
            <?php $form = ActiveForm::begin(); ?>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <?= $form->field($model, 'name', [
                        'template' => '<div>{label}</div><div>{input}</div><div class="text-danger">{error}</div>'
                    ])->textInput(['autofocus' => true, 'maxlength' => true]); ?>
                    <?= $form->field($model, 'name_short', [
                        'template' => '<div>{label}</div><div>{input}</div><div class="text-danger">{error}</div>'
                    ])->textInput(['autofocus' => true, 'maxlength' => true]); ?>
                    <?= $form->field($model, 'parent_id')->hiddenInput(['value'=> $model->parent_id])->label(false); ?>

                </div>
            </div>
            <div class="form-group">
                <?= Html::submitButton('<i class="fas fa-save"></i> Сохранить', ['class' => 'btn btn-success']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
