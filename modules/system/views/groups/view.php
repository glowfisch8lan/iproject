<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\widgets\ActiveForm;


?>

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Добавить в группу</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php $form = ActiveForm::begin([
                    'id' => 'groupMembers',
                    'options' => [
                        'class' => 'form-horizontal'
                    ],
                    'fieldConfig' => [
                        'template' => '<div class="col-md-12">{label}</div><div class="col-md-12">{input}</div><div class="col-md-6">{error}</div>',
                    ],
                ]); ?>
                    <div class="modal-body">

                        <?= $form->field($model, 'listMembers')->listBox( $model->listUsers  , ['multiple' => true])->label('Выберите пользователей');?>
                        <?= $form->field($model, 'action')->hiddenInput(['value'=>'add'])->label(false);;?>
                        <?= $form->field($model, 'name')->hiddenInput(['value'=>$model->name])->label(false);;?>


                    </div>
                    <div class="modal-footer">
                        <?= Html::submitButton('<i class="fas fa-user-plus"></i> Добавить', ['class' => 'btn btn-sm btn-outline-primary float-right']);?>

                    </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-6">
            <div class="box box-primary">
                <div class="page-title">
                    <h3>
                        <a href="#" class="btn btn-sm btn-outline-primary float-right" data-toggle="modal" data-target="#exampleModal"><i class="fas fa-user-plus"></i></a>
                    </h3>
                    </h3>
                </div>

                <div class="box-body">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'columns' => [
                            [
                                'class' => 'yii\grid\SerialColumn'

                            ],

                    /*        [
                                'attribute' => 'id',
                                'header' => 'Индекс',
                                'headerOptions' => [
                                    'width' => 50,
                                ],
                            ],*/
                            [
                                'label' => 'Ссылка',
                                'format' => 'raw',
                                'attribute' => 'login',
                                'header' => 'Логин',
                                'value' => function($data){

                                    return
                                        Html::a($data['login'], ['/system/users/update', 'id' => $data['id']], ['class' => 'link']);
                                }
                            ],
                            [
                                'attribute' => 'name',
                                'header' => 'Фамилия, имя и отчество'
                            ],
                            [
                                'attribute' => 'group',
                                'header' => 'Группа',
                                'headerOptions' => [
                                    'width' => 100,
                                ],
                            ],


                            [
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{delete}',
                                'headerOptions' => [
                                    'width' => 150,
                                ],
                                'contentOptions'=> ['style'=>'text-align: center;'],
                                'buttons' => [
                                    'delete' => function ($url,$data) use ($model) {
                                        if( $data['login'] == 'admin' && $model->id == 1) {
                                            return false;
                                        }
                                        else {

                                            return Html::a('<i class="fas fa-trash"></i>', '/system/groups/delete-group-members?user_id=' . $data['id'] .'&group_id='.$model->id,
                                                ['class' => 'btn btn-outline-danger btn-rounded',
                                                    'data' => [
                                                        'confirm' => 'Вы действительно хотите удалить данного пользователя из группы?',
                                                        'method' => 'post',
                                                    ]]);
                                        }
                                    },
                                ],
                            ],



                        ],


                    ]); ?>
                </div>
            </div>
        </div>


        <div class="col-lg-6">

            <div class="card">
                <div class="card-header">Настройка Группы</div>
                <div class="card-body">
                    <h5 class="card-title"><?= $model->name;?></h5>

                    <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                    <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => 'Are you sure you want to delete this item?',
                            'method' => 'post',
                        ],
                    ]) ?>

                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'id',
                            'name',
                            'description'
                        ],
                    ]) ?>
                </div>
            </div>

        </div>
    </div>
</div>
