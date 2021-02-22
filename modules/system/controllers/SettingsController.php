<?php

namespace app\modules\system\controllers;


use Yii;

use yii\widgets\ActiveForm;
use yii\web\Response;
use yii\db\Exception;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use app\modules\system\models\users\Groups;
use app\modules\system\models\users\Users;
use app\modules\system\models\rbac\AccessControl;
use app\modules\system\models\settings\Settings;

/**
 * Admin controller for the `user` module
 */

class SettingsController extends Controller
{

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionSave()
    {
        $model = new Settings();
        $req = Yii::$app->request->post();
        $model->formSettings($req);
        unset($req['_csrf']);

        foreach($model->settings as $name => $value)
        {
            $model->name = $name;
            $model->value = $value;
            if($model->validate())
                Settings::setValue($name,$value);
        }

        //return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
    }

    public function actionValidateForm()
    {
        $model = new Settings();
        $req = Yii::$app->request->post();

        unset($req['_csrf']);
        unset($req['ajax']);
        if (Yii::$app->request->isAjax) {
            $model->formSettings($req);
            foreach($model->settings as $name => $value)
            {
                $model->name = $name;
                $model->value = $value;
                Yii::$app->response->format = Response::FORMAT_JSON;

                if(!$model->validate())
                    $arr[] =  ActiveForm::validate($model);


            }
            if(!empty($arr))
                return $arr;

            return true;
        }
        throw new \yii\web\BadRequestHttpException('Bad request!');
    }
}
