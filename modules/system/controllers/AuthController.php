<?php

namespace app\modules\system\controllers;

use Yii;
use yii\web\Controller;

/**
 * Default controller for the `system` module
 */
class AuthController extends Controller
{


    public function actionIndex()
    {

        return $this->render('index');
    }

    public function actionSyncGroup()
    {

//        return $this->render('index');
    }
}
