<?php

namespace app\modules\typography\controllers;

use Yii;
use app\modules\typography\models\Orders;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\base\DynamicModel;
use app\modules\system\models\files\UploadManager;
/**
 * Default controller for the `typography` module
 */
class DefaultController extends Controller
{

    public function actions()
    {
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_DEBUG ? 'test' : null,
                'foreColor' => 0xFE980F, // цвет символов
                'minLength' => 2, // минимальное количество символов
                'maxLength' => 4, // максимальное
                'offset' => 5, // расстояние между символами (можно отрицательное)
            ],
        ];
    }

    public $layout = '/main';

    /**
     * Displays contact page.
     *
     * @return Response|string
     */

    public function actionIndex()
    {
        $model = new Orders();
        $model->scenario = 'guest';

        /* Действия при загрузке */
       if ($model->load(Yii::$app->request->post())) {

           $uploadManager = new UploadManager();
           $uploadManager->file = UploadManager::getInstance($model, 'file');
           $uploadManager->upload();

       //    $files->upload


//
//            $model->sender = $model->unitSender . ':' . $model->sender;
//            if($model->save()){
//                Yii::$app->session->setFlash('id', $model->id);
//            Yii::$app->session->setFlash('contactFormSubmitted');
//
//            return $this->refresh();
//            }
        }

        return $this->render('index', [
            'model' => $model,
        ]);
    }

    public function actionStatus(){
        $model = new DynamicModel(['id', 'verifyCode', ]);
        $model
            ->addRule('id', 'required', ['message' => 'Пожалуйста, заполните номер заявки'])
            ->addRule('verifyCode', 'required', ['message' => 'Введите проверочный код']);

        if( $model->load(Yii::$app->request->post()) && $model->validate() ){
            $order = $this->findModel($model->id);
            if(!$order){
                throw new NotFoundHttpException('Извините, данной заявки нет в базе!');
            }
            Yii::$app->session->setFlash('statusFormSubmitted');
            Yii::$app->session->setFlash('status', $order->status);
            return $this->refresh();
        }
        return $this->render('status', [
            'model' => $model,
        ]);
    }

    protected function findModel($id)
    {
        if (($model = Orders::findOne($id)) !== null) {
            return $model;
        }
        return false;
    }
}
