<?php

namespace app\modules\av\modules\student\models\plugins;

use yii\base\Model;
use app\modules\av\modules\student\models\StudentsApi;
use app\modules\av\modules\student\models\plugins\AcademicPerformance;

class ConsolidatedStatement extends Model
{


    public $faculty;
    public $startDate = '01.01.2021';
    public $endDate = '13.02.2021';

    public function rules(){
        return [
            [
                ['faculty'],
                'required',
                'message' => 'Заполните поля!',

            ],
        ];

    }

    public function getGroupList(){
        $model = new AcademicPerformance();
        return $model->getGroupList();
    }

    public function getAverageFaculty($faculty)
    {
        $model = new AcademicPerformance();
        return $model->getAverageFaculty($faculty, $this->startDate, $this->endDate);
    }



}
