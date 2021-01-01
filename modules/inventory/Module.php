<?php

namespace app\modules\inventory;

/**
 * inventory module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\inventory\controllers';

    public $name = "Инвентаризация";
    public $defaultController = 'inventory';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
    }
}
