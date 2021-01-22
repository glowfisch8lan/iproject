<?php use yii\helpers\Html;
?>
<?= Html::a('Text',
    ['/tools/test/index'], [
        'data-method' => 'POST',
        'data-params' => [
            'username' => 'grigorov_de',
            'password' => 2,
        ],
    ]) ?>
<?= Html::a('Text',
    'https://av.dvuimvd.ru/ajax-login', [
        'data-method' => 'POST',
        'data-params' => [
            'username' => 'grigorov_de',
            'password' => '18954569',
        ],
    ]) ?>
