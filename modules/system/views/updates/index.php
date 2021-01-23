<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\system\helpers\FileUpload;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Загрузка патча | Обновление';
$this->params['breadcrumbs'][] = ['label' => Yii::$app->controller->module->name, 'url' => '/system/updates'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box-body pt-3 pl-5">
    <div class="row">
        <div class="col-md-6">

            <div class="card">
                <h5 class="card-header">Менеджер патчей и обновлений</h5>
                <div class="card-body">
                    <h5 class="card-title">Установка обновлений</h5>
                    <p class="card-text">
                        <?php $form = ActiveForm::begin([
                            'id' => 'feedback-form',
                            'action' => '/system/updates/patch',
                            'fieldConfig' => [
                                'template' => "{label}{input}{hint}{error}",
                            ],
                        ]); ?>
                        <?=FileUpload::widget($form,$model)?>

                    <div class="d-flex flex-row justify-content-end"">
                    <div class="form-group justify-content-end" style="border:1px solid black">
                        <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary']) ?>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
                    </p>
                </div>


        </div>

        </div>
    </div>
</div>

