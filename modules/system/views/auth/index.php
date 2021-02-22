<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\modules\system\models\settings\Settings;

$checked = (filter_var(Settings::getValue('system.auth.ldap.status'), FILTER_VALIDATE_BOOLEAN) === true) ? 'checked' : null;

$script = <<< JS
    var urlNum = window.location.href.split('/').pop().split('#').pop();

    if($('li > a.nav-link#'+urlNum+'-tab').length != 0 )
    {
        $('#general-tab').removeClass('active');
        $('.tab-pane#general').removeClass('active show');
    
        $('#'+urlNum+'-tab').addClass('active');
        $('.tab-pane#'+urlNum).removeClass('fade').addClass('show active');
    }
    
  $('#settings-ldap').on('afterValidate', function (event, messages, errorAttributes) {
      
      console.log(messages);
    $('#settings-ldap').on('beforeSubmit', function (e) {
        return false;
    });
    });
JS;
$this->registerJs($script, $position = yii\web\View::POS_READY);

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
                        'id' => 'settings-ldap',
                    'action' => "/system/settings/save",
                    'enableAjaxValidation' => true,
                    'validationUrl' => '/system/settings/validate-form'
                ]); ?>

                <div class="col-2">
                        <div class="custom-control custom-switch">
                            <input type = hidden  name="system[auth][ldap][status]" value = "false">
                            <input type="checkbox" class="custom-control-input" id="switch1" name="system[auth][ldap][status]" value="true" <?=$checked?>>
                            <label class="custom-control-label" for="switch1">Статус</label></div>
                </div>

                <div class="col-6">
                    <?= $form->field($model, 'value')->input('text',
                        ['name' => 'system[auth][ldap][server]',
                            'value' => Settings::getValue('system.auth.ldap.server')
                        ]
                    )->label('Сервер')?>
                    <?= $form->field($model, 'value')->input('text',[
                        'name' => 'system[auth][ldap][port]',
                        'value' => Settings::getValue('system.auth.ldap.port')
                    ])->label('Порт')->hint('Если используется более одного контроллера домена, укажите порт глобального каталога - 3268')?>
                </div>

                <div class="d-flex flex-row justify-content-end">
                    <div class="form-group justify-content-end" style="border:1px solid #5e0800"><?= Html::submitButton('Запросить', ['class' => 'btn btn-primary']) ?></div>
                </div>
                <?php ActiveForm::end(); ?>

            </div>
        </div>

    </div>

</div>
