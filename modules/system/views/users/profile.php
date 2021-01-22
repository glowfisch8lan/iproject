<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\modules\social\components\ProfileSettingsWidget;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Профиль';

?>
<div class="container-fluid">
    <div class="page-title">
        <h3><?= $this->title ?></h3>
    </div>

    <div class="box box-primary">
        <div class="box-body">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="general-tab" data-toggle="tab" href="#general"
                       role="tab" aria-controls="general" aria-selected="true">Основные</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="social-tab" data-toggle="tab" href="#social-settings"
                       role="tab" aria-controls="social-settings" aria-selected="false">Меры поддержки</a>
                </li>
            </ul>

            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade active show" id="general" role="tabpanel" aria-labelledby="general-tab">
                    <div class="col-md-12">
                        <p class="text-muted">Основые настройки такие как ФИО, контакты и т.д.</p>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="lastname" class="form-control-label">Фамилия</label>
                                <input type="text" name="lastname" class="form-control">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="fisrtname" class="form-control-label">Имя</label>
                                <input type="text" name="firstname" class="form-control">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="patronymic" class="form-control-label">Отчество</label>
                                <input type="text" name="patronymic" class="form-control">
                            </div>
                            <div class="form-group col-md-12">
                                <label for="email">Email</label>
                                <input type="email" name="email" placeholder="Email Address" class="form-control">
                            </div>
                        </div>

                        <div class="form-group text-right">
                            <button class="btn btn-success" type="submit"><i class="fas fa-check"></i> Save</button>
                        </div>
                    </div>
                </div>
                <!-- Личные настройки модуля "Меры поддержки" -->
                <?= ProfileSettingsWidget::widget(); ?>


            </div>
        </div>
    </div>


</div>