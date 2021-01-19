<?php


namespace app\modules\sniffer\controllers;


use app\modules\sniffer\models\Logger;
use yii\data\ArrayDataProvider;
use yii\web\Controller;
class JournalController extends Controller
{
    public function actionIndex()
    {

        $dataProvider = new ArrayDataProvider([
            'allModels' => Logger::getAll(),
            'sort' => [
                'defaultOrder' => ['date' => SORT_DESC],
                'attributes' => [
                    'ip',
                    'date',
                    'userAgent'
                ],

            ],
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }
}