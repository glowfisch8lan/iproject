<?php

namespace app\modules\typography\controllers;

use Yii;
use app\modules\typography\models\Orders;
use yii\web\Controller;
use yii\web\Response;

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
        $model->scenario = 'guest_feedback';

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $model->sender = $model->unitSender . ':' . $model->sender;
            if($model->save()){
                Yii::$app->session->setFlash('id', $model->id);
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
            }
        }

        return $this->render('index', [
            'model' => $model,
        ]);
    }
}
