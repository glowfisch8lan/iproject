<?php

namespace app\modules\av\controllers;

use yii\web\Controller;

/**
 * Default controller for the `av` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
    public function actionTest()
    {
        var_dump('hello');
    }
}
