<?php
/* @var $this yii\web\View */
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
    <div class="col-md-12">
        <table class="table table-responsive" style="font-size:14px">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">ФИО</th>
                <?
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

            </tr>
            </thead>
            <tbody>
                <?
                foreach( $model->students as $student )
                {
                    $marksArrByDiscipline = $model->filterReMarks($model->getStudentMarks($student['id']));
                    echo '<tr>';
                    echo "<th scope=\"row\">$index</th>
                            <td>" . $model->getShortName((object)$student) . "</td>";

                    foreach($map as $value) {
                        if(empty($marksArrByDiscipline[$value]))
                        {
                            echo '<td>-</td>';
                        }
                        else{
                            echo '<td>';
                                echo implode('' , $marksArrByDiscipline[$value]['marks']);
                            echo '</td>';
                        }
                    }
                    echo '</tr>';
                    $index++;
                }

                ?>

            </tbody>
        </table>
    </div>
</div>

