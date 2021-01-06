<?php

namespace app\modules\feedback\controllers;

use yii\web\Controller;

/**
 * Default controller for the `feedback` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public $layout = '/main';

    public function actionIndex()
    {
        return $this->render('index');
    }
}
