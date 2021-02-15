<?php
use kartik\select2\Select2;
use app\modules\system\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = 'Сводная ведомость успеваемости';
$this->params['breadcrumbs'][] = ['label' => Yii::$app->controller->module->name, 'url' => '/av'];
$this->params['breadcrumbs'][] = ['label' => 'Плагины', 'url' => '/av/plugins'];
$this->params['breadcrumbs'][] = ['label' => 'Успеваемость', 'url' => '/av/plugins/load?module=student&id=academicPerformance&controller=academicPerformance'];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="box-body">
    <div class="col-md-12">

        <div class="card">
            <h5 class="card-header">Сводная ведомость</h5>
            <div class="card-body">
                <h5 class="card-title"></h5>
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
                        'language' => 'ru',
                        'options' => ['placeholder' => 'Выберите факультеты ...'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ])->label('');

                    ?>

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
