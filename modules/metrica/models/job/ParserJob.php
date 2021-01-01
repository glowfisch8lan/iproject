<?php

namespace app\modules\metrica\models\job;

class ParserJob extends \yii\base\BaseObject implements \yii\queue\JobInterface
{
    public $text;
    public $file;

    public function execute($queue)
    {
        file_put_contents($this->file, $this->text);
    }
}
