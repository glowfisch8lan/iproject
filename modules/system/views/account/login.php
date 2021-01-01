<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\system\SystemAsset;

$bundle = SystemAsset::register($this);


$this->title = 'Вход';
$this->params['breadcrumbs'][] = $this->title;
$this->registerCssFile($bundle->baseUrl . '/css/auth.css');
?>
<div class="wrapper">
    <div class="auth-content">
        <div class="card">
            <div class="card-body text-center">
                <div class="mb-4">
                    <img class="brand" src="<?=$bundle->baseUrl?>/img/logo_small.png" alt="bootstraper logo">
                </div>

                <h6 class="mb-4">Введите учетные данные</h6>

                <?php $form = ActiveForm::begin([
                        'id' => 'login-form',
                        'fieldConfig' => [
                            'template' => "<div class=\"form-group\">{input}</div>",
                            'labelOptions' => ['class' => 'control-label'],
                        ],
                    ]); ?>
                <?= $form->field($model, 'login')->textInput(['autofocus' => true])->input('login', ['placeholder' => "Введите свой логин"])?>
                <?= $form->field($model, 'password')->passwordInput()->input('password', ['placeholder' => "Введите свой пароль"])?>

                <?= $form->field($model, 'rememberMe', [
                        'options' => ['class' => 'text-left'],

                ])->checkbox()->label()?>

                <div class="form-group">
                        <?= Html::submitButton('Вход', ['class' => 'btn btn-primary shadow- mb-4', 'name' => 'login-button'])?>
                </div>

                <?php ActiveForm::end();?>


            </div>
        </div>
    </div>
</div>
