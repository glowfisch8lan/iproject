<?php


use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use app\modules\system\helpers\ArrayHelper;
use app\modules\av\modules\student\models\StudentsApi;

$ajax = ($ajax) ? 'ajax' : 'load';
$groups = array_filter(ArrayHelper::map(StudentsApi::getGroups() , 'id', 'name') , function ($data)
{
    if ((preg_match('/(ПД|Ю).*.2\d*$/', $data, $matches))) return $data;
});
$disciplines = ['1444' => 'ТСП'];

$form = ActiveForm::begin(['action' => "/av/plugins/" . $ajax . "?module=student&id=journal&controller=journal&action=journal"]);
echo $form->field($model, 'group')->widget(Select2::classname() , ['data' => $groups, 'language' => 'ru', 'value' => 'red', 'options' => ['placeholder' => 'Выберите группу ...'], 'pluginOptions' => ['allowClear' => true], ])->label('');

echo $form->field($model, 'discipline')->widget(Select2::classname() , ['data' => $disciplines, 'language' => 'ru', 'value' => 'red', 'options' => ['placeholder' => 'Выберите дисциплину ...'], 'pluginOptions' => ['allowClear' => true], ])->label('') . Html::submitButton('Запросить', ['class' => 'btn btn-primary']);

ActiveForm::end(); ?>