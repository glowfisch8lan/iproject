<?php


use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\web\View;
use app\modules\system\helpers\Settings;


?>
<div class="container-fluid">
    <div class="page-title">
        <h3>Настройки</h3>
    </div>
    <div class="box box-primary">
        <div class="box-body">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <?= Settings::menu($SettingsMenuItems);?>
            </ul>

            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade active show" id="general" role="tabpanel" aria-labelledby="general-tab">
                    <div class="col-md-6">
                        <p class="text-muted">Основные настройки модуля</p>
                        <div class="form-group">
                            <label for="site-title" class="form-control-label">Site Title</label>
                            <input type="text" name="site_title" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="site-description" class="form-control-label">Site Description</label>
                            <textarea class="form-control" name="site_description"></textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-control-label">Site Logo</label>
                            <div class="custom-file">
                                <input type="file" name="site_logo" class="custom-file-input">
                                <label class="custom-file-label">Choose File</label>
                            </div>
                            <small class="text-muted">The image must have a maximum size of 1MB</small>
                        </div>
                        <div class="form-group">
                            <label class="form-control-label">Favicon</label>
                            <div class="custom-file">
                                <input type="file" name="site_favicon" class="custom-file-input">
                                <label class="custom-file-label">Choose File</label>
                            </div>
                            <small class="text-muted">The image must have a maximum size of 1MB</small>
                        </div>
                        <div class="form-group">
                            <label class="form-control-label">Google Analytics Code</label>
                            <textarea class="form-control" name="google_analytics_code" rows="4"></textarea>
                        </div>
                        <div class="form-group text-right">
                            <button class="btn btn-success" type="submit"><i class="fas fa-check"></i> Save</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
