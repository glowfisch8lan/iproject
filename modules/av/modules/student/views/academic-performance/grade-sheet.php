<?php
/* @var $this yii\web\View */

use app\modules\system\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
/* @var $dataProvider yii\data\ActiveDataProvider */

Html::csrfMetaTags();

$this->title = 'Сводная ведомость успеваемости';
$this->params['breadcrumbs'][] = ['label' => Yii::$app->controller->module->name, 'url' => '/av'];
$this->params['breadcrumbs'][] = ['label' => 'Плагины', 'url' => '/av/plugins'];
$this->params['breadcrumbs'][] = ['label' => 'Успеваемость', 'url' => '/av/plugins/load?module=student&id=academicPerformance&controller=academicPerformance'];
$this->params['breadcrumbs'][] = $this->title;

// регистрируем небольшой js-код в view-шаблоне
$script = <<< JS
$(document).ready(function(){
    
    /**
    *   Подсчет среднего балла студента
    * */
    function calculateAverageMarks(){
    console.log('Calculate average marks');
    
     $('table.grade-sheet').find("tr").each(function(){
     
         var index = 0;
         var sum = 0;
         
         $(this).find('td.marks > a > span').each(function(){
                var mark = $(this).attr('value');
                if(typeof mark != "undefined"){
                    var result = mark.match(/(\d)/g);
                    if(result != null)
                    {
                    
                       for (let i = 0; i < result.length; i++)
                        {
                        
                            sum +=  Number.parseInt(result[i]);
                            index++;
                        }
                       
                    }
                    
                }
         });
         var average = Math.floor((sum/index) * 100) / 100;
         if(isNaN(average)){average = '-';}
         $(this).find('td.average').html('').append('<strong>'+average+'</strong>');
         });
    }
    
    /**
    *   Подсчет среднего балла дисциплины
    * */
    function calculateDisciplinesAverage(){
         var indexArray = [];
         $('table.grade-sheet').find("tr:eq(1)  > th.disciplines").each(function(){
         indexArray.push($(this).index());
         });
         $(document).find('tr.disciplines-average').html('').append('<td colspan="2"></td>');
         for(let i = 0 ; i < indexArray.length ; i++ )
             {
                 var index = 0;
                 var sum = 0;
                 
                   $('table.grade-sheet').find("tr.main-content").each(function(){
                     $(this).find('td.marks:eq('+i+')').each(function(){
                            
                                  $(this).find('a > span').each(function(){
                                    var mark = $(this).attr('value');
                                    if(typeof mark != "undefined"){
                                        var result = mark.match(/(\d)/g);
                                        if(result != null)
                                        {
                                           for (let i = 0; i < result.length; i++)
                                            {
                                                sum +=  Number.parseInt(result[i]);
                                                index++;
                                            }
                                        }
                                    }
                             });
                         });
                    });
                    var average = Math.floor((sum/index) * 100) / 100;
                    if(isNaN(average)){average = '-';}
                    $('.table.grade-sheet').find('tr.disciplines-average').append('<td><strong>'+average+'</strong></td>');
                    
             }
         $('.table.grade-sheet').find('tr.disciplines-average').append('<td class="group-average bg-primary text-white"></td>')
    }
    /**
    *   Подсчет среднего балла группы
    * */
    function calculateAverageGroup()
    {
    
        var index = 0;
        var sum = 0;
        
        $('.table.grade-sheet').find('td.average').each(function(){
            var mark = Number.parseInt( $(this).text() );
            if(!isNaN(mark))
                {
                    sum += mark;
                }
            index++;
        });
        var average = Math.floor((sum/index) * 100) / 100;
        if(isNaN(average)){average = '-';}
        $('.table.grade-sheet').find('td.group-average').html('<strong>'+average+'</strong>');
    }
    
    calculateAverageMarks();
    calculateDisciplinesAverage();
    calculateAverageGroup();
    
    /**
    * Отправка запроса на генерацию отчета
    */
    $('.generate-report').on('click',function(){
        
            var param = $('meta[name=csrf-param]').attr("content");
            var token = $('meta[name=csrf-token]').attr("content");console.log(param);
            
            
            let msg = $('.main-table-content').html();
            
            msg = msg.replace(/\s{2,}/g, "");
            msg = msg.replace(/<a href="(.*?)" (.*?)>/g, "");
            msg = msg.replace(/<\/a>/g, "");
            
            msg = msg.replace(/class="(.*?)"/g, "");
            msg = msg.replace(/style="(.*?)"/g, "");
            msg = msg.replace(/type="(.*?)"/g, "");
            msg = msg.replace(/\s{2,}/g, "");
            
            let form = document.createElement('form');
            let filename = $(this).attr('filename');
            form.action = '/av/plugins/reports';
            form.method = 'POST';
            form.innerHTML = '<input type="hidden" name="h" value='+Base64.encode(msg)+'><input type="hidden" name="'+param+'" value="'+token+'"><input type="hidden" name="filename" value='+filename+'>';
            document.body.append(form);
            form.submit();
            
//           var form = document.createElement("form");
//            form.setAttribute("method", 'post');
//            form.setAttribute("action", url);
//        
//            var input = document.createElement("input");
//            input.setAttribute("type", "hidden");
//            input.setAttribute("name", 'table');
//            input.setAttribute("value", btoa(toBinary(msg)));
//        
//            form.appendChild(input);
//            form.submit();
        
    });
    
    $('.table-row-discipline-remove').on('click',function(){
        var myIndex = $(this).parent('th').index();
        $(this).parents("table").find("tr").each(function(){
        $(this).find("th:eq("+myIndex+")").fadeOut(150, function(){ $(this).remove(); });
        $(this).find("td:eq("+myIndex+")").fadeOut(150, function(){ $(this).remove(); });
        });
    });
    
    $('.average-ball').on('click',function(){
            calculateAverageMarks();
            calculateAverageGroup();
        });
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
$reports = [
        [
                'name' => 'Текущая успеваемость',
                'filename' => 'Текущая_успеваемость'

        ]
];
$html = null;
foreach($reports as $key => $value)
{
    $html .= Html::a($value['name'], '#', [
        'class' => 'dropdown-item generate-report',
        'filename' => $value['filename']
    ]);
}
            if( !$ajax || 1 == 1 ){
                echo '
<div class="dropdown">
<a href="'.Yii::$app->session->get('home').'" class="btn btn-outline-secondary">Назад</a>
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
        <div class="main-table-content table-responsive">
        <table class="table table-bordered table-sm table-hover grade-sheet" style="font-size:14px">
            <thead>
            <tr>
                <td colspan="6">
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
                        echo "<th scope=\"col\" id='".$id."' class='disciplines'>".$name_short." <a href='#' class='text-danger table-row-discipline-remove'><i class=\"fa fa-minus-circle\" aria-hidden=\"true\"></a></i></th>";
                    }
                }

                ?>
                <th scope="col">Ср. балл <a href='#' class='text-info average-ball'><i class="fa fa-refresh" aria-hidden="true"></a></i></th>
            </tr>
            </thead>
            <tbody>
                <?
                #
                # Студенты
                #

                foreach( $model->students as $student )
                {
                    $marksArrByDiscipline = $model->filterReMarks($model->getStudentMarks($student['id']), $model->isSkip);
                    echo '<tr class="main-content">';
                    echo "<td scope=\"row\">$index</td>
                            <td type='table-td-students'><a href=\"https://av.dvuimvd.ru/student/students/".$model->group['id']."?student_id=".$student['id']."\" target='_blank'>" . $model->getShortName((object)$student) . "</a></td>";


                    foreach($map as $value) {

                        if(empty($marksArrByDiscipline[$value])) {echo '<td class="marks">-</td>';}

                        else{
                            echo '<td disciplines='.$value.' class="marks">';

                                foreach($marksArrByDiscipline[$value]['marks'] as $key => $mark)
                                {
                                    $journal_lesson_id = $model->marks[ArrayHelper::recursiveArraySearch($key, $model->marks)[0]]['journal_lesson_id'];
                                    $group = $model->group['id'];
                                    /* Выставляем оценки */

                                    $lightMarks = [
                                            '2' => ['class' => 'bg-danger', 'text-color' => 'text-white'],
                                            '5' => ['class' => 'bg-success', 'text-color' => 'text-white'],
                                            '4' => ['class' => 'bg-info', 'text-color' => 'text-white'],
                                            '3' => ['class' => 'bg-secondary', 'text-color' => 'text-white'],
                                            'н.' => ['class' => 'bg-warning', 'text-color' => 'text-white'],
                                            'б.' => ['class' => 'bg-warning', 'text-color' => 'text-white'],
                                            'к.' => ['class' => 'bg-warning', 'text-color' => 'text-white'],
                                            'о.' => ['class' => 'bg-warning', 'text-color' => 'text-white'],
                                            'у.' => ['class' => 'bg-warning', 'text-color' => 'text-white'],
                                    ];

                                    $class['bg'] = (isset($lightMarks[$mark])) ? $lightMarks[$mark]['class'] : 'default';
                                    $class['text-color'] = (isset($lightMarks[$mark])) ? $lightMarks[$mark]['text-color'] : null;

                                    echo "<a href=\"https://av.dvuimvd.ru/student/journal/view?group_id=$group&lesson_id=$journal_lesson_id\" target='_blank' class='m-1 ".$class['text-color']."'><span class='".$class['bg']." p-1' value='$mark'>" . $mark . '</span></a>';


                                }

                            echo '</td>';
                        }
                    }
                    //echo '<td>'.$model->getAverageMarksStudent($marksArrByDiscipline).'</td></tr>';
                    echo '<td class="average"></td></tr>';
                    $index++;

                }

//                #
//                # Ср. балл дисциплины
//                #
//
//                $marksArr = $model->filterReMarks($model->filterMarks($model->marks, [$model->startDate , $model->endDate]));
//                $sum = $model->getAverageMarksDiscipline($marksArr);
//                ksort($sum);
//
//                echo '<tr><td></td><td></td>';
//                foreach($sum as $discipline_key => $discipline_value)
//                {
//                    echo '<td>'.$discipline_value['average'].'</td>';
//                }
//
//                echo '</tr>';





                ?>

            <tr class="disciplines-average"></tr>
            </tbody>
        </table>
        </div>
    </div>
</div>

