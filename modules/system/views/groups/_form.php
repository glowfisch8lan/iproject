<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \app\modules\system\models\interfaces\modules\Module;
?>
<?php $form = ActiveForm::begin(); ?>

<div class="form-row row">


    <div class="form-group col-md-12">
        <?= $form->field($model, 'name', [
            'template' => '<div>{label}</div><div>{input}</div><small>Введите имя новой Группы</small>
            <div class="text-danger">{error}</div>'
        ])->textInput(['autofocus' => true]); ?>

    </div>

    <div class="form-group col-md-12">
        <?= $form->field($model, 'description', [
            'template' => '<div>{label}</div><div>{input}</div><small>Введите описание Группы</small>
            <div class="text-danger">{error}</div>'
        ]); ?>

    </div>

    <div class="form-group field-users-groups">
        <input type="hidden" name="Groups[permissions]" value="">
        <div id="users-groups">

            <? $modules = Module::getAllModules();

            foreach($modules as $module){
                    $label = null;
                    $titleStatus = null;

                    if(!empty($model->usedPermissions)){
                        if((in_array($module->visible, $model->usedPermissions))) {
                            $titleStatus = 'checked';
                        }
                    }
                    foreach($module->routes as $route){

                        $boolean = null;

                        if(!empty($model->usedPermissions)) {
                            if ((in_array($route['access'], $model->usedPermissions))) {
                                $boolean = 'checked';
                            }
                        }

                        $label .= '<dd><label><input type="checkbox" name="Group[permissions][]" value="' . $route['access'] . '" ' . $boolean . '> ' . $route['description'] . '</label></dd>';
                    }

                echo '<dl><dt><label><input type="checkbox" name="Group[permissions][]" value="' . $module->visible . '" ' . $titleStatus . '> ' . $module->name . ' (Доступ к модулю)</label></dt>' . $label . '</dl>';

            }?>

        </div>

        <div class="help-block"></div>
    </div>
</div>

<div class="form-group">
    <?= Html::submitButton('<i class="fas fa-save"></i> Сохранить', ['class' => 'btn btn-success']) ?>
</div>

<?php ActiveForm::end(); ?>
