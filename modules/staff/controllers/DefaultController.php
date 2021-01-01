<?php

namespace app\modules\staff\controllers;

use yii\web\Controller;

/**
 * Default controller for the `staff` module
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
}
