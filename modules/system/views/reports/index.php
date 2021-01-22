<?php

use yii\helpers\Html;
use yii\grid\GridView;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Отчеты';

?>
<div class="container-fluid">
    <div class="page-title">
        <h3><?= Html::encode($this->title) ?>
            <a href="/system/users/create" class="btn btn-sm btn-outline-primary float-right"><i class="fas fa-user-plus"></i> Добавить</a>
        </h3>
    </div>
    <div class="box box-primary">
        <div class="box-body">
        </div>
    </div>