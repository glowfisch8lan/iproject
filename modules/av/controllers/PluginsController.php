<?php

namespace app\modules\av\controllers;

use yii\db\Exception;
use yii\web\Controller;
use Yii;
use yii\data\ArrayDataProvider;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use app\modules\av\models\Reports;
use app\modules\av\modules\student\Module;
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
        $modules = new \app\modules\av\modules\student\Module();
        $plugins = [];
        foreach ($modules->plugins as $id => $plugin)
        {
            if($plugin['visible']){
                $plugins[] = $plugin;
            }

        }
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

    /*
     * Оптимизирующая функция для двух действий Load и Ajax
     */
    private function initLoad($module,$id,$action,$controller){

        $class = 'app\modules\av\modules\\'.$module.'\controllers\\'.ucfirst($id).'Controller';

        if(!class_exists($class))
            throw new NotFoundHttpException('Извините, ошибка в переданных параметрах!');


        $class = new $class();

        $actionController = 'action'.ucfirst($action);

        if(!method_exists($class, $actionController))
            throw new NotFoundHttpException('Извините, метод action не найден!');

        $array = $class->$actionController();


        $id = preg_split('/(?<=[a-z])(?=[A-Z])/u',$id);
        $id = (count($id) > 1 ) ? mb_strtolower($id[0].'-'.$id[1]) : $id[0];
        $path = '@app/modules/av/modules/'.$module.'/views/'.$id.'/'.$array['view'];

        return ['path' => $path, 'array' => $array];
    }

    public function actionLoad($module, $id, $action = 'index', $controller)
    {

        $action = $this->initLoad($module,$id,$action,$controller);


        if(!file_exists(realpath(Yii::getAlias($action['path']).'.php')))
            throw new NotFoundHttpException('Файл не найден');

        return $this->render($action['path'], [
            'model' => $action['array']['model'],
            'ajax' => false
        ]);
    }

    public function actionReport()
    {
        $reports = new Reports();
        $
        $reports->generate();
    }

    public function actionAjax($module, $id, $action = 'index', $controller)
    {
        $action = $this->initLoad($module,$id,$action,$controller);

        $this->layout = "@app/modules/system/views/layouts/empty";


        if(!file_exists(realpath(Yii::getAlias($action['path']).'.php')))
            throw new NotFoundHttpException('Файл не найден');

        return $this->render($action['path'], [
                'model' => $action['array']['model'],
                'ajax' => true]);
    }


}
