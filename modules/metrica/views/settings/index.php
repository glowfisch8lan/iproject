<?php

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

                        <div class="form-group text-right">
                            <button class="btn btn-success" type="submit"><i class="fa fa-check"></i> Save</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
