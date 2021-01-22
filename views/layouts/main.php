<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use app\assets\AppAsset;
use  app\modules\system\SystemAsset;
$bundle = AppAsset::register($this);
$bundleSystem = SystemAsset::register($this);

$options = [
    'position' => yii\web\View::POS_BEGIN,
    'depends' => 'app\assets\AppAsset'
];
$this->registerCssFile($bundleSystem->baseUrl . '/css/file-upload.css', $options);
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
    <?php $this->registerCsrfMetaTags() ?>
    <title><?=Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<div class="wrapper">
    <div class="container content">
        <div class="header">
            <ul class="nav">
                <li class="nav-item">
                    <a class="nav-link active" href="/">Главная</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">FAQ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/feedback">Обратная связь</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/typography">Типография</a>
                </li>
            </ul>
            <a href="//dvuimvd.ru"><div class="logo" style="background-image: url('<?=$bundle->baseUrl?>/img/icon/logo.png')"></div></a><h3 class="text-muted">ДВЮИ МВД России</h3>
        </div>
            <?= $content ?>
        <div class="footer">
            <p>&copy; ДВЮИ МВД РФ 2020</p>
        </div>
    </div>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
