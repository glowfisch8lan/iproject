<?php

use yii\widgets\ActiveForm;
use app\modules\av\modules\student\models\StudentsApi;
use app\modules\system\helpers\ArrayHelper;
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Успеваемость';
$this->params['breadcrumbs'][] = ['label' => Yii::$app->controller->module->name, 'url' => '/'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box-body">
    <div class="col-md-12">
        <?

        $form = ActiveForm::begin([
                'action' => '/av/plugins/load?module=student&id=AcademicPerformance&controller=AcademicPerformance&action=GetGradeSheet&ajax=true'
        ]);
        echo $form->field($model, 'group')->dropDownList(
            ArrayHelper::map(StudentsApi::getGroups(), 'id', 'name')
        );
        ?>
                <div class="form-group">
                    <?= Html::submitButton('<i class="fa fa-save"></i> Отправить', ['class' => 'btn btn-success']) ?>
    </div>
    <?ActiveForm::end();?>
    </div>
</div>

