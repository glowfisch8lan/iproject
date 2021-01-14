<?php


namespace app\modules\system\helpers;


class FileUpload
{
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

}