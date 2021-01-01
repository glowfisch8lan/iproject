<?php

namespace app\modules\metrica\controllers;

use Yii;


use app\modules\metrica\models\Parser;
use app\modules\metrica\models\Pattern;
use app\modules\metrica\models\job\ParserJob;
use app\modules\metrica\models\report\Report;

use app\modules\system\models\users\Users;

use yii\data\ArrayDataProvider;
use yii\helpers\Json;

class AnalyzeController extends \yii\web\Controller
{
    public function actionIndex()
    {


        $model =  new Parser();
        $pattern = new Pattern();
        $patterns = $pattern->getAllPatterns();

        $user = new Users();
        $report = new Report();

        /*
         * Обработка данных с формы;
         */
        if($model->load(Yii::$app->request->post())){

            $model->user = $user->getId();
            $model->date = date("Y-m-d");

            foreach($model->url as $url){$arr[] = [ 'date' => $model->date, 'url' => $url, 'user' => $model->user, 'patterns' => $model->patterns];}

            $result = $model->analyze($arr);
            $dataProvider = new ArrayDataProvider([
                'allModels' => $result
            ]);

            return $this->render('result', [
                'dataProvider' => $dataProvider
            ]);

        }

        return $this->render('index', [
            'model' => $model,
            'patterns' => $patterns,
            'report' => $report
        ]);
    }


    public function actionUploadTemplate(){

        $template = new Report();
        $report->uploadTemplate();

    }

    public function actionTest(){

      //Отправка задания в очередь
      $queue = Yii::$app->queue;
      $id = $queue->push(new ParserJob([
        'text' => 'test',
        'file' => Yii::$app->basePath . '/web/file.txt'
      ]));
      print_r($id);
    }

    public function actionJobStatus($id){

      return Json::encode(['status' => $status]);
    }
}
