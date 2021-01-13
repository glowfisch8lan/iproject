<?php

namespace app\modules\feedback\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;

/**
 * PrintOnDemand controller for the `feedback` module
 */
class PrintOnDemandController extends Controller
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

    //public $layout = '/main';

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionIndex()
    {

    }
}
