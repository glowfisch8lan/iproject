<?php

namespace app\modules\metrica\controllers;

use Yii;

use app\modules\metrica\models\Pattern;
use app\modules\system\models\settings\Settings;

use yii\web\UploadedFile;
use yii\web\Controller;

class SettingsController extends Controller
{
    public function actionIndex()
    {

        Settings::setValue('metrica.template.path','file');
        Settings::setValue('metrica.template.file','file');
        return $this->render('index', [
            'SettingsMenuItems' => \Yii::$app->controller->module->SettingsMenuItems
        ]);
    }

    /*
     * Настройки "Паттерны"
     */
    public function actionPatternCreate()
    {

        $model = new Pattern();
        $model->user = Yii::$app->user->id;

        if ( $model->load(Yii::$app->request->post()) && $model->validate()) {

            if ($model->save()) {

                return $this->redirect(['index']);
            }

        }

        return $this->render('update', [
            'model' => $model
        ]);
    }

    public function actionPatternUpdate($id)
    {
       // $model = new Pattern();
        $model = $this->findModel($id);

        if ( $model->load(Yii::$app->request->post()) && $model->validate()) {

            if ($model->save()) {

                return $this->redirect(['index']);
            }

        }

        return $this->render('pattern/update', [
            'model' => $model
        ]);
    }

    public function actionPatternDelete($id)
    {

        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /*
     * Вспомогательные Actions
     */

    public function actionUpload()
    {
        $model = new Settings();

        if (Yii::$app->request->isPost) {
            $model->template = UploadedFile::getInstance($model, 'template');
            if ($model->upload()) {
               echo 'success!';
            }
        }
    }

    protected function findModel($id)
    {

        if (($model = Pattern::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
