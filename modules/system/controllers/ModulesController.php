<?php

namespace app\modules\system\controllers;
use app\modules\system\helpers\ArrayHelper;
use app\modules\system\models\interfaces\modules\Module;
use yii\data\ArrayDataProvider;

class ModulesController extends \yii\web\Controller
{
    public function actionIndex()
    {


        $data = Module::createArrayDataProvider();
        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'sort' => [
                'attributes' => ['id', 'name', 'status'],
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionRegister($id)
    {
        Module::register($id);
        return $this->redirect(['index']);
    }
    public function actionUnregister($id)
    {
        Module::unregister($id);
        return $this->redirect(['index']);
    }
}
