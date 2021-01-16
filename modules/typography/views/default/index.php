<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\captcha\Captcha;
use app\modules\staff\models\Units;
use app\modules\system\helpers\ArrayHelper;
use app\modules\system\helpers\FileUpload;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\staff\models\StateSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Книга по-требованию';
$this->params['breadcrumbs'][] = $this->title;
$js = <<< JS
$(document).ready(function() {
//$('.file-upload').file_upload();

  $('.file-upload').change(function() {
      console.log(this.files[0]);
    if(typeof this.files[0] != undefined) // если выбрали файл
        alert(1);
      $('.custom-file-label').text(this.files[0].name);
  });
});


JS;
$this->registerJs( $js, $position = yii\web\View::POS_END, $key = null );

?>
<div class="box-body">
    <div class="col-md-12">
        <div class="site-contact">
            <h1><?= Html::encode($this->title) ?></h1>

            <?php if (Yii::$app->session->hasFlash('contactFormSubmitted')): ?>

                <div class="alert alert-success">
                    Ваша заявка <strong>#<?=Yii::$app->session->getFlash('id')?></strong> успешно принята! Специалист обработает ее в ближайшее время.
                </div>

            <?php else: ?>
            <p>
                Статус заявки можете узнать <a href="/typography/default/status">здесь!</a><br>
            </p>

                <p>
                 Для печати книги по-требованию - пожалуйста, оставьте заявку!<br>
                </p>

                <div class="row">
                    <div class="col-lg-8">

                        <?php $form = ActiveForm::begin(['id' => 'feedback-form']); ?>

                        <?= $form->field($model, 'sender')->textInput() ?>

                        <?= $form->field($model, 'comment')->textarea(['rows' => 6]) ?>


                        <?=FileUpload::widget($form,$model)?>

                        <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                            'captchaAction' => '/typography/default/captcha',
                            'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-9">{input}</div></div>',
                        ]) ?>

                        <div class="d-flex flex-row justify-content-end"">
                            <div class="form-group justify-content-end" style="border:1px solid black">
                                <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary']) ?>
                            </div>
                        </div>


                        <?php ActiveForm::end(); ?>

                    </div>
                </div>

            <?php endif; ?>
        </div>
    </div>
</div>
