<?php

namespace app\modules\av\controllers;

use yii\web\Controller;
use yii\data\ArrayDataProvider;
use app\modules\av\models\Reports;

/**
 * Reports controller for the `av` module
 */
class ReportsController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {

        //var_dump(Reports::getList());
        $reports = [
            [
                'id' => 1,
                'name' => 'Ведомость успеваемости',
                'module' => 'Студент',
            ]
        ];
        $dataProvider = new ArrayDataProvider([
            'allModels' => $reports,
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
        return $this->render('/reports/index', [
            'dataProvider' => $dataProvider,
        ]);
    }

}
