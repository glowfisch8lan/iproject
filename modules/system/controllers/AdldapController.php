<?php

namespace app\modules\system\controllers;

use Yii;
use yii\web\Controller;
use app\modules\system\models\auth\LDAP;

/**
 * Default controller for the `system` module
 */
class AdldapController extends Controller
{


    public function actionIndex()
    {
        $ad = new LDAP();
        $ad->addGroup();
//        return $this->render('index');
    }

    public function actionSyncGroup()
    {
        $ad = new LDAP();
        $ad->syncGroups();
//        return $this->render('index');
    }
}
