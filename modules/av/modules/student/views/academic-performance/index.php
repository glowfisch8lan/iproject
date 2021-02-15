<?php

use yii\widgets\ActiveForm;
use app\modules\av\modules\student\models\StudentsApi;
use app\modules\system\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\select2\Select2;
use app\modules\system\models\rbac\AccessControl;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Успеваемость';
$this->params['breadcrumbs'][] = ['label' => Yii::$app->controller->module->name, 'url' => '/av'];
$this->params['breadcrumbs'][] = ['label' => 'Плагины', 'url' => '/av/plugins'];
$this->params['breadcrumbs'][] = $this->title;

?>

        <div class="box-body">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="general-tab" data-toggle="tab" href="#general" role="tab" aria-controls="general" aria-selected="true">Основная</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="attributions-tab" data-toggle="tab" href="#reports101" role="tab" aria-controls="reports101" aria-selected="false">Отчеты</a>
                </li>
            </ul>

            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade active show" id="general" role="tabpanel" aria-labelledby="general-tab">
                    <div class="col-md-12">
                        <div class="card">
                            <h5 class="card-header">Текущая успеваемость</h5>
                            <div class="card-body">
                                <h5 class="card-title">Получить сведения о текущей успеваемости</h5>
                                <p class="card-text">
                                    <?
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

                                <div class="col-2">
                                    <div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" id="switch1" name="AcademicPerformance[session]"><label class="custom-control-label" for="switch1">Результаты сессий</label></div>
                                    <div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input " id="switch2" name="AcademicPerformance[isSkip]"><label class="custom-control-label" for="switch2">Пропуски</label></div>
                                </div>

                                <div class="d-flex flex-row justify-content-end">
                                    <div class="form-group justify-content-end" style="border:1px solid #5e0800"><?= Html::submitButton('Запросить', ['class' => 'btn btn-primary']) ?></div>
                                </div>
                                <?php ActiveForm::end(); ?>
                                </p>


                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="reports101" role="tabpanel" aria-labelledby="reports101-tab">
                    <div class="col-md-12">
                        <hr>
                        <?
                        $reports = [
                            [
                                'name' => 'Сводная успеваемость по факультетам',
                                'link' => '/av/plugins/load?module=student&id=AcademicPerformance&controller=AcademicPerformance&action=ConsolidatedStatement'

                            ]
                        ];
                        $html = null;
                        foreach($reports as $key => $value)
                        {
                            $html .= Html::a($value['name'], $value['link'], [
                                'class' => 'generate-report'
                            ]);
                        }

                        ?>

                            <div class="card card-body">
                                <div>
                                    <?=$html;?>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>









