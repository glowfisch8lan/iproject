<?php

namespace app\modules\journal\controllers;

class AttendanceController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

}
