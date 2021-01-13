<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\captcha\Captcha;
use app\modules\staff\models\Units;
use app\modules\system\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\staff\models\StateSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Обратная связь';
$this->params['breadcrumbs'][] = $this->title;
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
                    Если у вас есть необходимость в решении технических проблем - пожалуйста, оставьте заявку!<br>
                    В поле <strong>"Отправитель"</strong> и <strong>"Подразделение"</strong> укажите свои <i>данные</i>
                </p>

                <div class="row">
                    <div class="col-lg-8">

                        <?php $form = ActiveForm::begin(['id' => 'feedback-form']); ?>

                        <?= $form->field($model, 'sender')->textInput() ?>

                        <?= $form->field($model, 'unitSender')->textInput() ?>

                        <?= $form->field($model, 'subject') ?>

                        <?= $form->field($model, 'text')->textarea(['rows' => 6]) ?>

                        <?= $form->field($model, 'unit_id')->dropDownList(ArrayHelper::map(Units::find()->asArray()->all(), 'id', 'name')) ?>

                        <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                            'captchaAction' => '/feedback/default/captcha',
                            'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-9">{input}</div></div>',
                        ]) ?>

                        <div class="d-flex flex-row justify-content-end"">
                            <div class="form-group justify-content-end" style="border:1px solid black">
                                <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
                            </div>
                        </div>


                        <?php ActiveForm::end(); ?>

                    </div>
                </div>

            <?php endif; ?>
        </div>
    </div>
</div>
