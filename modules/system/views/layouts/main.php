<?
/* @var $this \yii\web\View */
/* @var $content string */


use yii\web\View;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use app\modules\system\helpers\Menu;
use app\modules\system\SystemAsset;
use app\modules\system\models\interfaces\modules\Modules;

$bundle = SystemAsset::register($this);

$options = [
        'position' => yii\web\View::POS_END,
        'depends' => 'app\modules\system\SystemAsset'
];


$this->registerCssFile($bundle->baseUrl . '/css/master.css', $options);
$this->registerCssFile($bundle->baseUrl . '/css/CRUD.css', $options);
$this->registerCssFile($bundle->baseUrl . '/vendor/airdatepicker/dist/css/datepicker.min.css', $options);
$this->registerCssFile($bundle->baseUrl . '/vendor/mdtimepicker/mdtimepicker.min.css', $options);

$this->registerJsFile( $bundle->baseUrl . '/vendor/airdatepicker/dist/js/datepicker.min.js', $options, $key = null );
$this->registerJsFile( $bundle->baseUrl . '/vendor/airdatepicker/dist/js/i18n/datepicker.en.js', $options, $key = null );
$this->registerJsFile( $bundle->baseUrl . '/vendor/mdtimepicker/mdtimepicker.min.js', $options, $key = null );
$this->registerJsFile( $bundle->baseUrl . '/js/is-hide-sidebar.js', $options, $key = null );
$this->registerJsFile( $bundle->baseUrl . '/js/script.js', $options, $key = null );

// регистрируем небольшой js-код в view-шаблоне
$script = <<< JS

$('ul.collapse').on('hide.bs.collapse', function () {
            sessionStorage.setItem('#'+$(this).attr('id'), 0);
        });
        
$('ul.collapse').on('show.bs.collapse', function () {
            sessionStorage.setItem('#'+$(this).attr('id'), 1);
        });

$(document).ready(function(){
    
    $(this).find('a.menu-link-dropdown').each(function(){
        
        var id = $(this).attr('href');
        var result = sessionStorage.getItem(id);
        if(Number(result) === 1){
            $(id).collapse();
        };
    });
        
    });
JS;
// значение $position может быть View::POS_READY (значение по умолчанию),
// или View::POS_LOAD, View::POS_HEAD, View::POS_BEGIN, View::POS_END
$position = $this::POS_END;
$this->registerJs($script, $position);
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

    <body class="d-flex flex-column min-vh-100">
    <? $this->beginBody() ?>
    <div class="wrapper">
        <nav id="sidebar">
            <div class="sidebar-header"> <img src="<?=$bundle->baseUrl?>/img/logo.png" alt="logo" class="app-logo" width="210"> </div>
            <ul class="list-unstyled components">
                <li> <a href="/my"><i class="fa fa-home"></i> Личный кабинет</a> </li>
<!--                <li> <span class="ml-3"></span> </li>-->
                <?= Menu::widget(Modules::getAllModules());?>
            </ul>
        </nav>
        <div id="body" class="active">

            <nav class="navbar navbar-expand-lg navbar-primary bg-primary">
                <button type="button" id="sidebarCollapse" class="btn btn-outline-light default-light-menu" "data-pjax"="0"><i class="fa fa-bars"></i><span></span></button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="nav navbar-nav ml-auto">
                        <li class="nav-item dropdown">
                            <div class="nav-dropdown"> <a href="" class="nav-item nav-link dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <span><?= Yii::$app
                                            ->user
                                            ->identity->login; ?></span> <i style="font-size: .8em;" class="fa fa-caret-down"></i></a>
                                <div class="dropdown-menu dropdown-menu-right nav-link-menu">
                                    <ul class="nav-list">
                                        <li><a href="/system/users/profile" class="dropdown-item"><i class="fa   fa-address-card"></i> Профиль</a></li>
                                        <li><a href="" class="dropdown-item"><i class="fa fa-envelope"></i> Сообщения</a></li>
                                        <div class="dropdown-divider"></div>
                                        <li> <a href="/system/default/logout" data-method="post" class="dropdown-item"><i class="fa fa-sign-out-alt"></i> Выйти</a></li>
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
                                    'url' => '/',
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