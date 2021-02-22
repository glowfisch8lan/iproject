<?php


namespace app\modules\system\models\cache;

use Yii;
use app\modules\system\models\settings\Settings;
use Exception;

trait Cache
{


    protected static $_instance;
    private $duration = 1200;
    private $_status;

    public static function cache() {
        if (self::$_instance === null) {
            self::$_instance = new self;
        }

        self::$_instance->_status = false;
        if(filter_var(Settings::getValue('system.cache.status'), FILTER_VALIDATE_BOOLEAN))
            self::$_instance->_status = true;


        return self::$_instance;
    }

    private function set($id, $data)
    {
        if($this->_status)
            return $this->cache->set($id, $data, $this->duration);

        return false;

    }

    private function get($id)
    {
        if($this->_status)
            return $this->cache->get($id);

        return false;

    }

}