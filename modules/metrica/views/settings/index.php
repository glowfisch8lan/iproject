<?php
use Yii;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\web\View;


?>
<div class="container-fluid">
    <div class="page-title">
        <h3>Настройки</h3>
    </div>
    <div class="box box-primary">
        <div class="box-body">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="general-tab" data-toggle="tab" href="#general" role="tab" aria-controls="general" aria-selected="true">General</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="template-tab" data-toggle="tab" href="#template" role="tab" aria-controls="template" aria-selected="false">Шаблоны</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="patterns-tab" data-toggle="tab" href="#patterns" role="tab" aria-controls="patterns" aria-selected="false">Паттерны</a>
                </li>
            </ul>

            <div class="tab-content" id="myTabContent">

                <div class="tab-pane fade active show" id="general" role="tabpanel" aria-labelledby="general-tab">
                    <div class="col-md-6">
                        <p class="text-muted">General settings such as, site title, site description, address and so on.</p>
                        <div class="form-group">
                            <label for="site-title" class="form-control-label">Site Title</label>
                            <input type="text" name="site_title" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="site-description" class="form-control-label">Site Description</label>
                            <textarea class="form-control" name="site_description"></textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-control-label">Site Logo</label>
                            <div class="custom-file">
                                <input type="file" name="site_logo" class="custom-file-input">
                                <label class="custom-file-label">Choose File</label>
                            </div>
                            <small class="text-muted">The image must have a maximum size of 1MB</small>
                        </div>
                        <div class="form-group">
                            <label class="form-control-label">Favicon</label>
                            <div class="custom-file">
                                <input type="file" name="site_favicon" class="custom-file-input">
                                <label class="custom-file-label">Choose File</label>
                            </div>
                            <small class="text-muted">The image must have a maximum size of 1MB</small>
                        </div>
                        <div class="form-group">
                            <label class="form-control-label">Google Analytics Code</label>
                            <textarea class="form-control" name="google_analytics_code" rows="4"></textarea>
                        </div>
                        <div class="form-group text-right">
                            <button class="btn btn-success" type="submit"><i class="fas fa-check"></i> Save</button>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="template" role="tabpanel" aria-labelledby="template-tab">
                    <div class="col-md-6">
                        <p class="text-muted">Настройки шаблонов отчетов модуля "Метрика"</p>

                        <?php $form = ActiveForm::begin([

                                'action' => '/metrica/settings/upload',
                                'options' => []

                        ]) ?>

                        <div class="custom-file">

                            <?=
                            $form->field($settings, 'template',
                                ['template'=>
                                    "{input}{label}<small class=\"text-muted\">The file must have a maximum size of 1MB</small>"

                                ])
                                ->fileInput(
                                    [
                                        'class' => 'custom-file-input'
                                    ]
                                )->label('Выберите файл',['class' => 'custom-file-label']);
                            ?>
                        </div>

                        <? echo Html::submitButton('<i class="fas fa-check"></i> Сохранить', ['class' => 'btn btn-success']);?>
                        <?php ActiveForm::end() ?>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
