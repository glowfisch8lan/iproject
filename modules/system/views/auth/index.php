<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>
<div class="box-body">
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="general-tab" data-toggle="tab" href="#general" role="tab" aria-controls="general" aria-selected="true">Основные</a>
        </li>

        <li class="nav-item">
            <a class="nav-link" id="adldap-tab" data-toggle="tab" href="#adldap" role="tab" aria-controls="adldap" aria-selected="true">AD/LDAP</a>
        </li>
    </ul>

    <div class="tab-content" id="myTabContent">

        <div class="tab-pane fade active show" id="general" role="tabpanel" aria-labelledby="general-tab">
            <div class="col-md-6">

            </div>
        </div>

        <div class="tab-pane fade" id="adldap" role="tabpanel" aria-labelledby="adldap-tab">
            <div class="col-md-6">
                <p class="text-muted">Настройки подключения и авторизации/аутенфикации через AD/LDAP</p>
                <? $form = ActiveForm::begin([
                    'action' => "/system/auth/save"
                ]);
                ?>
                <div class="col-2">
                    <div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" id="switch1" name="Settings[adldap_on]"><label class="custom-control-label" for="switch1">Статус</label></div>
                </div>

                <div class="col-6">
                    <?= $form->field($model, 'value')->input('text',['name' => 'system[adldap][server]'])->label('Сервер')?>
                    <?= $form->field($model, 'value')->input('text',['name' => 'system[adldap][port]'])->label('Порт')->hint('Если используется более одного контроллера домена, укажите порт глобального каталога - 3268')?>
                </div>

                <div class="d-flex flex-row justify-content-end">
                    <div class="form-group justify-content-end" style="border:1px solid #5e0800"><?= Html::submitButton('Запросить', ['class' => 'btn btn-primary']) ?></div>
                </div>
                <?php ActiveForm::end(); ?>

            </div>
        </div>

    </div>

</div>
