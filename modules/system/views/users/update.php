<?php

use app\modules\system\models\users\Groups;
use app\modules\system\models\users\Users;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\User */
?>

<div class="user-update">

    <div class="col-lg-12 user-form">
        <div class="card">
            <div class="card-header"></div>
            <div class="card-body">
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



                <div class="form-group field-users-groups">
                    <input type="hidden" name="Users[groups]" value="">

                        <div id="users-groups">

                    <?
                        foreach( Groups::getAllGroupList() as $val ){

                            $boolean = 'checked';
                            if( array_search( $val['name'], array_column( Users::getUserGroups($model->id), 'group')) === false ) {
                                $boolean = null;
                            }
                            echo '<label><input type="checkbox" name="User[groups][]" value="' . $val['id'] . '"' . $boolean . '>' . $val['name'] . '</label><br>';


                        }
                    ?>

                        </div>

                    <div class="help-block"></div>
                </div>



                <div class="form-group">
                    <?= Html::submitButton('<i class="fas fa-save"></i> Сохранить', ['class' => 'btn btn-success']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
