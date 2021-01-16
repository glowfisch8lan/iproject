<?php

use yii\helpers\Html;
use \app\modules\system\helpers\Grid;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Справочник';
?>
<div class="container-fluid">
    <div class="page-title">
        <h3><?= Html::encode($this->title) ?>

        </h3>
    </div>
    <div class="box box-primary">
        <div class="box-body">
            <div class="col-md-12">

                <?= Grid::table($dataProvider,

                    [
                        [   'attribute' => 'id',
                            'type'   => 'row',
                            'headerOptions' => [
                                'width' => 15,
                            ],
                        ],
                        [   'attribute' => 'name',
                            'type'   => 'row',
                            'format' => 'raw',
                            //'value' =>
                        ]
/*                        [
                            'type'   => 'buttons',
                            'header' => '<div class="text-center"><a href="/metrica/settings/pattern-сreate" class="btn  btn-outline-info btn-rounded"><i class="fas fa-plus"></i></a></div>',
                            'buttons' => [
                                'update' => function ($url,$model) {
                                    return Html::a('<i class="fas fa-pen"></i>', '/metrica/settings/pattern-update?id=' . $model['id'],
                                        ['class' => 'btn btn-outline-info btn-rounded', 'data-method' => 'post']);
                                },
                                'delete' => function ($url,$model) {

                                    return Html::a('<i class="fas fa-trash"></i>', '/metrica/settings/pattern-delete?id=' . $model['id'],
                                        ['class' => 'btn btn-outline-danger btn-rounded',
                                            'data' => [
                                                'confirm' => 'Вы действительно хотите удалить данный паттерн?',
                                                'method' => 'post',
                                            ]]);
                                }

                            ]

                        ]*/

                    ]
                );

                ?>
            </div>
        </div>
    </div>
</div>