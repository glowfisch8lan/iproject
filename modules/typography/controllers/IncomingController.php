<?php

namespace app\modules\typography\controllers;

use Yii;
use app\modules\typography\models\Orders;
use app\modules\typography\models\OrdersSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\ServerErrorHttpException;
use app\modules\system\models\files\UploadManager;

/**
 * IncomingController implements the CRUD actions for TypographyOrders model.
 */
class IncomingController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all TypographyOrders models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OrdersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TypographyOrders model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TypographyOrders model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Orders();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing TypographyOrders model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing TypographyOrders model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TypographyOrders model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Orders the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Orders::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    /**
     * Переключение статуса заявки
     * @param integer $id
     * @return Messages the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function switchStatus($id, $status)
    {
        $model = $this->findModel($id);
        switch($status){
            case 'complete':
                $model->status = 1;
                break;
            case 'working':
                $model->status = 0;
                break;
            default:
                $model->status = null;
        }

        $model->position = null;

        if(!$model->save())
        {
            throw new ServerErrorHttpException('Ошибка при изменении статуса заявки!');
        }
        return true;
    }
    /**
     * Переключение статуса заявки в "отработано"
     * @param integer $id
     * @return Messages the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionComplete($id)
    {
        $this->switchStatus($id, 'complete');
        return $this->redirect(['index']);
        //return $this->refresh();
    }

    /**
     * Переключение статуса заявки в "отработано"
     * @param integer $id
     * @return Orders the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionWorking($id)
    {
        $this->switchStatus($id, 'working');
        return $this->redirect(['index']);
    }

    /**
     * Загрузка файла
     * @param integer $id
     * @return Orders the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionGetFile($uuid,$file)
    {
        $file = pathinfo($file);

        $f = \Yii::getAlias('@app') . '/data/'. $this->module->id . '/' . $uuid .'/'. $file['basename'];

        if (file_exists($f)) {
            return \Yii::$app->response->sendFile($f);
        }
        throw new \Exception('File not found');

    }
}