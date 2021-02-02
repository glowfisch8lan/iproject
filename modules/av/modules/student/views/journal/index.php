<?php
/* @var $this yii\web\View */

use app\modules\system\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Успеваемость';
$this->params['breadcrumbs'][] = ['label' => Yii::$app->controller->module->name, 'url' => '/av'];
$this->params['breadcrumbs'][] = ['label' => 'Плагины', 'url' => '/av/plugins'];
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="box-body">
    <div class="col-12">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <td>
                        Группа №1
                    </td>
                    <td>
                        Дисциплина: Информатика
                    </td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Список
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                <button class="dropdown-item" type="button">ТГП</button>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>ФИО</td>
                    <td>01.01</td>
                    <td>02.01</td>
                    <td>03.01</td>
                    <td>04.01</td>
                    <td>05.01</td>
                    <td>06.01</td>
                    <td>07.01</td>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>
                        Иванов И.И.
                    </td>
                    <td>5</td>
                    <td>5</td>
                    <td>5</td>
                    <td>5</td>
                    <td>5</td>
                    <td>5</td>
                    <td>н./5</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

