<?php
/* @var $this yii\web\View */

use app\modules\system\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use app\modules\av\modules\student\models\StudentsApi;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\base\DynamicModel;
use app\modules\av\modules\student\models\plugins\Journal;
use app\modules\av\modules\student\models\plugins\AcademicPerformance;
use DateTime;
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Электронный журнал';
$this->params['breadcrumbs'][] = ['label' => Yii::$app
    ->controller
    ->module->name, 'url' => '/av'];
$this->params['breadcrumbs'][] = ['label' => 'Плагины', 'url' => '/av/plugins'];
$this->params['breadcrumbs'][] = $this->title;

// регистрируем небольшой js-код в view-шаблоне
$script = <<< JS
$(document).ready(function(){
    $('.collapse').on('hidden.bs.collapse', function () {
       // $(this).closest('tr').hide();
    
    });
    
     $('.collapse').on('show.bs.collapse', function () {
        $(this).closest('tr').show();
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

    <div class="table-responsive" >
        <table class="table table-bordered table-hover table-sm ">
            <thead>
            <tr>
                <td>
                    <?php
                    $ajax = ($ajax) ? 'ajax' : 'load';
                    $groups = array_filter(ArrayHelper::map(StudentsApi::getGroups() , 'id', 'name') , function ($data)
                    {
                        if ((preg_match('/(ПД|Ю).*.2\d*$/', $data, $matches))) return $data;
                    });
                    $disciplines = ['1444' => 'ТСП'];

                    $form = ActiveForm::begin(['action' => "/av/plugins/" . $ajax . "?module=student&id=journal&controller=journal"]);
                    echo $form->field($model, 'group')->widget(Select2::classname() , ['data' => $groups, 'language' => 'ru', 'value' => 'red', 'options' => ['placeholder' => 'Выберите группу ...'], 'pluginOptions' => ['allowClear' => true], ])->label('');

                    echo $form->field($model, 'discipline')->widget(Select2::classname() , ['data' => $disciplines, 'language' => 'ru', 'value' => 'red', 'options' => ['placeholder' => 'Выберите дисциплину ...'], 'pluginOptions' => ['allowClear' => true], ])->label('') . Html::submitButton('Запросить', ['class' => 'btn btn-primary']);

                    ActiveForm::end(); ?>
                </td>
            </tr>
            <tr>
                <td>ФИО</td>
                <?
                $days = ["вск", "пнд", "вт", "ср", "чт", "пт", "сб",];
                $startDate = (string)'20.01.2021';

                $date = new DateTime($startDate);
                $format = 'd.m';

                $arrDate = null;
                $accordion = null;

                for ($i = 0;;$i++)
                {

                    if ($i > 5){break;}

                    $arrDate[] = $date->format($format);
                    $day_week = date("w", $date->getTimestamp());

                    echo '<td class=\'text-center\'><button class="btn btn-outline-primary" aria-pressed="false" autocomplete="off" role="button" aria-pressed="true" type="button" data-toggle="collapse" data-target="#accordion_'.$i.'">'. $date->format($format) . '&nbsp;(' . $days[$day_week] . ')</button></td>';

                    $date->modify('+1 days');
                    $accordion[] = $i;
                }
                ?>
            </tr>
            </thead>
            <tbody>
            <tr style="display:none"><td colspan="100">
                    <?
                    //var_dump(StudentsApi::getJournalLesson());
                    foreach($accordion as $i){

                        echo '<div id="accordion_'.$i.'" class="collapse">Тема № 17 - Практическое занятие с пистолетом</div>';
                    }?>
                </td>
            </tr>
            </tbody>
            <tbody>
            <?
            var_dump($model->discipline);
            //1444 - Огневая; group - 24
            if ($model->group)
            {
                $students = StudentsApi::getStudentsByGroup($model->group);
                $marks = StudentsApi::getMarksByGroup($model->group);
                $marks = $model->filterMarks($marks, [$startDate, $date->format('d.m.Y') ]);
                $marks = $model->filterDiscipline($marks, $model->discipline);
                $index = 0;

                foreach ($students as $student)
                {

                    $index++;
//                        if($index == 10)
//                        {

                    $student_marks = ArrayHelper::ArrayValueFilter($marks, 'student_id', $student['id']);

                    echo '<tr>';
                    echo '<td><a href="https://av.dvuimvd.ru/student/students/'.$model->group['id'].'?student_id='.$student['id'].'" target=\'_blank\'>' . AcademicPerformance::getShortName((object)$student) . '</a></td>'; // Выводим имя;


                    uasort($student_marks, function ($a, $b)
                    {
                        return ($a['lesson_date'] > $b['lesson_date']);
                    });

                    foreach($arrDate as $key1 => $value1)
                    {

                        $h = $value1.'.'.date('Y');

                        $r = new DateTime($h);
                        $result = ArrayHelper::recursiveArraySearch($r->format('Y-m-d'), $student_marks);


                        if($result)
                        {
                            $html = null;
                            foreach($result as $key2 => $value2)
                            {
                                $html .= '<span class=\'p-1\'><a href="https://av.dvuimvd.ru/student/journal/view?group_id='.$model->group.'&lesson_id='.$student_marks[$value2]['journal_lesson_id'].'" target=\'_blank\'>'.Journal::getMarkValue($student_marks[$value2]['mark_value_id'])['name_short'] . '</a></span>&nbsp;';
                            }
                            echo '<td class="text-center">'.$html.'</td>';
                        }
                        else{echo  '<td class="text-center"><button type="button" class="btn btn-outline-info btn-sm"><i class="fa fa-plus" aria-hidden="true"></i></button></td>';}
                    }

                }


//                    }

            }
            else
            {
                echo 'Список пуст';
            }
            ?>

            </tr>
            </tbody>
        </table>
    </div>
</div>
</div>
