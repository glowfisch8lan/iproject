<?php

namespace app\modules\system\controllers;

use app\modules\system\models\users\Groups;
use Yii;
use yii\web\Controller;
use app\modules\system\models\auth\LDAP;

/**
 * Default controller for the `system` module
 */
class LdapController extends Controller
{


    public function actionIndex()
    {


        //$ad->addGroup();
//        return $this->render('index');
    }

    public function actionSyncGroup()
    {

//        return $this->render('index');
    }
}
