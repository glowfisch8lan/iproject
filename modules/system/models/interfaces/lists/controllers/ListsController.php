<?php


namespace app\modules\system\models\interfaces\lists\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use app\modules\system\models\interfaces\lists\models\Lists;
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

class ListsController extends Controller
{
    /**
     * Список справочников и запросов для их загрузки.
     *
     * @return mixed
     */


    public function init() {
        parent::init();
    }

    public function actionIndex()
    {
            $model = $this->newModel('Lists');
            $dataProvider = new ActiveDataProvider([
                'query' => $model->find(),
            ]);

            return $this->render('@systemViewPath/lists/lists/index',
                [
                    'dataProvider' => $dataProvider,
                    'model' => $model
                ]
            );
    }

}
