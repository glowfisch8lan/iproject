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
use yii\helpers\Url;
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
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        return $this->render('/plugins/index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Оптимизирующая функция для двух действий Load и Ajax
     *
     * @param $module
     * @param $id
     * @param $action
     * @param $controller
     * @return array
     * @throws NotFoundHttpException
     */
    private function initLoad($module,$id,$action,$controller){

        $cl = 'app\modules\av\modules\\'.$module.'\controllers\\'.ucfirst($id).'Controller';

        if(!class_exists($cl))
            throw new NotFoundHttpException('Извините, ошибка в переданных параметрах!');


        $class = new $cl();

        $actionController = 'action'.ucfirst($action);

        if(!method_exists($class, $actionController))
            throw new NotFoundHttpException('Извините, метод action не найден!');

        $array = $class->$actionController();


        $id = preg_split('/(?<=[a-z])(?=[A-Z])/u',$id);
        $id = (count($id) > 1 ) ? mb_strtolower($id[0].'-'.$id[1]) : $id[0];
        $path = '@app/modules/av/modules/'.$module.'/views/'.$id.'/'.$array['view'];

        if($action == 'index')
            Yii::$app->session->set('home', Url::current([], true));

        return ['path' => $path, 'array' => $array];
    }

    /**
     * Загрузка подконтроллера подмодуля
     *
     * @param $module
     * @param $id
     * @param string $action | default = 'index'
     * @param $controller
     * @return string
     * @throws NotFoundHttpException
     */
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

    /**
     *  Генерация отчета из HTML => XLS посредством PHPSPREADSHEET;
     */
    public function actionReports()
    {
        $model = new Reports();
        $load = $model->load(
            [
                'Reports' => [
                    'filename' => Yii::$app->request->post('filename'),
                    'html' => Yii::$app->request->post('h')
                ]]);

        if($load)
            $model->generate();

        throw new ServerErrorHttpException('Ошибка формирования отчета');

    }

}
