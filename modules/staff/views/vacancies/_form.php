<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\modules\staff\models\Positions;
use app\modules\staff\models\Units;

?>

<div class="col-lg-12 user-form">
    <div class="card">
        <div class="card-header"></div>
        <div class="card-body">
            <!-- <h5 class="card-title">Введите новые учетные данные</h5>-->
            <?php $form = ActiveForm::begin(); ?>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <?= $form->field($model, 'unit_id')->dropDownList(ArrayHelper::map(Units::find()->asArray()->all(), 'id', 'name')) ?>
                    <?= $form->field($model, 'position_id')->dropDownList(ArrayHelper::map(Positions::find()->asArray()->all(), 'id', 'name')) ?>

                </div>
            </div>
            <div class="form-group">
                <?= Html::submitButton('<i class="fas fa-save"></i> Сохранить', ['class' => 'btn btn-success']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>