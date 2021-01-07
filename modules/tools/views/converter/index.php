<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\tools\models\UploadForm;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Генератор логинов';
$this->params['breadcrumbs'][] = ['label' => Yii::$app->controller->module->name, 'url' => '/system/tools'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box-body pt-3 pl-5">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <h5 class="card-header">Moodle CSV конвертер</h5>
                <div class="card-body">
                    <h5 class="card-title">Пакетная генерация логинов из .csv файла</h5>
                    <p class="card-text">
                        <?php $form = ActiveForm::begin([
                            'action' => '/tools/converter/276a2c57cc79ecfe8e88623411a5c1c5',
                            'options' => []
                        ]) ?>
                        <?=
                        $form->field($model, 'file',
                            ['template' =>
                                "<div class=\"custom-file\">{input}{label}</div><small class=\"text-muted\">Размер файла не должен превышать 1MB</small>"

                            ])
                            ->fileInput(
                                [
                                    'class' => 'custom-file-input'
                                ]
                            )->label('Выберите файл',['class' => 'custom-file-label']);
                        ?>
                        <div class="d-flex justify-content-end">
                        <? echo Html::submitButton('<i class="fa fa-check"></i> Обработать', ['class' => 'btn btn-primary']);?>
                        </div>
                        <?php ActiveForm::end() ?>

                    </p>
                </div>
            </div>
        </div>
    </div>
<!--            <div class="col-md-4">-->
<!--                <ul class="list-group list-group-flush">-->
<!--                    --><?//
//                    $index = 0;
//                    $htmlBody = null;
//
//                  foreach(UploadForm::getUploadList() as $item){
//                        $index++;
//                        $htmlBody .= '<li class="list-group-item">' . Html::a($index . ') ' . $item['basename'], ['generator-login/generator', 'file' => $item['basename']], ['class' => 'profile-link']).'</li>';
//                        if($index === 5){$htmlBody .= '<li class="list-group-item text-center">...</li>';break;}
//
//                   }
//                    $htmlBegin = '<li class="list-group-item d-flex justify-content-between align-items-center">Файлы: <span class="badge badge-primary badge-pill">' . $index . '</span></li>';
//                    echo $htmlBegin . $htmlBody;
//                    ?>
<!---->
<!--                </ul>-->
<!--            </div>-->

</div>

