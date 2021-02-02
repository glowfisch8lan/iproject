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
?>

<?
$index = 1;

foreach( $model->students as $student )
{
    $data[]['student'] = $model->getShortName((object)$student);
    $marksArrByDiscipline = $model->filterReMarks($model->getStudentMarks($student['id']));
}
?>

<div class="box-body">
    <div class="col-12 m-2">
        <a href="#" class="btn btn-outline-secondary" onclick="history.back();return false;">Назад</a>
        <?
        echo Html::a('Экспорт', Url::to([
            '/av/plugins/load',
            'module' => 'student',
            'id' => 'AcademicPerformance',
            'controller' => 'AcademicPerformance',
            'action' => 'generateReport'
        ]), [
                'class' => 'btn btn-outline-secondary',
                'data' => [
                'method' => 'post',
                'params' => [
                    'AcademicPerformance[group]' => $model->group['id'],
                    'AcademicPerformance[startDate]' => $model->startDate,
                    'AcademicPerformance[endDate]' => $model->endDate,
                ],
            ],
        ]);
        ?>
    </div>

    <div class="col-12">
        <div class="table-responsive">
        <table class="table table-bordered" style="font-size:12px">
            <thead>
            <tr>
                <th colspan="100"><a href="https://av.dvuimvd.ru/student/students/<?=$model->group['id']?>/index" target="_blank">Группа: <?=$model->group['name']?></a></th>
            </tr>
            <tr>
                <th scope="col">#</th>
                <th scope="col">ФИО</th>
                <?
                #
                # Дисциплины
                #
                $map = [];
                foreach( $model->collectDisciplines() as $id )
                {
                    $name_short = $model->getDisciplineName($id)['name_short'];
                    if($name_short != null)
                    {
                        $map[] = $id;
                        echo "<th scope=\"col\" id='".$id."'>".$name_short."</th>";
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
                                    echo "<a href=\"https://av.dvuimvd.ru/student/journal/view?group_id=$group&lesson_id=$journal_lesson_id\" target='_blank'>" . $mark . '</a> ';
                                }
                            echo '</td>';
                        }
                    }
                    echo '<td>'.$model->getAverageMarksStudent($marksArrByDiscipline).'</td></tr>';
                    $index++;
                }

                ?>

            </tbody>
        </table>
        </div>
    </div>
</div>

