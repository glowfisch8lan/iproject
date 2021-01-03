<?php

namespace app\modules\system\controllers;

use Yii;

use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use app\modules\system\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use app\modules\system\models\users\Groups;
use app\modules\system\models\users\Users;
use app\modules\system\models\users\UsersSearch;
use app\modules\system\models\rbac\AccessControl;




/**
 * ManagerController implements the CRUD actions for User model.
 */
class UsersController extends Controller
{

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

    public function actionIndex()
    {
        {
            $searchModel = new UsersSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
    }

    public function actionCreate()
    {
            $model = new Users();

            if ( $model->load(Yii::$app->request->post()) && $model->validate() && $model->save()) {

                Groups::addMembers(ArrayHelper::indexMap($model->groups, $model->id)); //Добавляем список групп в system_users_groups заново;
                return $this->redirect(['index']);

            }
            return $this->render('create', [
                'model' => $model,
            ]);

    }

    public function actionUpdate($id)
    {

        $model = $this->findModel($id);
        $model->setAttribute('password', null);

        if ( $model->load(Yii::$app->request->post()) && $model->validate() && $model->save()) {

                Groups::removeAllGroupMember($id); //Удаляем все группы пользователя;
                Groups::addMembers(ArrayHelper::indexMap($model->groups, $model->id)); //Добавляем список групп в system_users_groups заново;

                return $this->redirect(['index']);

        }

        return $this->render('update', [
            'model' => $model
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {

        if (($model = Users::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionProfile()
    {
    }
}
