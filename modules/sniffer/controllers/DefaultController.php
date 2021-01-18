<?php

namespace app\modules\sniffer\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Html;

/**
 * Default controller for the `tools` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        var_dump(Yii::$app->request->userIP);
        return $this->renderPartial('index');
    }

}
