<?php
use app\modules\system\helpers\Grid;
?>
<?= Grid::initWidget([
    'dataProvider' => $dataProvider,
    'columns' => [
        [
            'attribute' => 'ip',
            'label' => 'IP адрес'
        ],
        [
            'attribute' => 'date',
            'label' => 'Время'
        ],
        [
            'attribute' => 'userAgent',
            'label' => 'User Agent'
        ],
    ]],
    [
        'enableActionColumn' => false
    ]

);?>