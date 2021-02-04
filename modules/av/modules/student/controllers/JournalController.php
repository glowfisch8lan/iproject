<?php


namespace app\modules\av\modules\student\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\base\DynamicModel;
use app\modules\av\modules\student\models\plugins\Journal;
/**
 * Default controller for the `staff` module
 */
class JournalController
{
    public function actionIndex()
    {
//        $model = new DynamicModel(['group']);
//        $model
//            ->addRule(['group'], 'required');
        $model = new Journal();

        if(Yii::$app->request->isPost){
            $model->load(Yii::$app->request->post());
            //$model->attributes = \Yii::$app->request->post('DynamicModel');
        }

        return [
            'view' => 'index',
            'model' => $model
        ];
    }
}


