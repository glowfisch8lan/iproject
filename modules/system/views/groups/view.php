<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\DetailView;
use app\modules\system\helpers\Grid;
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
    <div class="row" style="padding:10px">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">Участники группы</div>
                <div class="card-body">
                    <?
                    $headerCallback = function(){
                        return Html::a('<i class="fa fa-user-plus" aria-hidden="true"></i>', '#',
                            [
                                    'class' => 'btn btn-outline-info',
                                    'data' => [
                                        'toggle' => 'modal',
                                        'target' => '#exampleModal'

                                ]
                            ]);
                    };
                    echo Grid::initWidget([
                        'dataProvider' => $dataProvider,
                        'columns' => [
                            [

                                'format' => 'raw',
                                'attribute' => 'login',
                                'label' => 'Логин',
                                'value' => function($model){
                                    return
                                        Html::a($model['login'], ['/system/users/update', 'id' => $model['id']], ['class' => 'link']);
                                }
                            ],
                            [
                                'attribute' => 'name',
                                'label' => 'Фамилия, имя и отчество'
                            ],
                            [
                                'attribute' => 'group',
                                'label' => 'Группа',
                                'headerOptions' => [
                                    'width' => 100,
                                ],
                            ],

                        ],
                        'ActionColumnHeader' => $headerCallback(),
                        'buttonsOptions' => ['template' => '{delete}'],
                        'ActionColumnButtons' => [
                            'delete' => function ($url, $inModel) use ($model) {
                                return ( ($inModel['login'] != 'admin') && ($model->id != 1) ) ? Html::a('<i class="fa fa-trash"></i>', '/system/groups/delete-group-members?user_id=' . $inModel['id'] .'&group_id='.$model->id,
                                        ['class' => 'btn btn-outline-danger',
                                            'data' => [
                                                'confirm' => 'Вы действительно хотите удалить данного пользователя из группы?',
                                                'method' => 'post',
                                            ]]) : false;

                            },
                        ],


                    ]);
                    ?>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">Настройка Группы</div>
                <div class="card-body">
                    <h5 class="card-title"><?= $model->name;?></h5>
                    <?
                    $options = ['class' => 'btn btn-danger'];
                    if ($model->id == 1) {
                        Html::addCssClass($options, 'd-none');
                    }
                    ?>
                    <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                    <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
                        'class' => $options,
                        'data' => [
                            'confirm' => 'Вы уверены, что хотите удалить группу?',
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
