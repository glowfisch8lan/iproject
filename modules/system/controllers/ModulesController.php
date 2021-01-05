<?php

namespace app\modules\system\controllers;
use app\modules\system\helpers\ArrayHelper;
use app\modules\system\models\interfaces\modules\Modules;
use yii\data\ArrayDataProvider;
use yii\web\ForbiddenHttpException;

class ModulesController extends \yii\web\Controller
{
    public function actionIndex()
    {


        $data = Modules::createArrayDataProvider();
        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'sort' => [
                'attributes' => ['id', 'name', 'status', 'description'],
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionRegister($id)
    {
        Modules::register($id);
        return $this->redirect(['index']);
    }
    public function actionUnregister($id)
    {
        if($id != 'system') {
            Modules::unregister($id);
            return $this->redirect(['index']);
        }
        throw new ForbiddenHttpException('Выгрузка системного модуля невозможна!');
    }
}
