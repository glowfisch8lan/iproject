<?php


namespace app\modules\system\models\files;

use Yii;
use yii\web\UploadedFile;


class UploadManager extends UploadedFile
{

    public static $uploadPath = '/data/';
    public $file;
    private $uuid;

    /**
     * Генерация нового UUID.
     *
     * @return string
     */
    public function generateUUID()
    {
        $uuid = str_split(implode('', array_map(function($item) {
            $val = dechex(ord($item));
            if (strlen($val) < 2)
                $val = '0' . $val;

            return $val;
        }, str_split(openssl_random_pseudo_bytes(16), 1))), 4);

        return $uuid[ 0 ] . $uuid[ 1 ] . '-' . $uuid[ 2 ] . '-' . $uuid[ 3 ] . '-' . $uuid[ 4 ] . '-' . $uuid[ 5 ] . $uuid[ 6 ] . $uuid[ 7 ];
    }

    protected function getModule()
    {
        return Yii::$app->controller->module->id;
    }

    //TODO: Настроить валидацию
    public function upload()
    {
        $uuid = $this->generateUUID();
        $path = Yii::getAlias('@app') . self::$uploadPath . $this->getModule() .'/'. $uuid;
        $extension = $this->file->extension;
        $filename = md5(date('ymdhms') . '_' . $this->file->baseName);

        if(!file_exists($path)){mkdir($path, 0775, true);}
        if($this->file->saveAs($path . '/' . $filename . '.' . $extension))
        {
            return [$uuid, $filename, base64_encode($extension)];
        }

    }
}