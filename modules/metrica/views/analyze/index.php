<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\web\View;

$js = <<< JS
$(document).on('click', '.btn-add', function() {
     var html = '<div class="form-group field-request-url required"><div class="input-group col-lg-8"><div style="margin-right:10px;"><a href="#" class="btn btn-outline-primary btn-add"><i class="fas fa-plus" aria-hidden="true"></i></a></div><input type="text" id="request-url" class="form-control" name="Parser[url][]" aria-required="true"></div></div>';
     
     $('#input-url-group').prepend(html);
     $(this).removeClass('btn-outline-primary btn-add').addClass('btn-outline-danger btn-remove').html('<i class="fas fa-minus" aria-hidden="true"></i>');
});
$(document).on('click', '.btn-remove', function() {
    
     $(this).parents('.form-group').remove();
});
JS;

$this->registerJs( $js, $position = yii\web\View::POS_END, $key = null );
?>


<div class="container-fluid">
    <div class="page-title">
        <h3>&laquo;Метрика&raquo; - Система поиска и анализа метрической информации</h3>
    </div>
    <div class="box box-primary">
        <div class="box-body">
            <a href="/metrica/history">История</a>
            <? $form = ActiveForm::begin([
            'id' => 'form-analyze',
            'options' => [
            'enctype' => 'multipart/form-data',
                'data-pjax' => true
            ],
            ]);?>

            <h8>Введите URL</h8>
            <div id="input-url-group">
                    <?= $form->field($model, 'url[]',
                        ['template'=>
                            "<div class=\"input-group col-lg-8\"><div style='margin-right:10px;'><a href='#' class='btn btn-outline-primary btn-add disabled' 'data-pjax'='0' ><i class=\"fa fa-plus\" aria-hidden=\"true\"></i></a></div>{input}</div>"])
                        ->textInput(['class' => 'form-control'])->label(false); ?>



            </div>

            <div class="form-group">
                <?

                foreach( $patterns as $val ){
                    echo '<span><label><input type="checkbox" name="Parser[patterns][]" value="' . $val['id'] . '" > ' . $val['name'] . '</label></span>';
                }

                ?>

            </div>
            <? echo Html::submitButton('Запрос', ['class' => 'btn btn-primary']);?>
            <? ActiveForm::end();?>

        </div>
    </div>
</div>