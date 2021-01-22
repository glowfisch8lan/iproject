<?php


namespace app\modules\system\models\updates;

use Yii;
use ZipArchive;

/**
 * Class PatchManager
 *
 * @package app\models\update
 *
 * Обновление системы и установка модулей.
 */
class PatchManager
{
    /**
     * @var Manager|null Единственный объект класса (singleton).
     */

    protected static $_instance;
    public static $installDirectory = '@app/data/system/updates/';
    public static $patchFile = 'updates.zip';

    private function __construct() {
    }

    public static function getInstance() {
        if (self::$_instance === null) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }

    /**
     * Установка патча.
     *
     * @return string
     * @throws \Exception
     */
    public function installPatch()
    {

        if(!file_exists(Yii::getAlias(self::$installDirectory) . self::$patchFile))
            throw new \Exception('Update.zip file not found');

        $archive = new ZipArchive();
        $archive->open(Yii::getAlias(self::$installDirectory) . self::$patchFile);

        if(!file_exists(Yii::getAlias(self::$installDirectory) . 'temp'))
            mkdir(Yii::getAlias(PatchManager::$installDirectory) . 'temp', 0777, true );

        $files = [];
        for ($i = 0; $i < $archive->count(); $i++) {
            $file = $archive->statIndex($i)[ 'name' ];

            if (preg_match('#/$#', $file) || preg_match('#/\.$#', $file) || preg_match('#/\.\.$#', $file))
                continue;

            $files[] = $file;
        }

        // извлекаем файлы
        $count = 0;
        foreach ($files as $index => $file) {
            $path = pathinfo(Yii::getAlias('@app') . '/' . $file, PATHINFO_DIRNAME);

            // создаем необходимые папки
            if (!file_exists($path)) {
                if (!mkdir($path, 0777, true))
                    throw new \Exception('Невозможно создать папку ' . str_replace(Yii::getAlias('@app'), '', $path) . '.');
            }

            if (!file_put_contents(Yii::getAlias('@app') . '/' . $file, $archive->getFromName($file)))
                throw new \Exception('Невозможно записать данные в файл ' . $file . ' - ' . $file . ".");

            $count++;
        }

        $archive->close();
        unlink(Yii::getAlias(self::$installDirectory) . self::$patchFile);
        return 0;
    }




}