<?
/* @var $this \yii\web\View */
/* @var $content string */


use yii\web\View;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use app\modules\system\helpers\Menu;
use app\modules\system\SystemAsset;
use app\modules\system\models\interfaces\modules\Modules;
use yii\widgets\Pjax;
$bundle = SystemAsset::register($this);

$options = [
        'position' => yii\web\View::POS_END,
        'depends' => 'app\modules\system\SystemAsset'
];


$this->registerCssFile($bundle->baseUrl . '/css/master.css', $options);
$this->registerCssFile($bundle->baseUrl . '/css/file-upload.css', $options);
$this->registerCssFile($bundle->baseUrl . '/css/CRUD.css', $options);

$this->registerCssFile($bundle->baseUrl . '/vendor/airdatepicker/dist/css/datepicker.min.css', $options);
$this->registerCssFile($bundle->baseUrl . '/vendor/mdtimepicker/mdtimepicker.min.css', $options);


$this->registerJsFile( $bundle->baseUrl . '/vendor/airdatepicker/dist/js/datepicker.min.js', $options, $key = null );
$this->registerJsFile( $bundle->baseUrl . '/vendor/airdatepicker/dist/js/i18n/datepicker.en.js', $options, $key = null );
$this->registerJsFile( $bundle->baseUrl . '/vendor/mdtimepicker/mdtimepicker.min.js', $options, $key = null );
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
    <body class="d-flex flex-column min-vh-100">
    <? $this->beginBody() ?>

            <div class="content">
                <div class="container-fluid">
                    <div class="box box-primary">

                        <?=$content?>
                    </div>
            </div>
        </div>
    <?php $this->endBody(); ?>
    </body>

    </html>
<?php $this->endPage() ?>