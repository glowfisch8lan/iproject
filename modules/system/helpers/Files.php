<?php


namespace app\modules\system\helpers;


class Files
{

    public static $uploadPath = '@app/data/files';

    public static function init($form, $model){
        return $form->field($model, 'file',
            ['template' =>
                "<div class=\"custom-file\"><div class=\"file-upload-wrapper\">{input}{label}</div></div><small class=\"text-muted\">Размер файла не должен превышать 1MB</small>"

            ])
            ->fileInput(
                [
                    'class' => 'custom-file-input file-upload'
                ]
            )->label('Выберите файл',['class' => 'custom-file-label']);
    }

    /**
     * Генерация нового UUID.
     *
     * @return string
     */
    public static function generateUUID()
    {
        $uuid = str_split(implode('', array_map(function($item) {
            $val = dechex(ord($item));
            if (strlen($val) < 2)
                $val = '0' . $val;

            return $val;
        }, str_split(openssl_random_pseudo_bytes(16), 1))), 4);

        return $uuid[ 0 ] . $uuid[ 1 ] . '-' . $uuid[ 2 ] . '-' . $uuid[ 3 ] . '-' . $uuid[ 4 ] . '-' . $uuid[ 5 ] . $uuid[ 6 ] . $uuid[ 7 ];
    }

}