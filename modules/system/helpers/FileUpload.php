<?php


namespace app\modules\system\helpers;


class FileUpload
{
    public static function widget($form, $model, $var = 'file'){
        return $form->field($model, $var,
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