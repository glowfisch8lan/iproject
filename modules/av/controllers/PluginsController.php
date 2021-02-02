<?php

namespace app\modules\av\controllers;

use yii\db\Exception;
use yii\web\Controller;
use Yii;
use yii\data\ArrayDataProvider;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

/**
 * Reports controller for the `av` module
 */
class PluginsController extends Controller
{

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {

        //var_dump(Reports::getList());
        $plugins = [
            [
                'id' => 'academicPerformance',
                'name' => 'Успеваемость',
                'module' => [
                    'id' => 'student',
                    'name' => 'Студент',
                ],
                'category' => 'plugins',
                'controller' => 'academicPerformance'
            ],
            [
                'id' => 'journal',
                'name' => 'Электронный журнал',
                'module' => [
                    'id' => 'student',
                    'name' => 'Студент',
                ],
                'category' => 'plugins',
                'controller' => 'journal'
            ]
        ];

        $dataProvider = new ArrayDataProvider([
            'allModels' => $plugins,
//            'sort' => [
//                'defaultOrder' => ['date' => SORT_DESC],
//                'attributes' => [
//                    'ip',
//                    'date',
//                    'userAgent'
//                ],
//            ],
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        return $this->render('/plugins/index', [
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionLoad($module, $id, $action = 'index', $controller)
    {

        $class = 'app\modules\av\modules\\'.$module.'\controllers\\'.ucfirst($id).'Controller';
        $class = new $class();
        $actionController = 'action'.ucfirst($action);


        $array = $class->$actionController();

        $id = preg_split('/(?<=[a-z])(?=[A-Z])/u',$id);
        $id = (count($id) > 1 ) ? mb_strtolower($id[0].'-'.$id[1]) : $id[0];
        $path = '@app/modules/av/modules/'.$module.'/views/'.$id.'/'.$array['view'];



        if(!file_exists(realpath(Yii::getAlias($path).'.php')))
            throw new NotFoundHttpException('Файл не найден');

        return $this->render($path, [
            'model' => $array['model'],
            'ajax' => false
        ]);
    }

    public function actionAjax($module, $id, $action = 'index', $controller)
    {
        $class = 'app\modules\av\modules\\'.$module.'\controllers\\'.$id.'Controller';

        $class = new $class();
        $actionController = 'action'.ucfirst($action);

        $array = $class->$actionController();

        $id = preg_split('/(?<=[a-z])(?=[A-Z])/u',$id);
        $id = (count($id) > 1 ) ? mb_strtolower($id[0].'-'.$id[1]) : $id[0];
        $path = '@app/modules/av/modules/'.$module.'/views/'.$id.'/'.$array['view'];

        $this->layout = "@app/modules/system/views/layouts/empty";

        if(!file_exists(realpath(Yii::getAlias($path).'.php')))
            throw new NotFoundHttpException('Файл не найден');



        return $this->render($path, [
                'model' => $array['model'],
                'ajax' => true]);
    }

    public function actionLti()
    {


        //$module, $id, $action = 'index', $controller

        if(Yii::$app->request->isPost){
            echo '1';
            var_dump(Yii::$app->request->post());
        }
        die();
        $class = 'app\modules\av\modules\\'.$module.'\controllers\\'.$id.'Controller';

        $class = new $class();
        $actionController = 'action'.ucfirst($action);

        $array = $class->$actionController();

        $id = preg_split('/(?<=[a-z])(?=[A-Z])/u',$id);
        $id = (count($id) > 1 ) ? mb_strtolower($id[0].'-'.$id[1]) : $id[0];
        $path = '@app/modules/av/modules/'.$module.'/views/'.$id.'/'.$array['view'];

        $this->layout = "@app/modules/system/views/layouts/empty";

        if(!file_exists(realpath(Yii::getAlias($path).'.php')))
            throw new NotFoundHttpException('Файл не найден');



        return $this->render($path, [
            'model' => $array['model'],
            'ajax' => true]);
    }
}
