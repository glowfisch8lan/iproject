<?php

use app\modules\system\models\users\Groups;
use yii\helpers\Html;
use yii\widgets\ActiveForm;


$this->title = 'Создание пользователя';
$this->params['breadcrumbs'][] = ['label' => Yii::$app->controller->module->name, 'url' => '/system/settings'];
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => '/system/' . Yii::$app->controller->id];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box-body">
    <div class="col-md-12">
        <div class="col-lg-12 user-form">
        <div class="card">
            <div class="card-header"><i class="fa fa-user-circle" aria-hidden="true"></i> Создание учетной записи</div>
            <div class="card-body">
                <?php $form = ActiveForm::begin(); ?>

                <div class="form-row">
                    <div class="form-group col-md-12">
                        <?= $form->field($model, 'name', [
                            'template' => '<div>{label}</div><div>{input}</div><small>Введите имя пользователя</small><div class="text-danger">{error}</div>'
                        ])->textInput(['autofocus' => true]); ?>

                    </div>
                    <div class="form-group col-md-6">
                        <?= $form->field($model, 'login', [
                            'template' => '<div>{label}</div><div>{input}</div><small>Введите логин пользователя</small>
                        <div class="text-danger">{error}</div>'
                        ])->textInput(); ?>

                    </div>

                    <div class="form-group col-md-6">
                        <?= $form
                            ->field($model, 'password', [
                                'template' => '<div>{label}</div><div>{input}</div>
                             <small>Пожалуйста, придумайте пароль</small><div class="text-danger">{error}</div>'
                            ])
                            ->textInput(['autofocus' => true])
                            ->passwordInput();
                        ?>
                    </div>



                </div>

                <div class="form-group">


                    <?
                    foreach( Groups::getAllGroupList() as $val ){
                        echo '<div class="custom-control custom-checkbox">';
                        echo '<input type="checkbox" class="custom-control-input" name="Users[groups][]" id="switch' . $val['id'] . '" value="' . $val['id'] . '" >';
                        echo '<label class="custom-control-label" for="switch' . $val['id'] . '">' . $val['name'] . '</label><br>';
                        echo '</div>';
                    }
                    ?>

                </div>


                <div class="form-group">
                    <?= Html::submitButton('<i class="fas fa-save"></i> Создать', ['class' => 'btn btn-success']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
    </div>
</div>
