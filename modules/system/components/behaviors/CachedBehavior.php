<?php
namespace app\modules\system\components\behaviors;

use Yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;

/**
 * Поведение, очищающее кеш при записи в базу Данных. В web.php для группировки настроено несколько кешей, например, cacheUsers и cacheGroups;
 *
 * Class CachedBehavior
 * @package app\modules\system\components\behaviors
 */
class CachedBehavior extends Behavior
{
    public $cache;

    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'deleteCache',
            ActiveRecord::EVENT_AFTER_UPDATE => 'deleteCache',
            ActiveRecord::EVENT_AFTER_DELETE => 'deleteCache',
        ];
    }
    public function deleteCache()
    {
        $this->cache->flush();
    }
}