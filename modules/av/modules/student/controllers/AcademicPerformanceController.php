<?php


namespace app\modules\av\modules\student\controllers;

use Yii;
use app\modules\av\modules\student\models\plugins\AcademicPerformance;
use yii\web\NotFoundHttpException;

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

        $model->fetchData();

        if(!$model->students)
            throw new NotFoundHttpException('В группе нет учащихся!');

        return [
            'view' => 'grade-sheet',
            'model' => $model
        ];
    }
    /*
        * Оптимизирующая функция для двух действий Load и Ajax
        */
    private function initLoad($module,$id,$action,$controller){

        $class = 'app\modules\av\modules\\'.$module.'\controllers\\'.ucfirst($id).'Controller';

        if(!class_exists($class))
            throw new NotFoundHttpException('Извините, ошибка в переданных параметрах!');


        $class = new $class();

        $actionController = 'action'.ucfirst($action);

        if(!method_exists($class, $actionController))
            throw new NotFoundHttpException('Извините, метод action не найден!');

        $array = $class->$actionController();


        $id = preg_split('/(?<=[a-z])(?=[A-Z])/u',$id);
        $id = (count($id) > 1 ) ? mb_strtolower($id[0].'-'.$id[1]) : $id[0];
        $path = '@app/modules/av/modules/'.$module.'/views/'.$id.'/'.$array['view'];

        return ['path' => $path, 'array' => $array];
    }

    public function actionGenerateReport()
    {

        $model = new AcademicPerformance();
        if(Yii::$app->request->isPost){
            $model->load(Yii::$app->request->post());
        }


//        $namespace = str_replace('\controllers','',__NAMESPACE__ );
//        $class = $namespace . '\models\plugins\reports\\'.$model->report . '\Report';
//        $class = new $class();
//
//        var_dump($class);
        //$model->fetchData();
        //$model->generateGradeSheet();
        die();
        if(!$model->students)
            throw new NotFoundHttpException('В группе нет учащихся!');

        return [
            'view' => 'grade-sheet',
            'model' => $model
        ];
    }
}


