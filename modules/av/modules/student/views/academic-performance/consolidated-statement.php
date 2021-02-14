<?php
use kartik\select2\Select2;
use app\modules\system\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>
<div class="box-body">
    <div class="col-md-12">

        <div class="card">
            <h5 class="card-header">Сводная ведомость</h5>
            <div class="card-body">
                <h5 class="card-title">Сформировать сводную ведомость успеваемости</h5>
                <p class="card-text">

                    <?php


//                    $ajax = ($ajax) ? 'ajax' : 'load';

                    $faculty = ArrayHelper::map($model->getGroupList(), 'id', 'name');

                    ?>
                    <? $form = ActiveForm::begin([
                        'action' => "/av/plugins/load?module=student&id=AcademicPerformance&controller=AcademicPerformance&action=GetConsolidatedStatement"
                    ]);

                    echo $form->field($model, 'faculty')->widget(Select2::classname(), [
                        'data' => $faculty,
                        'options' => ['placeholder' => 'Выберите факультеты ...', 'multiple' => false],
                        'pluginOptions' => [
                            'tags' => false,
                            'tokenSeparators' => [',', ' '],
                            'maximumInputLength' => 10
                        ],
                    ])->label('');

                    ?>

<!--                <div class="col-2">-->
<!---->
<!--                    <div class="custom-control custom-switch">-->
<!--                        <input type="checkbox" class="custom-control-input" id="switch1" name="AcademicPerformance[session]">-->
<!--                        <label class="custom-control-label" for="switch1">Результаты сессий</label>-->
<!--                    </div>-->
<!---->
<!--                    <div class="custom-control custom-switch">-->
<!--                        <input type="checkbox" class="custom-control-input " id="switch2" name="AcademicPerformance[isSkip]">-->
<!--                        <label class="custom-control-label" for="switch2">Пропуски</label>-->
<!--                    </div>-->
<!---->
<!--                </div>-->

<hr>
                <div class="d-flex flex-row justify-content-end"">
                <div class="form-group justify-content-end" style="border:1px solid black">
                    <?= Html::submitButton('Запросить', ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>

            </p>
            <hr>

        </div>
    </div>

</div>
