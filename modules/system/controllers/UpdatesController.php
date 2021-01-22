<?php

namespace app\modules\system\controllers;

use app\modules\system\models\files\UploadManager;
use Yii;
use yii\base\DynamicModel;
use yii\web\Controller;
use yii\web\UploadedFile;
use app\modules\system\models\updates\PatchManager;


/**
 * Updates controller for the `system` module
 */
class UpdatesController extends Controller
{


    public function actionIndex()
    {
        $model = new DynamicModel(['file', ]);
        $model
            ->addRule('file', 'safe');
//            ->addRule('verifyCode', 'required', ['message' => 'Введите проверочный код']);

        return $this->render('/updates/index', ['model' => $model]);
    }

    /**
     * Загрузка и установка патча.
     */
    public function actionPatch()
    {
        $model = new DynamicModel(['file', ]);
        $model
            ->addRule( 'file', 'file', ['extensions' => ['zip'], 'maxSize' => 1024 * 1024 * 0.5]);
        $model->load(Yii::$app->request->post());


        $patch = new UploadManager();
        $patch->file = UploadManager::getInstance($model, 'file');

        if (pathinfo($patch->file, PATHINFO_EXTENSION) != 'zip')
            throw new \Exception('Файл патча должен иметь расширение ZIP.');

        if (!file_exists(PatchManager::$installDirectory))
            mkdir(Yii::getAlias(PatchManager::$installDirectory), 0777, true );

        $patch->file->saveAs(Yii::getAlias(PatchManager::$installDirectory) . 'updates.zip');

        $pathManager = PatchManager::getInstance();
        


//
//
//        if ($updateFile instanceof UploadedFile) {
//            if (pathinfo($updateFile->name, PATHINFO_EXTENSION) != 'zip')
//                throw new \Exception('Файл патча должен иметь расширение ZIP.');
//
//            if (!file_exists(Manager::$installDirectory))
//                mkdir(Yii::getAlias(Manager::$installDirectory));
//
//            $updateFile->saveAs(Yii::getAlias(Manager::$installDirectory) . '/' . Manager::$patchFile);
//            //Manager::getInstance()->installPatch();
//
//            return $this->redirect(['index']);
//        }
//
//        $this->redirect(['index']);
    }
}
