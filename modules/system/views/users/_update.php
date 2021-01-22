<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="col-lg-12 user-form">
    <div class="card">
        <div class="card-header"></div>
        <div class="card-body">
           <!-- <h5 class="card-title">Введите новые учетные данные</h5>-->
            <?php $form = ActiveForm::begin(); ?>

                <div class="form-row">
                    <div class="form-group col-md-12">
                        <?= $form->field($model, 'name', [
                            'template' => '<div>{label}</div><div>{input}</div><div class="text-danger">{error}</div>'
                        ])->textInput(['autofocus' => true, 'maxlength' => true, 'disabled' => $model->login == 'admin']); ?>

                    </div>
                    <div class="form-group col-md-6">
                        <?= $form->field($model, 'login', [
                        'template' => '<div>{label}</div><div>{input}</div><div class="text-danger">{error}</div>'
                        ])->textInput(['maxlength' => true, 'disabled' => $model->login == 'admin']); ?>

                    </div>
                    
                    <div class="form-group col-md-6">
                        <?= $form
                            ->field($model, 'password', [
                            'template' => '<div>{label}</div><div>{input}</div><small>Введите пароль, если вы хотите его изменить либо оставьте поле пустым.</small>
                            <div class="text-danger">{error}</div>'
                            ])
                            ->passwordInput();
                        ?>
                    </div>



                </div>

                <div class="form-group">
                    <?= $form
                        ->field($model, 'userGroup', [
                            'template' => '<div>{label}</div><div>{input}</div><div>{error}</div>'
                        ])
                        ->dropDownList(
                                $model->allGroups,
                            [
                                'options' => [
                                    $model->userGroup => ['selected' => true],
                                ],
                                'disabled' => $model->login == 'admin'
                                ]
                        );
                    ?>
                </div>


                <div class="form-group">
                    <?= Html::submitButton('<i class="fas fa-save"></i> Сохранить', ['class' => 'btn btn-success']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
