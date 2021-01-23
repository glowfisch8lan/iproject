<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\metrica\models\patterns\Patterns */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="d-flex justify-content-start">
    <div class="col-lg-10">
    <div class="card">
        <div class="card-header">Создание паттерна</div>
        <div class="card-body">
            <!-- <h5 class="card-title">Введите новые учетные данные</h5>-->
            <?php $form = ActiveForm::begin(); ?>

            <div class="form-row">
                <div class="form-group col-md-12">
                    <?= $form->field($model, 'name', [
                        'template' => '<div>{label} <a href="#" class="help"><i class="fa fa-question-circle" aria-hidden="true"></i></a></div><div>{input}</div><small>Введите наименование паттерна</small><div class="text-danger">{error}</div>'
                    ])->textInput(['autofocus' => false]); ?>
                </div>

                <div class="form-group col-md-12">
                    <?= $form->field($model, 'pattern', [
                        'template' => '<div>{label} <a href="#" class="help"><i class="fa fa-question-circle" aria-hidden="true"></i></a></div><div>{input}</div><div class="text-danger">{error}</div>'
                    ])->textInput(['autofocus' => false]); ?>
                </div>

            </div>

            <div class="form-group">
                <?= Html::submitButton('<i class="fa fa-save"></i> Создать', ['class' => 'btn btn-success']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
    </div>
    </div>
