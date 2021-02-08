<?php
/* @var $this yii\web\View */

use app\modules\system\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Успеваемость';
$this->params['breadcrumbs'][] = ['label' => Yii::$app->controller->module->name, 'url' => '/av'];
$this->params['breadcrumbs'][] = ['label' => 'Плагины', 'url' => '/av/plugins'];
$this->params['breadcrumbs'][] = $this->title;

// регистрируем небольшой js-код в view-шаблоне
$script = <<< JS
$(document).ready(function(){
    
    $('.table-row-discipline-remove').on('click',function(){
        
        var myIndex = $(this).parent('th').index();
        $(this).parents("table").find("tr").each(function(){
        $(this).find("th:eq("+myIndex+")").remove();
        $(this).find("td:eq("+myIndex+")").remove();
        });

        
    });
//     console.log();
//     $('.mark-two').on('click', function(){
//         value = $(this).attr('value');
//         $('span[value = '+value+']').addClass('bg-success').find('a').addClass('text-white')
//     });
     });
JS;
// значение $position может быть View::POS_READY (значение по умолчанию),
// или View::POS_LOAD, View::POS_HEAD, View::POS_BEGIN, View::POS_END
$position = $this::POS_END;
$this->registerJs($script, $position);

$index = 1;

foreach( $model->students as $student )
{
    $data[]['student'] = $model->getShortName((object)$student);
    $marksArrByDiscipline = $model->filterReMarks($model->getStudentMarks($student['id']));
}
?>

<div class="box-body">
    <div class="col-12 m-2 p-2">
            <?
            if(!$ajax){
$reports = [
        [
                'name' => 'Ведомость успеваемости',
                'urlParams' => [
                    '/av/plugins/load',
                    'module' => 'student',
                    'id' => 'AcademicPerformance',
                    'controller' => 'AcademicPerformance',
                    'action' => 'generateReport'
                ],
            'data' => [
                'method' => 'post',
                'params' => [
                    'AcademicPerformance[group]' => $model->group['id'],
                    'AcademicPerformance[startDate]' => $model->startDate,
                    'AcademicPerformance[endDate]' => $model->endDate,
                ],
            ],

        ],


];
$html = null;
foreach($reports as $key => $value)
{
    $html .= Html::a('Ведомость успеваемости', Url::to($value['urlParams']), [
        'class' => 'dropdown-item',
        'data' => $value['data']
    ]);
}
                echo '
<div class="dropdown">
<a href="#" class="btn btn-outline-secondary" onclick="history.back();return false;">Назад</a>
  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    Отчеты
  </button>
  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
'.$html.'
  </div>
</div>';

            }
            ?>
    </div>



<!--    <div class="col-12 m-2">-->
<!--        <span>Фильтр: </span>-->
<!--        <a href="#" class="btn btn-outline-danger mark-two" color="danger" data-toggle="button" aria-pressed="false" autocomplete="off" value="2">2</a>-->
<!--        <a href="#" class="btn btn-outline-warning mark-three" color="warning" data-toggle="button" aria-pressed="false" autocomplete="off" value="3">3</a>-->
<!--        <a href="#" class="btn btn-outline-success mark-four" color="success" data-toggle="button" aria-pressed="false" autocomplete="off" value="4">4</a>-->
<!--        <a href="#" class="btn btn-outline-success mark-five" color="success" data-toggle="button" aria-pressed="false" autocomplete="off" value="5">5</a>-->
<!--    </div>-->

    <div class="col-12">
        <div class="table-responsive">
        <table class="table table-bordered table-sm table-hover" style="font-size:14px">
            <thead>
            <tr>
                <td colspan="100">
                    <strong>
                    <span>
                        Группа:
                    <a href="https://av.dvuimvd.ru/student/students/<?=$model->group['id']?>/index" target="_blank"><?=$model->group['name']?></a>
                        </span>
                    /
                    <span>
                    Учебный план <a href="https://av.dvuimvd.ru/plan/plans/<?=$model->group['education_plan_id'];?>" target="_blank">№<?=$model->group['education_plan_id'];?></a>
                    </span>
                    /
                    </strong>
                    <span>
                    <?
                    $date[0] = new DateTime($model->startDate);
                    $date[1] = new DateTime($model->endDate);
                    echo $date[0]->format('d.m') . '-' . $date[1]->format('d.m.Y');
                    ?>

                    </span>

                </td>
            </tr>

            <tr>
                <th scope="col">#</th>
                <th scope="col">ФИО</th>
                <?

                #
                # Дисциплины - получаем все дисциплины за период
                #
                $map = [];

                $d = $model->collectDisciplines();

                foreach( $d as $id )
                {
                    $name_short = $model->getDisciplineName($id)['name_short'];
                    if($name_short != null)
                    {
                        $map[] = $id;
                        echo "<th scope=\"col\" id='".$id."'>".$name_short." <a href='#' class='text-danger table-row-discipline-remove'><i class=\"fa fa-minus-circle\" aria-hidden=\"true\"></a></i></th>";
                    }
                }

                ?>
                <th scope="col">Ср. балл</th>
            </tr>
            </thead>
            <tbody>
                <?
                #
                # Студенты
                #
                foreach( $model->students as $student )
                {
                    $marksArrByDiscipline = $model->filterReMarks($model->getStudentMarks($student['id']));
                    echo '<tr>';
                    echo "<th scope=\"row\">$index</th>
                            <td><a href=\"https://av.dvuimvd.ru/student/students/".$model->group['id']."?student_id=".$student['id']."\" target='_blank'>" . $model->getShortName((object)$student) . "</a></td>";

                    foreach($map as $value) {
                        if(empty($marksArrByDiscipline[$value])) {echo '<td>-</td>';}
                        else{
                            echo '<td>';
                            foreach($marksArrByDiscipline[$value]['marks'] as $key => $mark)
                            {
                                $journal_lesson_id = $model->marks[ArrayHelper::recursiveArraySearch($key, $model->marks)[0]]['journal_lesson_id'];
                                $group = $model->group['id'];
                                /* Выставляем оценки */
                                if((int)$mark == 2){
                                    echo "<span class='bg-danger p-1'  value='$mark'><a href=\"https://av.dvuimvd.ru/student/journal/view?group_id=$group&lesson_id=$journal_lesson_id\" target='_blank' class='text-white'>" . $mark . '</a></span>';
                                }
                                else{
                                    echo "<span class='p-1' value='$mark'><a href=\"https://av.dvuimvd.ru/student/journal/view?group_id=$group&lesson_id=$journal_lesson_id\" target='_blank'>" . $mark . '</a></span>';
                                }

                            }
                            echo '</td>';
                        }
                    }
                    echo '<td>'.$model->getAverageMarksStudent($marksArrByDiscipline).'</td></tr>';
                    $index++;

                }

                #
                # Ср. балл дисциплины
                #

                $marksArr = $model->filterReMarks($model->filterMarks($model->marks, [$model->startDate , $model->endDate]));
                $sum = $model->getAverageMarksDiscipline($marksArr);
                ksort($sum);

                echo '<tr><td></td><td></td>';
                foreach($sum as $discipline_key => $discipline_value)
                {
                    echo '<td>'.$discipline_value['average'].'</td>';
                }

                echo '</tr>';





                ?>

            </tbody>
        </table>
        </div>
    </div>
</div>

