<?php

namespace app\modules\system\controllers;


use Yii;

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
        $settings = null;
        $req = Yii::$app->request->post();
        unset($req['_csrf']);

        $settings = new Settings();
        $settings->formSettings($req);
        foreach($settings->settings as $name => $value)
            Settings::setValue($name,$value);

        return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
    }
}
