<?php


namespace app\modules\av\modules\student\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\base\DynamicModel;
/**
 * Default controller for the `staff` module
 */
class JournalController
{
    public function actionIndex()
    {
        $model = new DynamicModel(['group']);
        $model
            ->addRule(['group'], 'required');

        if(Yii::$app->request->isPost){
            $model->load(Yii::$app->request->post());
            $model->attributes = \Yii::$app->request->post('DynamicModel');
        }

        return [
            'view' => 'index',
            'model' => $model
        ];
    }
}


