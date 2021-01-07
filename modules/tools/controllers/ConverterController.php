<?php

namespace app\modules\tools\controllers;


use Yii;
use yii\web\Controller;
use yii\web\UploadedFile;
use app\modules\tools\models\UploadForm;
use app\modules\system\helpers\Inflector;
use app\modules\tools\models\ConvertCSV;

/**
 * Default controller for the `tools` module
 */
class ConverterController extends Controller
{

    /**
     * Рендерит представление для "генератора-логинов"
     * @return string
     */
    public function actionIndex()
    {
        $model = new UploadForm();

        return $this->render('index', [
            'model' => $model
        ]);
    }


    public function action276a2c57cc79ecfe8e88623411a5c1c5(){
        $model = new UploadForm();
        $convertManager = new ConvertCSV();

        if (Yii::$app->request->isPost) {
            $model->file = UploadedFile::getInstance($model, 'file');
            $convertManager->init($model->file, 'convertUsername');
        }



    }
    /**
     *  Загрузка файла на сервер
     * @return string
     */
    protected function actionUpload()
    {
        $model = new UploadForm();

        if (Yii::$app->request->isPost) {
            $model->file = UploadedFile::getInstance($model, 'file');
            $model->upload();
        }
        return $this->redirect(['index']);
    }
}
