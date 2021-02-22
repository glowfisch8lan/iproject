<?php

namespace app\modules\system\controllers;

use Yii;
use app\modules\system\helpers\ArrayHelper;
use app\modules\system\models\interfaces\modules\Modules;
use yii\data\ArrayDataProvider;
use yii\httpclient\Exception;
use yii\web\ForbiddenHttpException;
use yii\web\ServerErrorHttpException;

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
        if(!Modules::register($id))
            throw new ServerErrorHttpException('Ошибка при регистрации модуля.');


        Yii::$app->session->setFlash('moduleStatus', true);
        Yii::$app->session->setFlash('moduleRegister', true);

        return $this->redirect(['index']);
    }
    public function actionUnregister($id)
    {
        if($id != 'system') {
            if(!Modules::unregister($id))
                throw new ServerErrorHttpException('Ошибка при выключении модуля.');
            Yii::$app->session->setFlash('moduleStatus', true);
            Yii::$app->session->setFlash('moduleRegister', false);
            return $this->redirect(['index']);
        }
        throw new ForbiddenHttpException('Выгрузка системного модуля невозможна!');
    }
}
