<?php

use app\modules\system\models\users\Groups;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;



?>
<div class="user-create">
    <div class="col-lg-12 user-form">
        <div class="card">
            <div class="card-header"></div>
            <div class="card-body">
                <!-- <h5 class="card-title">Введите новые учетные данные</h5>-->
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
                        echo '<label><input type="checkbox" name="Users[groups][]" value="' . $val['id'] . '" > ' . $val['name'] . '</label><br>';
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
