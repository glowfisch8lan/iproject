<?php

namespace app\modules\tools\models;

use Yii;
use yii\base\Model;
use yii\helpers\FileHelper;
use app\modules\system\helpers\ArrayHelper;

class UploadForm extends Model
{
    public $file;

    public function rules(){
        return [
            [['file'], 'file',
            'mimeTypes' => 'application/vnd.ms-excel, text/csv, text/plain',
            'checkExtensionByMimeType' => true
            ]
        ];

    }

    public static function getUploadList(){

        $list = array_map(function ($item) {
            $date = filectime($item); //date("H:i:s m-d-Y ",
            return ['basename' => basename($item), 'dateModify' => $date];
        }, glob(realpath('resources/modules/tools/uploads/') . '/*'));
        ArrayHelper::multisort($list, ['dateModify'], [SORT_DESC]);
        return $list;
    }

    public function upload()
    {
        return ($this->validate()) ? $this->file->saveAs('resources/modules/tools/uploads/' . md5(date('ymdhms') . '_' . $this->file->baseName) . '.' . $this->file->extension): false;
    }
}