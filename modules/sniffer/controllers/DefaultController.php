<?php

namespace app\modules\sniffer\controllers;

use Yii;
use yii\web\Controller;
use app\modules\sniffer\models\Logger;

/**
 * Default controller for the 'sniffer' module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {

        $logger = new Logger();
        $logger->write([
            ['ip' => Yii::$app->request->userIP,
            'date' => date('H:i:s d-m-Y'),
            'userAgent' => Yii::$app->request->userAgent]
        ]);
        $logger->getAll();
        return $this->renderPartial('index');
    }
    public function actionJournal()
    {
        $data = Logger::getAll();
        var_dump($data);
        return $this->renderPartial('status');
    }

}
