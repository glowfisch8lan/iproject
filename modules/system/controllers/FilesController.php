<?php

namespace app\modules\system\controllers;

use Yii;

use yii\filters\VerbFilter;
use yii\web\Controller;



/**
 * ManagerController implements the CRUD actions for User model.
 */
class FilesController extends Controller
{

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
            ],
        ];
    }

    public function actionIndex()
    {

    }

    public function actionGetFile()
    {

    }

}
