<?php

namespace app\modules\system\controllers;


use Yii;

use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use app\modules\system\models\users\Groups;
use app\modules\system\models\users\Users;
use app\modules\system\models\rbac\AccessControl;


/**
 * Admin controller for the `user` module
 */
class SettingsController extends Controller
{

    public function beforeAction($action)
    {
        $user = new Users();
        if(!(AccessControl::checkAccess($user->getId(),'viewSettings'))) {throw new ForbiddenHttpException('Access deny!');}
        return parent::beforeAction($action);
    }

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ]

        ];
    }

    public function actionIndex()
    {
        return $this->render('settings');
    }
}
