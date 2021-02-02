<?php


namespace app\modules\av\modules\student\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use app\modules\av\modules\student\models\plugins\Journal;
/**
 * Default controller for the `staff` module
 */
class JournalController
{
    public function actionIndex()
    {
        $model = new Journal();
        return [
            'view' => 'index',
            'model' => $model
        ];
    }
}


