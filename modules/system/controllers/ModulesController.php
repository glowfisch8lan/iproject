<?php

namespace app\modules\system\controllers;
use app\modules\system\models\interfaces\modules\Module;


class ModulesController extends \yii\web\Controller
{
    public function actionIndex()
    {

        var_dump(Module::getListModules($filter = false));
        return $this->render('index');
    }

}
