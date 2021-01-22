<?php


namespace app\modules\tools\controllers;

use yii\web\Controller;
use Yii;
use yii\httpclient\Client;
use app\modules\system\models\users\Users;
class TestController extends Controller
{
    public function beforeAction($action)
    {
        if ($action->id == 'index') {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
        return false;
    }
    public function actionIndex(){

//        $token = '7c70f687-c3c5-4d9e-8739-25a54339661d';
//        $url = 'https://av.dvuimvd.ru/api/call/system-database/get?token=' . $token;
//        $client = new Client();
//        $response = $client->createRequest()
//            ->setMethod('post')
//            ->setUrl($url)
//            ->setData(['table' => 'system_users', 'filter' => ['login' => 'grigorov_de']])
//            ->send();


        $url = 'http://av.dvuimvd.ru/ajax-login';
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('post')
            ->setUrl($url)
            ->setData(['username' => 'grigorov_de', 'password' => '18954569'])
            ->send();
        var_dump($response);



    }
    public function actionTest(){


        return $this->render('test');

    }
}