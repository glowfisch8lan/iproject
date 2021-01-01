<?php
use yii\widgets\Pjax;
use yii\helpers\Html;
?>

<?php Pjax::begin(); ?>
<?= Html::a(
    'Показать время',
    ['/dashboard/default/pjax-example-3?action=time'],
    ['class' => 'btn btn-lg btn-primary']
) ?>
<?= Html::a(
    'Показать дату',
    ['/dashboard/default/pjax-example-3?action=date'],
    ['class' => 'btn btn-lg btn-success']
) ?>
    <p>Ответ сервера: <?= $data ?></p>
<?php Pjax::end(); ?>