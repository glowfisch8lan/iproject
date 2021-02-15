<?php

namespace app\modules\system\controllers;

use Yii;
use yii\base\DynamicModel;
use yii\web\Controller;
use app\modules\system\models\auth\LDAP;
use app\modules\system\models\settings\Settings;
/**
 * Default controller for the `system` module
 */
class AuthController extends Controller
{


    public function actionIndex()
    {

        $model = new DynamicModel(['name', 'value']);
        $model
            ->addRule(['name','value'], 'required', ['message' => 'Укажите параметр!']);

        return $this->render('index',
        ['model' => $model]
        );
    }
    public function actionSave()
    {
        var_dump(Yii::$app->request->post());
    }

}
