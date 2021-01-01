<?php

namespace app\modules\metrica\models\settings;

use Yii;
use yii\base\Model;
use yii\web\Session;
use yii\web\UploadedFile;

class Settings extends Model
{

    const templatePath = '@webroot/resources/modules/metrica/reports/templates/';

    public $template;

    public function rules()
    {
        return [
            [['template'], 'safe'],
        ];
    }

    public function __construct($config = [])
    {
        Yii::setAlias('@temp', '@runtime/temp/metrica');

        parent::__construct($config);
    }

    public static function getTemplatePath(){

        return Yii::getAlias(self::templatePath);
    }

    public function upload()
    {
        if ($this->validate()) {
            $file = Settings::getTemplatePath() . 'template.docx';

            $this->template->saveAs($file);
            return true;
        } else {
            return false;
        }
    }
}