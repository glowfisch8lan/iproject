<?php
/* @var $this yii\web\View */

use app\modules\system\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use app\modules\av\modules\student\models\StudentsApi;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\base\DynamicModel;
use \app\modules\av\modules\student\models\plugins\AcademicPerformance;
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Электронный журнал';
$this->params['breadcrumbs'][] = ['label' => Yii::$app->controller->module->name, 'url' => '/av'];
$this->params['breadcrumbs'][] = ['label' => 'Плагины', 'url' => '/av/plugins'];
$this->params['breadcrumbs'][] = $this->title;


// регистрируем небольшой js-код в view-шаблоне
$script = <<< JS
$(document).ready(function(){
    
    $('.select_send_ajax').on('change', function() {
      alert(1);
    });
    });
JS;
// значение $position может быть View::POS_READY (значение по умолчанию),
// или View::POS_LOAD, View::POS_HEAD, View::POS_BEGIN, View::POS_END
$position = $this::POS_END;
$this->registerJs($script, $position);
?>
<!--$('.select_send_ajax').on('change', function() {-->
<!--$(this.form).submit();-->
<!--});-->
<div class="box-body">
    <div class="col-12">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <td>
                        <?php
                        $ajax = ($ajax) ? 'ajax' : 'load';
                        $groups = array_filter(ArrayHelper::map(StudentsApi::getGroups(), 'id', 'name'), function($data){
                            if((preg_match('/(ПД|Ю).*.2\d*$/', $data, $matches)))
                                return $data;
                        });

                        $form = ActiveForm::begin([
                            'action' => "/av/plugins/".$ajax."?module=student&id=journal&controller=journal"
                        ]);
                        echo $form->field($model, 'group')->widget(Select2::classname(), [
                            'data' => $groups,
                            'language' => 'ru',
                            'value' => 'red',
                            'options' => ['placeholder' => 'Выберите группу ...'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ])->label('');
                        echo Html::submitButton('Запросить', ['class' => 'btn btn-primary']);
                        ActiveForm::end(); ?>
                    </td>
                    <td>
                        Дисциплина: Информатика
                    </td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Список
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                <button class="dropdown-item" type="button">ТГП</button>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>ФИО</td>
                    <?
                    $startDate = ''
                    ?>
                </tr>
                </thead>
                <tbody>
                <?
                if($model->group){
                    $students = StudentsApi::getStudentsByGroup($model->group);
                    foreach($students as $student){
                        echo '<tr><td>'.AcademicPerformance::getShortName((object)$student).'</td></tr>';
                    }
                }
                else{echo 'Список пуст';}

                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

