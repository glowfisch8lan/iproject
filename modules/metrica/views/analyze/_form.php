<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\metrica\models\patterns\Patterns;
use app\modules\system\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model app\modules\metrica\models\patterns\Patterns */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="d-flex justify-content-start">
    <div class="col-lg-10">
        <div class="card">
            <div class="card-header">Создание очереди на Парсинг</div>
            <div class="card-body">
                <!-- <h5 class="card-title">Введите новые учетные данные</h5>-->
                <?php $form = ActiveForm::begin(); ?>

                <div class="form-row">
                    <div class="form-group col-md-12">
                        <?= $form->field($model, 'url', [
                            'template' => '<div>{label} <a href="#" class="help"><i class="fa fa-question-circle" aria-hidden="true"></i></a></div><div>{input}</div><small>Введите наименование паттерна</small><div class="text-danger">{error}</div>'
                        ])->textInput(['autofocus' => false]); ?>
                    </div>

                    <?
                    $patterns = Patterns::find()->all();
                    $arr = ArrayHelper::map($patterns, 'id','name');

                    ?>

                    <div class="form-group col-md-12">
                        <?= $form->field($model, 'pattern_id', [
                            'template' => '<div>{label} <a href="#" class="help"><i class="fa fa-question-circle" aria-hidden="true"></i></a></div><div>{input}</div><div class="text-danger">{error}</div>'
                        ])->dropDownList($arr); ?>
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
