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
JS;
$this->registerJs( $js, $position = yii\web\View::POS_END, $key = null );

?>
<div class="box-body">
    <div class="col-md-12">
        <div class="site-contact">
            <h1><?= Html::encode($this->title) ?></h1>

            <?php if (Yii::$app->session->hasFlash('statusFormSubmitted')): ?>
                <?
                $status = (Yii::$app->session->getFlash('status')) ? 'Выполнено' : 'В обработке';
                $class = (Yii::$app->session->getFlash('status')) ? 'success' : 'warning';
                ?>
                <a href="/typography/default/status">Назад</a>
                <div class="alert alert-<?=$class?>">
                   Статус вашей заявки: <?=$status?>
                </div>

            <?php else: ?>

            <p>
                Введите № заявки, чтобы проверить статус<br>
            </p>

            <div class="row">
                <div class="col-lg-8">

                    <?php $form = ActiveForm::begin(['id' => 'feedback-form']); ?>

                    <?= $form->field($model, 'id')->textInput()->label('Номер заявки') ?>

                    <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                        'captchaAction' => '/typography/default/captcha',
                        'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-9">{input}</div></div>',
                    ])->label('')  ?>

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
