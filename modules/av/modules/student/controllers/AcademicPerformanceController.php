<?php


namespace app\modules\av\modules\student\controllers;

use Yii;
use app\modules\av\modules\student\models\plugins\AcademicPerformance;
/**
 * Default controller for the `staff` module
 */
class AcademicPerformanceController
{
    public function actionIndex()
    {
        $model = new AcademicPerformance();
        return [
            'view' => 'index',
            'model' => $model
        ];
    }
    public function actionGetGradeSheet()
    {
        $model = new AcademicPerformance();

        if(Yii::$app->request->isPost){
            $model->load(Yii::$app->request->post());
        }
        $model->fetchDataGradeSheet();


        return [
            'view' => 'grade-sheet',
            'model' => $model
        ];
    }
}


