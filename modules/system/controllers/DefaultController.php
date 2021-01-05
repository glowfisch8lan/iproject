<?php

namespace app\modules\system\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use app\modules\system\models\users\LoginForm;
use app\modules\system\models\users\Users;

/**
 * Default controller for the `system` module
 */
class DefaultController extends Controller
{


    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction'
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],

        ];
    }

    public function beforeAction($action)
    {
        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        return $this->render('index');
    }
    public function actionProfile()
    {

        return $this->render('profile');
    }
    public function actionLogin()
    {
        $this->layout = 'login';

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('/account/login', [
                'model' => $model,
            ]

        );
    }
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

}
