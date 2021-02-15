<?php


namespace app\modules\av\modules\student\controllers;


use Yii;
use yii\web\NotFoundHttpException;
use yii\base\DynamicModel;
use app\modules\av\modules\student\models\plugins\Journal;
use app\modules\av\modules\student\models\plugins\AcademicPerformance;
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

    public function actionJournal()
    {

        $model = new Journal();
        $model->academicPerformance = new AcademicPerformance();
        if(Yii::$app->request->isPost){
            $model->load(Yii::$app->request->post());
        }

        return [
            'view' => 'journal',
            'model' => $model
        ];
    }
}


