<?php
use yii\helpers\Html;
$groups = $model->getGroupList();
$faculties = $groups[$model->faculty]['items'];
$coursesFaculty = array_keys($faculties);

// регистрируем небольшой js-код в view-шаблоне
$script = <<< JS
$(document).ready(function(){
    /**
    * Отправка запроса на генерацию отчета
    */
    $('.generate-report').on('click',function(){
        
            var param = $('meta[name=csrf-param]').attr("content");
            var token = $('meta[name=csrf-token]').attr("content");console.log(param);
            
            
            let msg = $('.main-table-content').html();
            
            // msg = msg.replace(/\s{2,}/g, "");
            // msg = msg.replace(/<a href="(.*?)" (.*?)>/g, "");
            // msg = msg.replace(/<\/a>/g, "");
            //
            // msg = msg.replace(/class="(.*?)"/g, "");
            // msg = msg.replace(/style="(.*?)"/g, "");
            // msg = msg.replace(/type="(.*?)"/g, "");
            // msg = msg.replace(/\s{2,}/g, "");
            
            let form = document.createElement('form');
            let filename = $(this).attr('filename');
            form.action = '/av/plugins/reports';
            form.method = 'POST';
            form.innerHTML = '<input type="hidden" name="h" value='+Base64.encode(msg)+'><input type="hidden" name="'+param+'" value="'+token+'"><input type="hidden" name="filename" value='+filename+'>';
            document.body.append(form);
            form.submit();
        
    });
});
JS;
// значение $position может быть View::POS_READY (значение по умолчанию),
// или View::POS_LOAD, View::POS_HEAD, View::POS_BEGIN, View::POS_END
$position = $this::POS_END;
$this->registerJs($script, $position);
?>
<div class="box-body">
    <div class="col-md-12">
        <div class="col-12 m-2 p-2">
            <?
            $reports = [
                [
                    'name' => 'Отчет',
                    'filename' => 'Сводная_ведомость'
                ]
            ];
            $html = null;
            foreach($reports as $key => $value)
            {
                $html .= Html::a($value['name'], '#', [
                    'class' => 'btn btn-outline-secondary generate-report',
                    'filename' => $value['filename']
                ]);
            }
            if( !$ajax || 1 == 1 ){
                echo '
<div class="dropdown">
<a href="'.Yii::$app->session->get('home').'" class="btn btn-outline-secondary">Назад</a>
'.$html.'
</div>';

            }
            ?>
        </div>


    <div class="col-12 m-2 p-2">
        <h1>Сводная ведомость</h1>
        <h2><?=$groups[$model->faculty]['name']?></h2>
            <div class="main-table-content table-responsive">
                <table class="table table-bordered table-sm table-hover grade-sheet" style="font-size:14px">
                    <thead>
                    <th>Курс</th>
                    <th>Кол-во л/c</th>
                    <th>Ср. балл</th>
                    <th>Кол-во курсантов, имеющих ср. балл < 4</th>
                    <th>Кол-во курсантов, имеющих ср. балл < 3</th>
                    <th>Кол-во неудов. оценок</th>
                    <th>Кол-во отр. неудов. оценок</th>
                    </thead>
                    <tbody>
                    <?
                        foreach($coursesFaculty as $key => $value)
                        {
                            $countPeople = 0;
                            foreach($faculties[$value]['items'] as $group)
                            {
                                $countPeople += $group['student_count'];
                            }
                            $average = $model->getAverageFaculty($faculties[$value]);
                            echo '<tr>
                                    <td class="course" value='.$value.'>'.$faculties[$value]['name'].'</td>
                                    <td class="count-people" value='.$countPeople.'>'.$countPeople.'</td>
                                    <td class="average-faculty">'.$average['average'].'</td>
                                    <td class="count-above4">'.$average['count']['above4'].'</td>
                                    <td class="count-less3">'.$average['count']['less3'].'</td>
                                    <td class="count-count2">'.$average['count']['count2'].'</td>
                                    <td class="count-count2corrected">'.$average['count']['count2corrected'].'</td>
                                     </tr>';


                        }
                    ?>
                    </tbody>
                </table>
            </div>
    </div>
    </div>
</div>