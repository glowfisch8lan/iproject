<?php

use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
use app\modules\staff\models\Workers;
use app\modules\staff\models\Vacancies;
use app\modules\system\helpers\ArrayHelper;
use app\modules\staff\models\Units;

/* @var $this yii\web\View */
/* @var $model app\modules\staff\models\State */
/* @var $form yii\widgets\ActiveForm */
$js = <<< JS

    jQuery.fn.filterByText = function(textbox, selectSingleMatch) {
        return this.each(function() {
            var select = this;
            var options = [];
            $(select).find('option').each(function() {
                options.push({value: $(this).val(), text: $(this).text()});
            });
            $(select).data('options', options);
            $(textbox).bind('change keyup', function() {
                var options = $(select).empty().data('options');
                var search = $(this).val().trim();
                var regex = new RegExp(search,"gi");
              
                $.each(options, function(i) {
                    var option = options[i];
                    if(option.text.match(regex) !== null) {
                        $(select).append(
                           $('<option>').text(option.text).val(option.value)
                        );
                    }
                });
                if (selectSingleMatch === true && $(select).children().length === 1) {
                    $(select).children().get(0).selected = true;
                }
            });            
        });
    };

    $(function() {
        $('#state-vacancies_id').filterByText($('#textbox_vacancies'), false);
      $("select option").click(function(){
        alert(1);
      });
    });
        $(function() {
        $('#state-workers_id').filterByText($('#textbox_workers'), false);
      $("select option").click(function(){
        alert(1);
      });
    });
JS;

$this->registerJs( $js, $position = View::POS_END, $key = null );
?>

<div class="state-form">

    <?php $form = ActiveForm::begin(); ?>
    <input id="textbox_workers" type="text" />
    <?var_dump(ArrayHelper::mapMerge(Workers::find()->asArray()->all(), 'id', ['lastname', 'firstname', 'middlename'], ' '));?>
    <?= $form->field($model, 'workers_id')->dropDownList(ArrayHelper::mapMerge(Workers::find()->asArray()->all(), 'id', ['lastname', 'firstname', 'middlename'], ' ')) ?>

    <?

    foreach(Vacancies::find()->with('unit')->with('position')->all() as $vacancie){
        $arr[$vacancie->id] = $vacancie->position->name . ' | ' .$vacancie->unit->name_short;
    }
    ?>
    <input id="textbox_vacancies" type="text" />
    <?= $form->field($model, 'vacancies_id')->listBox($arr, ['maxlength' => 255])->label('Вакансия') ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
