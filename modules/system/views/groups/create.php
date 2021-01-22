<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\User */


?>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">Создание новой Роли</div>
                <div class="card-body">
                    <h5 class="card-title">Пожалуйста, заполните данные новой Роли</h5>
                    <?= $this->render('_form', [
                        'model' => $model,
                    ]) ?>
                </div>
            </div>
        </div>
