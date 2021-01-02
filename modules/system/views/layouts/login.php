<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use app\modules\system\SystemAsset;

$bundle = SystemAsset::register($this);

$options = [
    'position' => yii\web\View::POS_END,
    'depends' => 'app\modules\system\SystemAsset'
];

$this->registerCssFile($bundle->baseUrl . '/css/master.css', $options);
$this->registerCssFile($bundle->baseUrl . '/css/auth.css', $options);
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

    <?= $content ?>

</div>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
