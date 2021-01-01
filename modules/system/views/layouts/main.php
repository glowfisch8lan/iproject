<?
/* @var $this \yii\web\View */
/* @var $content string */

use yii\web\View;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use app\modules\system\helpers\MenuHelper;
use app\modules\system\SystemAsset;
use app\modules\system\models\interfaces\modules\Module;

$bundle = SystemAsset::register($this);

$js = <<< JS
$(document).ready(function(){

    
    var status =  localStorage.getItem('isHideSidebar');
    
    if( status == '1'){
        $('#sidebar').addClass('active');
    }

    // $('a').on('click', function() {
    //    
    //     if($(this).hasClass("active")){
    //         localStorage.setItem('isHideSidebar',1);
    //     }
    //     else{
    //         localStorage.setItem('isHideSidebar', 0);
    //     }
    //  });
    
    $('#sidebarCollapse').on('click', function() {
        
        if($("#sidebar").hasClass("active")){
            localStorage.setItem('isHideSidebar',1);
        }
        else{
            localStorage.setItem('isHideSidebar', 0);
        }
     });
});
JS;

$this->registerJs( $js, $position = View::POS_END, $key = null );

?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?=Yii::$app->language
    ?>">

    <head>
        <meta charset="<?=Yii::$app->charset
        ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <? $this->registerCsrfMetaTags() ?>
        <title>
            <?=Html::encode($this->title) ?>
        </title>
        <? $this->head() ?>
    </head>

    <body>
    <? $this->beginBody() ?>
    <div class="wrapper">
        <nav id="sidebar">
            <div class="sidebar-header"> <img src="<?=$bundle->baseUrl?>/img/logo.png" alt="logo" class="app-logo" width="210"> </div>
            <ul class="list-unstyled components">
                <li> <a href="/my"><i class="fas fa-home"></i> Главная панель</a> </li>
                <li> <span class="ml-3">Модули</span> </li>
                <?= MenuHelper::widget(Module::getAllModules());?>
            </ul>
        </nav>
        <div id="body" class="active">
            <nav class="navbar navbar-expand-lg navbar-primary bg-primary">
                <button type="button" id="sidebarCollapse" class="btn btn-outline-light default-light-menu" "data-pjax"="0"><i class="fas fa-bars"></i><span></span></button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="nav navbar-nav ml-auto">
                        <li class="nav-item dropdown">
                            <div class="nav-dropdown"> <a href="" class="nav-item nav-link dropdown-toggle" data-toggle="dropdown"><i class="fas fa-user"></i> <span><?= Yii::$app
                                            ->user
                                            ->identity->login; ?></span> <i style="font-size: .8em;" class="fas fa-caret-down"></i></a>
                                <div class="dropdown-menu dropdown-menu-right nav-link-menu">
                                    <ul class="nav-list">
                                        <li><a href="/system/users/profile" class="dropdown-item"><i class="fas fa-address-card"></i> Профиль</a></li>
                                        <li><a href="" class="dropdown-item"><i class="fas fa-envelope"></i> Сообщения</a></li>
                                        <div class="dropdown-divider"></div>
                                        <li> <a href="/system/default/logout" data-method="post" class="dropdown-item"><i class="fas fa-sign-out-alt"></i> Выйти</a></li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>

            <div class="content">
                <div class="container-fluid">
                    <div class="page-title">
<!--                        <h1>--><?//= Html::encode($this->title)?><!--</h1> -->
                        </div>
                    <div class="box box-primary">
                        <div>
                            <?= Breadcrumbs::widget([
                                'homeLink' => [
                                    'label' => 'Главная',
                                    'url' => '/my',
                                ],
                                'tag' => 'ol',
                                'itemTemplate' => '<li class="breadcrumb-item">{link}</li>',
                                'activeItemTemplate' => '<li class="breadcrumb-item active">{link}</li>',
                                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],

                            ]) ?>
                        </div>
                        <?=$content?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php $this->endBody(); ?>
    </body>

    </html>
<?php $this->endPage() ?>