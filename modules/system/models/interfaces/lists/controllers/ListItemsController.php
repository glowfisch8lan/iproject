<?php


namespace app\modules\system\models\interfaces\lists\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ActiveDataProvider;

use app\modules\system\models\interfaces\lists\models\Lists;
use app\modules\system\models\interfaces\lists\models\ListItems;

/**
 * Список справочников
 */

/**
 * Class ListsController
 *
 * @package app\modules\system\models\interfaces
 *
 * Управление справочниками.
 */

class ListItemsController extends Controller
{
    /**
     * Список справочников и запросов для их загрузки.
     *
     * @return mixed
     */

    public function init() {
        parent::init();
    }

    public function actionIndex($id)
    {
        $lists = $this->newModel('Lists');
        ListItems::useTable( $lists->getTableName($id) );

        $model = $this->newModel('ListItems');
        $model->parent = $lists->findOne($id);

        $query = $model->find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        return $this->render('@systemViewPath/lists/list-items/index',
            [
                'dataProvider' => $dataProvider,
                'model' => $model
            ]
        );
    }

    public function actionCreate()
    {

            if (!empty(Yii::$app->request->post('parent_id')) && Yii::$app->request->isPost) {

                $parent_id = Yii::$app->request->post('parent_id');
                $lists = $this->newModel('Lists');
                ListItems::useTable( $lists->getTableName($parent_id) );

                $model = $this->newModel('ListItems');
                $model->parent_id = $parent_id;
                $model->parent = $lists->findOne($parent_id);

                return $this->render('@systemViewPath/lists/list-items/create', ['model' => $model]);

            }
            if(Yii::$app->request->isPost){

                $lists = $this->newModel('Lists');
                $parent_id = Yii::$app->request->post()['ListItems']['parent_id'];
                ListItems::useTable( $lists->getTableName($parent_id) ); // указываем, какую таблицу подгружать;

                $model = $this->newModel('ListItems');

                if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->save()) {
                        return $this->redirect(['//'.$this->module->id.'/list-items?id='.$parent_id]);
                }
            }
    }

    public function actionDelete($id)
    {

      if (!empty(Yii::$app->request->post('parent_id')) && Yii::$app->request->isPost) {

          $parent_id = Yii::$app->request->post('parent_id');
          $lists = $this->newModel('Lists');

          ListItems::useTable( $lists->getTableName($parent_id) );

          $model = $this->newModel('ListItems');
          $model = $model->findOne($id);

          if($model != null) {
              $model->delete();
              return $this->redirect(['//'.$this->module->id.'/list-items?id='.$parent_id]);
            }

      }


    }

    public function actionUpdate($id){

      $save = null;
      /* Открываем страницу редактирования */
      if (Yii::$app->request->isPost) {


          $request = Yii::$app->request->post();

          $parent_id = (empty($request['parent_id']) && !empty($request['ListItems']['parent_id'])) ? $request['ListItems']['parent_id'] : $request['parent_id'];
          $save = (empty($request['parent_id']) && !empty($request['ListItems']['parent_id'])) ? true : false;


          $lists = $this->newModel('Lists');
          ListItems::useTable( $lists->getTableName($parent_id) );

          $model = $this->newModel('ListItems');


          $model = $model->findOne($id); //загружаем модель;
          $model->parent_id = $parent_id;

          $model->parent = $lists->findOne($parent_id);

          if( !($save) ){
              return $this->render('@systemViewPath/lists/list-items/update',[
                  'model' => $model
              ]);
          }

          if( $save && $model->load(Yii::$app->request->post()) && $model->validate() && $model->save()){
                    return $this->redirect(['//'.$this->module->id.'/list-items?id='.$parent_id]);
          }

      }

    }

}
