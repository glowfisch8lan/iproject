<?php

use app\modules\system\models\users\Groups;
use app\modules\system\models\users\Users;
use yii\helpers\Html;
use yii\widgets\ActiveForm;


$this->title = 'Редактирование пользователя: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::$app->controller->module->name, 'url' => '/system/settings'];
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => '/system/' . Yii::$app->controller->id];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box-body">
    <div class="col-md-12">
    <div class="col-lg-12 user-form">
        <div class="card">
            <div class="card-header"><i class="fa fa-user-circle" aria-hidden="true"></i> <?=$model->name;?></div>
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
                        $disabled = ($model->id == 1) ? 'disabled' : null;
                        foreach( Groups::getAllGroupList() as $val ){
                                $boolean = ( array_search( $val['name'], array_column( Users::getUserGroups($model->id), 'group')) === false ) ? null : 'checked';
                                //TODO: Сделать проверку аккаунта на уровне движка;
                                echo '<label><input type="checkbox" name="User[groups][]" value="' . $val['id'] . '"' . $boolean . ' ' .  $disabled . '> ' . $val['name'] . '</label><br>';
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
</div>
