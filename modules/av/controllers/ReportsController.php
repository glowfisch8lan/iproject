<?php

namespace app\modules\av\controllers;

use yii\web\Controller;
use app\modules\av\models\students\reports\GradeSheet;

/**
 * Default controller for the `av` module
 */
class ReportsController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $report = new GradeSheet();
        $report->generate();
    }

}
