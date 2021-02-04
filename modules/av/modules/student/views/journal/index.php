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
        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Список
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                <button class="dropdown-item" type="button">ТГП</button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <td>
                        <?php
                        $ajax = ($ajax) ? 'ajax' : 'load';
                        $groups = array_filter(ArrayHelper::map(StudentsApi::getGroups() , 'id', 'name') , function ($data)
                        {
                            if ((preg_match('/(ПД|Ю).*.2\d*$/', $data, $matches))) return $data;
                        });

                        $form = ActiveForm::begin(['action' => "/av/plugins/" . $ajax . "?module=student&id=journal&controller=journal"]);
                        echo $form->field($model, 'group')->widget(Select2::classname() , ['data' => $groups, 'language' => 'ru', 'value' => 'red', 'options' => ['placeholder' => 'Выберите группу ...'], 'pluginOptions' => ['allowClear' => true], ])->label('');
                        echo Html::submitButton('Запросить', ['class' => 'btn btn-primary']);
                        ActiveForm::end(); ?>
                    </td>
                </tr>
                <tr>
                    <td>ФИО</td>
                    <?
                    $days = array(
                        "пнд",
                        "вт",
                        "ср",
                        "чт",
                        "пт",
                        "сб",
                        "вск"
                    );
                    $startDate = (string)'20.01.2021';

                    $date = new DateTime($startDate);
                    $format = 'd.m';
                    $datetime = strtotime($startDate);
                    $ITS_NUM = date("w", $datetime);
                    $arrDate = null;
                    echo "<td>" . $date->format($format) . '&nbsp;(' . $days[$ITS_NUM - 1] . ")</td>";

                    $arrDate[] = $date->format($format);
                    for ($i = 0;;$i++)
                    {

                        if ($i > 5)
                        {
                            break;
                        }
                        $date->modify('+1 days');
                        $arrDate[] = $date->format($format);
                        echo "<td>" . $date->format($format) . '&nbsp;(' . $days[$i + 1] . ")</td>";

                    }
                    ?>
                </tr>
                </thead>
                <tbody>
                <?
                //1444 - Огневая; group - 24
                if ($model->group)
                {
                    $students = StudentsApi::getStudentsByGroup($model->group);
                    $marks = StudentsApi::getMarksByGroup($model->group);
                    $marks = $model->filterMarks($marks, [$startDate, $date->format('d.m.Y') ]);
                    $marks = $model->filterDiscipline($marks, 1444);
                    $index = 0;

                    foreach ($students as $student)
                    {
                        $index++;

//                        if($index == 10)
//                        {
                            $arr = ArrayHelper::recursiveArraySearch($student['id'], $marks); //Все оценки студента


                            echo '<tr><td>' . AcademicPerformance::getShortName((object)$student) . '</td>'; // Выводим имя;
                            //выбираем оценки студента;
                            $student_marks = null;
                            foreach ($arr as $i => $key)
                            {
                                $student_marks[] = $marks[$key];
                            }

                            uasort($student_marks, function ($a, $b)
                            {
                                return ($a['lesson_date'] > $b['lesson_date']);
                            });

                            foreach($arrDate as $key1 => $value1)
                            {

                                //var_dump($value1);
                                $h = $value1.'.'.date('Y');
                                var_dump($student_marks);
                                die();
                                $r = new DateTime($h);
                                $result = ArrayHelper::recursiveArraySearch($r->format('Y-m-d'), $student_marks);

                                $html = null;
                                if($result)
                                {

                                    foreach($result as $key2 => $value2)
                                    {
                                        var_dump($student_marks[$value2]);
                                        $html = '<span class=\'p-1\'><a href="https://av.dvuimvd.ru/student/journal/view?group_id='.$model->group.'&lesson_id='.$student_marks[$value2]['journal_lesson_id'].'" target=\'_blank\'>'.Journal::getMarkValue($student_marks[$value2]['mark_value_id'])['name_short'] . '</a></span>&nbsp;';
                                    }
                                    echo '<td>'.$html.'</td>';
                                }
                                else{echo  '<td>---</td>';}
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
