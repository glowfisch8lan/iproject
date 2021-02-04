<?php

use yii\widgets\ActiveForm;
use app\modules\av\modules\student\models\StudentsApi;
use app\modules\system\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Успеваемость';
$this->params['breadcrumbs'][] = ['label' => Yii::$app->controller->module->name, 'url' => '/'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box-body">
    <div class="col-md-12">

        <div class="card">
            <h5 class="card-header">Текущая успеваемость</h5>
            <div class="card-body">
                <h5 class="card-title">Получить сведения о текущей успеваемости</h5>
                <p class="card-text">

                    <?php

                    $ajax = ($ajax) ? 'ajax' : 'load';
                    $groups = array_filter(ArrayHelper::map(StudentsApi::getGroups(), 'id', 'name'), function($data){
                        if((preg_match('/(ПД|Ю).*.2\d*$/', $data, $matches)))
                            return $data;
                    });
                    $form = ActiveForm::begin([
                'action' => "/av/plugins/".$ajax."?module=student&id=AcademicPerformance&controller=AcademicPerformance&action=GetGradeSheet"
        ]);

                    echo $form->field($model, 'group')->widget(Select2::classname(), [
                        'data' => $groups,
                        'language' => 'ru',
                        'options' => ['placeholder' => 'Выберите группу ...'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],

                    ]);
                    ?>
                    <div class="col-2">
                     <?= $form->field($model, 'startDate')->input('text', ['class' => 'datepicker-here form-control', 'data-language' => 'ru', 'value' => date('01.01.Y')])->label('Начало:')?>
                    </div>

                    <div class="col-2">
                        <?= $form->field($model, 'endDate')->input('text', ['class' => 'datepicker-here form-control', 'data-language' => 'ru', 'value' => date('d.m.Y')])->label('Конец:')?>
                    </div>
                <div class="d-flex flex-row justify-content-end"">
                <div class="form-group justify-content-end" style="border:1px solid black">
                    <?= Html::submitButton('Запросить', ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
            </p>
        </div>
    </div>
</div>

