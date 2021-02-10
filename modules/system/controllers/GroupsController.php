<?php

namespace app\modules\system\controllers;

use Yii;

use app\modules\system\models\users\Groups;
use app\modules\system\models\users\Users;
use app\modules\system\models\interfaces\modules\Modules;
use app\modules\system\models\rbac\AccessControl;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\filters\VerbFilter;

class GroupsController extends Controller
{


    public function beforeAction($action)
    {
        return parent::beforeAction($action);
    }

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
        $dataProvider = new ActiveDataProvider([
            'query' => Groups::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {

       // $model = new Group();
        $user  = new Users();

        $model = $this->findModel($id);

        if ( $model->load(Yii::$app->request->post()) && $model->validate()) {
           switch($model->action){
               case 'add':
                   foreach($model->listMembers as $user_id) {
                       $dataArray[] = array($user_id, $model->id);
                   }
                    $model->addMembers($dataArray);
                   break;
           }

           return $this->redirect(Yii::$app->request->referrer); //Если все хорошо - возвращаемся на предыдущую страницу

        } else {


        }


        $model->listUsers = array_diff(ArrayHelper::map($user->getUsersList(), 'id','login'), ArrayHelper::map($model->getGroupMembers($id), 'id','login'));


        $dataProvider = new ArrayDataProvider([
            'allModels' => $model->getGroupMembers($id),
            'sort' => [
                'attributes' => ['login', 'name', 'group'],
            ],
        ]);

        return $this->render('view', [
            'model' => $model,
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionDeleteGroupMembers($user_id,$group_id)
    {
       Groups::removeGroupMember($user_id, $group_id);

       return $this->redirect(Yii::$app->request->referrer); //Если все хорошо - возвращаемся на предыдущую страницу
    }

    public function actionCreate()
    {
        $model = new Groups();
        $model->permissions = Modules::getAllPermissions();

        if(  $model->load(Yii::$app->request->post()) && $model->validate() ){

            $model->permissions = Json::encode($model->permissions);


            if( !$model->save() ){
                throw ServerErrorHttpException('Ошибка при сохранении Роли!');

            }

            return $this->redirect('index');
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->usedPermissions = Json::Decode(Groups::getPermissions($id)[0]['permissions']);


        if( $model->load(Yii::$app->request->post()) && $model->validate() ){

            $model->permissions = Json::encode($model->permissions); //в таблице system_users храним все разрешения группы в json

            if( !$model->save() ){
                throw ServerErrorHttpException('Ошибка при сохранении Группы!');
            }
            return $this->redirect('index');
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {

        $model = new Groups();

        ($id == 1) ? function() use($model){throw new \yii\base\Exception( "Удалить группу ' . $model->name . ' невозможно!" );} : false;

        if(!$model->getGroupMembers($id)){
            $this->findModel($id)->delete();
            return $this->redirect(Yii::$app->request->referrer); //Если все хорошо - возвращаемся на предыдущую страницу
        }
        else{
            throw new ForbiddenHttpException( "В группе есть пользователи! Удалить нельзя" );
            //return $this->redirect(['index']);
        }

    }

    protected function findModel($id)
    {
        if (($model = Groups::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
